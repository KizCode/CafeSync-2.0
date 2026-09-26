<x-layouts.kasir title="Dashboard Kasir | CafeSync">
    <header class="kasir-topbar">
        <div>
            <p class="kasir-kicker">Kasir Dashboard</p>
            <h1>Ringkasan hari ini</h1>
        </div>
        <a class="kasir-btn" href="{{ route('kasir.index') }}">Buka POS</a>
    </header>

    <section class="kasir-stats">
        <article>
            <span>Penjualan hari ini</span>
            <strong>Rp {{ number_format($todayTotal, 0, ',', '.') }}</strong>
        </article>
        <article>
            <span>Transaksi</span>
            <strong>{{ $todayCount }}</strong>
        </article>
    </section>

    <section class="kasir-panel">
        <div class="kasir-panel-head">
            <h2>Transaksi terbaru</h2>
            <a href="{{ route('kasir.riwayat') }}">Lihat semua</a>
        </div>
        <ul class="kasir-order-list">
            @forelse ($transactions as $transaction)
                <li>
                    <div>
                        <a href="{{ route('kasir.show', $transaction) }}">{{ $transaction->invoice_number }}</a>
                        <small>{{ $transaction->customer_name ?: 'Umum' }} ·
                            {{ $transaction->created_at->timezone(config('app.timezone'))->format('H:i') }}</small>
                    </div>
                    <strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong>
                </li>
            @empty
                <li class="kasir-empty">Belum ada transaksi.</li>
            @endforelse
        </ul>
    </section>
</x-layouts.kasir>
