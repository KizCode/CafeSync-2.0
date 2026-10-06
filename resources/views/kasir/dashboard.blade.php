<x-layouts.kasir title="Dashboard Kasir | CafeSync">
    <header class="kasir-topbar">
        <div>
            <p class="kasir-kicker">Dashboard</p>
            <h1>Ringkasan hari ini</h1>
            <p class="kasir-muted">Penjualan atas akun kasir yang sedang masuk.</p>
        </div>
        <a class="kasir-btn" href="{{ route('kasir.index') }}">Buka POS</a>
    </header>

    <section class="kasir-stats" aria-label="Ringkasan hari ini">
        <article>
            <span>Penjualan hari ini</span>
            <strong>Rp {{ number_format($todayTotal, 0, ',', '.') }}</strong>
        </article>
        <article>
            <span>Transaksi</span>
            <strong>{{ $todayCount }}</strong>
        </article>
        <article>
            <span>Item terjual</span>
            <strong>{{ $todayItemCount }}</strong>
        </article>
        <article>
            <span>Rata-rata transaksi</span>
            <strong>Rp {{ number_format($todayAverage, 0, ',', '.') }}</strong>
        </article>
    </section>

    <section class="kasir-panel">
        <div class="kasir-panel-head">
            <h2>Transaksi terbaru</h2>
            <a href="{{ route('kasir.riwayat') }}">Lihat semua</a>
        </div>
        <div class="kasir-table-wrap">
            <table class="kasir-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Waktu</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <th scope="row">
                                <a href="{{ route('kasir.show', $transaction) }}">{{ $transaction->invoice_number }}</a>
                            </th>
                            <td>{{ $transaction->customer_name ?: 'Umum' }}</td>
                            <td>{{ $transaction->created_at->timezone(config('app.timezone'))->format('H:i') }}</td>
                            <td>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.kasir>
