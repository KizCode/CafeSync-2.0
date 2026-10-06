<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class KasirController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $todaySales = Transaction::query()
            ->visibleTo($user)
            ->whereDate('created_at', today())
            ->where('status', 'lunas');

        $todayTotal = (float) (clone $todaySales)->sum('grand_total');
        $todayCount = (clone $todaySales)->count();

        return view('kasir.dashboard', [
            'todayTotal' => $todayTotal,
            'todayCount' => $todayCount,
            'todayItemCount' => (int) TransactionItem::query()
                ->whereHas(
                    'transaction',
                    fn ($query) => $query
                        ->visibleTo($user)
                        ->whereDate('created_at', today())
                        ->where('status', 'lunas')
                )
                ->sum('quantity'),
            'todayAverage' => $todayCount > 0 ? $todayTotal / $todayCount : 0,
            'transactions' => Transaction::query()
                ->visibleTo($user)
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }

    public function index(): View
    {
        return view('kasir.index', [
            'categories' => Category::query()->orderBy('name')->get(),
            'products' => Product::query()
                ->with('category')
                ->where('stock', '>', 0)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function prepare(Request $request): RedirectResponse
    {
        $request->merge([
            'items' => collect($request->input('items', []))
                ->filter(fn (array $item): bool => (int) ($item['quantity'] ?? 0) > 0)
                ->values()
                ->all(),
        ]);

        $cart = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $request->session()->put('kasir_cart', $cart);

        return to_route('kasir.create');
    }

    public function create(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('kasir_cart');

        if (! is_array($cart) || blank($cart['items'] ?? null)) {
            return to_route('kasir.index')->with('status', 'Pilih menu terlebih dahulu.');
        }

        $products = Product::query()
            ->whereIn('id', collect($cart['items'])->pluck('product_id'))
            ->get()
            ->keyBy('id');

        $lines = collect($cart['items'])->map(function (array $item) use ($products): array {
            $product = $products->get($item['product_id']);
            $quantity = (int) $item['quantity'];
            $lineTotal = (float) $product->price * $quantity;

            return [
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ];
        });

        return view('kasir.create', [
            'cart' => $cart,
            'lines' => $lines,
            'subtotal' => $lines->sum('line_total'),
        ]);
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $transaction = DB::transaction(function () use ($request): Transaction {
            $items = [];
            $subtotal = 0.0;

            foreach ($request->validated('items') as $item) {
                $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak mencukupi.",
                    ]);
                }

                $totalPrice = (float) $product->price * $item['quantity'];
                $subtotal += $totalPrice;
                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $totalPrice,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $paidAmount = (float) $request->validated('paid_amount');

            if ($paidAmount < $subtotal) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Jumlah pembayaran kurang dari total transaksi.',
                ]);
            }

            $transaction = Transaction::query()->create([
                'user_id' => $request->user()->id,
                'invoice_number' => 'INV-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                'subtotal' => $subtotal,
                'grand_total' => $subtotal,
                'payment_method' => $request->validated('payment_method'),
                'paid_amount' => $paidAmount,
                'change_amount' => $paidAmount - $subtotal,
                'status' => 'lunas',
                'customer_name' => $request->validated('customer_name'),
            ]);

            $transaction->items()->createMany($items);

            return $transaction;
        });

        $request->session()->forget('kasir_cart');

        return to_route('kasir.show', $transaction)->with('status', 'Pembayaran berhasil.');
    }

    public function pesanan(Request $request): View
    {
        $user = $request->user();

        $orders = Transaction::query()
            ->visibleTo($user)
            ->with('items.product')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view('kasir.pesanan', [
            'orders' => $orders,
            'paidCount' => $orders->where('status', 'lunas')->count(),
            'openCount' => $orders->where('status', 'belum_lunas')->count(),
            'totalAmount' => $orders->sum('grand_total'),
        ]);
    }

    public function riwayat(Request $request): View
    {
        return view('kasir.riwayat', [
            'transactions' => Transaction::query()
                ->visibleTo($request->user())
                ->with('items')
                ->latest()
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    public function show(Request $request, Transaction $transaction): View
    {
        $this->ensureVisible($request->user(), $transaction);

        return view('kasir.show', [
            'transaction' => $transaction->load(['items.product', 'cashier']),
        ]);
    }

    public function struk(Request $request, Transaction $transaction): View
    {
        $this->ensureVisible($request->user(), $transaction);

        return view('kasir.struk', [
            'transaction' => $transaction->load(['items.product', 'cashier']),
        ]);
    }

    public function edit(Request $request, Transaction $transaction): View
    {
        $this->ensureVisible($request->user(), $transaction);

        return view('kasir.edit', compact('transaction'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $this->ensureVisible($request->user(), $transaction);

        $attributes = $request->validated();
        $paidAmount = (float) $attributes['paid_amount'];

        if ($paidAmount < (float) $transaction->grand_total) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Jumlah pembayaran kurang dari total transaksi.',
            ]);
        }

        $transaction->update([
            ...$attributes,
            'change_amount' => $paidAmount - (float) $transaction->grand_total,
            'status' => 'lunas',
        ]);

        return to_route('kasir.show', $transaction)->with('status', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->ensureVisible($request->user(), $transaction);

        DB::transaction(function () use ($transaction): void {
            $transaction->load('items');

            foreach ($transaction->items as $item) {
                Product::query()->whereKey($item->product_id)->increment('stock', $item->quantity);
            }

            $transaction->delete();
        });

        return to_route('kasir.index')->with('status', 'Transaksi dibatalkan dan stok dikembalikan.');
    }

    private function ensureVisible(User $user, Transaction $transaction): void
    {
        abort_unless($transaction->isVisibleTo($user), 404);
    }
}
