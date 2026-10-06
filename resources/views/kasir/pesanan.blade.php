<x-layouts.kasir title="Pesanan hari ini | CafeSync">
    <header class="kasir-topbar">
        <div>
            <p class="kasir-kicker">Pesanan</p>
            <h1>Pesanan hari ini</h1>
        </div>
        <a class="kasir-btn" href="{{ route('kasir.index') }}">Buka POS</a>
    </header>

    <section class="kasir-stats" aria-label="Ringkasan pesanan">
        <article>
            <span>Lunas</span>
            <strong>{{ $paidCount }}</strong>
        </article>
        <article>
            <span>Belum lunas</span>
            <strong>{{ $openCount }}</strong>
        </article>
        <article>
            <span>Total</span>
            <strong>Rp {{ number_format($totalAmount, 0, ',', '.') }}</strong>
        </article>
    </section>

    <section class="kasir-panel">
        <div class="kasir-table-wrap">
            <table class="kasir-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <th scope="row">{{ $order->invoice_number }}</th>
                            <td>
                                {{ $order->customer_name ?: 'Umum' }}
                                <small class="kasir-muted">{{ $order->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                {{ $order->items->map(fn ($item) => ($item->product?->name ?? 'Menu').' ×'.$item->quantity)->join(', ') }}
                            </td>
                            <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td><span class="kasir-badge">{{ str_replace('_', ' ', $order->status) }}</span></td>
                            <td><a href="{{ route('kasir.show', $order) }}">Detail</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada pesanan hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.kasir>
