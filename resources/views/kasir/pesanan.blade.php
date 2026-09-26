<x-layouts.kasir title="Pesanan hari ini | CafeSync">
    <header class="kasir-topbar">
        <div>
            <p class="kasir-kicker">Daftar Pesanan</p>
            <h1>Pesanan hari ini</h1>
        </div>
        <a class="kasir-btn" href="{{ route('kasir.index') }}">POS</a>
    </header>

    <section class="kasir-stats">
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

    <div class="kasir-order-grid">
        @forelse ($orders as $order)
            <article class="kasir-panel">
                <div class="kasir-panel-head">
                    <h2>{{ $order->invoice_number }}</h2>
                    <span class="kasir-badge">{{ str_replace('_', ' ', $order->status) }}</span>
                </div>
                <p class="kasir-muted">{{ $order->customer_name ?: 'Umum' }} ·
                    {{ $order->created_at->format('H:i') }}</p>
                <ul>
                    @foreach ($order->items as $item)
                        <li>{{ $item->product->name }} x{{ $item->quantity }}</li>
                    @endforeach
                </ul>
                <strong>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong>
                <a class="kasir-text-link" href="{{ route('kasir.show', $order) }}">Detail</a>
            </article>
        @empty
            <p class="kasir-empty">Belum ada pesanan hari ini.</p>
        @endforelse
    </div>
</x-layouts.kasir>
