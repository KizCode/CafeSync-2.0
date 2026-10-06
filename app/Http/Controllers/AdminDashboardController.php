<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $role = request()->user()->role;

        abort_unless(in_array($role, ['admin', 'owner'], true), 403);

        $todayRevenue = $this->paidTotal(today());
        $yesterdayRevenue = $this->paidTotal(today()->subDay());

        return view($role === 'owner' ? 'owner.index' : 'admin.index', [
            'userCount' => User::query()->count(),
            'todayTransactionCount' => $this->paidCount(today()),
            'todayRevenue' => $todayRevenue,
            'revenueChange' => $this->percentChange($todayRevenue, $yesterdayRevenue),
            'lowStockCount' => Product::query()->where('stock', '<=', 5)->count(),
            'dailySales' => $this->dailySales(6),
            'paymentMix' => $this->paymentMix(),
            'topProducts' => $this->topProducts(),
            'lowStockProducts' => Product::query()->where('stock', '<=', 5)->orderBy('stock')->limit(5)->get(),
            'recentTransactions' => Transaction::query()
                ->where('status', 'lunas')
                ->latest()
                ->limit(6)
                ->get(),
        ]);
    }

    private function paidTotal(Carbon $day): float
    {
        return (float) Transaction::query()
            ->where('status', 'lunas')
            ->whereDate('created_at', $day)
            ->sum('grand_total');
    }

    private function paidCount(Carbon $day): int
    {
        return Transaction::query()
            ->where('status', 'lunas')
            ->whereDate('created_at', $day)
            ->count();
    }

    private function percentChange(float $current, float $previous): ?float
    {
        if ($previous <= 0) {
            return null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * @return Collection<int, array{label: string, total: float, percent: int}>
     */
    private function dailySales(int $daysBack): Collection
    {
        $points = collect();

        for ($offset = $daysBack; $offset >= 0; $offset--) {
            $day = today()->subDays($offset);
            $points->push([
                'label' => $day->translatedFormat('D'),
                'total' => $this->paidTotal($day),
            ]);
        }

        $max = max($points->max('total') ?: 0, 1);

        return $points->map(fn (array $point): array => [
            ...$point,
            'percent' => (int) round(($point['total'] / $max) * 100),
        ]);
    }

    /**
     * @return Collection<int, array{label: string, count: int, percent: float}>
     */
    private function paymentMix(): Collection
    {
        $rows = Transaction::query()
            ->where('status', 'lunas')
            ->selectRaw('payment_method, count(*) as aggregate')
            ->groupBy('payment_method')
            ->pluck('aggregate', 'payment_method');

        $total = max($rows->sum(), 1);

        return $rows->map(fn (mixed $count, string $method): array => [
            'label' => ucfirst($method),
            'count' => (int) $count,
            'percent' => round(((int) $count / $total) * 100, 1),
        ])->values();
    }

    /**
     * @return Collection<int, TransactionItem>
     */
    private function topProducts(): Collection
    {
        return TransactionItem::query()
            ->selectRaw('product_id, sum(quantity) as quantity, sum(total_price) as revenue')
            ->whereHas('transaction', fn ($query) => $query->where('status', 'lunas'))
            ->groupBy('product_id')
            ->orderByDesc('quantity')
            ->limit(5)
            ->with('product')
            ->get();
    }
}
