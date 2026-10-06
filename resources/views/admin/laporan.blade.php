<x-layouts.admin title="Laporan Penjualan | CafeSync" heading="Laporan" :surface="auth()->user()->role === 'owner' ? 'cream' : 'paper'">
    <div class="page-heading">
        <div>
            @if (auth()->user()->role === 'owner')
                <p class="kasir-kicker">Laporan</p>
            @endif
            <h1>Laporan penjualan</h1>
            <p>Kinerja bulan ini dan tren enam bulan terakhir.</p>
        </div>
        <a @class(['button-primary', 'owner-export' => auth()->user()->role === 'owner']) href="{{ route('admin.laporan.export') }}">Export CSV</a>
    </div>

    <section class="admin-kpi" aria-label="Ringkasan bulan ini">
        <article class="admin-kpi-card">
            <p>Total pendapatan</p>
            <strong>Rp {{ number_format($monthRevenue, 0, ',', '.') }}</strong>
            <small>{{ now()->translatedFormat('F Y') }}</small>
        </article>
        <article class="admin-kpi-card">
            <p>Total pengeluaran</p>
            <strong>Rp {{ number_format($monthExpense, 0, ',', '.') }}</strong>
            <small>Belum tercatat di sistem</small>
        </article>
        <article class="admin-kpi-card">
            <p>Laba bersih</p>
            <strong>Rp {{ number_format($monthProfit, 0, ',', '.') }}</strong>
            <small>Pendapatan − pengeluaran</small>
        </article>
        <article class="admin-kpi-card">
            <p>Total transaksi</p>
            <strong>{{ $monthCount }}</strong>
            <small>Pembayaran lunas</small>
        </article>
    </section>

    <section class="admin-card">
        <header>
            <h2>Tren 6 bulan terakhir</h2>
        </header>
        <div class="admin-bars" role="img" aria-label="Grafik pendapatan enam bulan">
            @foreach ($monthlySales as $month)
                <div class="admin-bar">
                    <span style="height: {{ max($month['percent'], 6) }}%"></span>
                    <small>{{ $month['label'] }}</small>
                </div>
            @endforeach
        </div>
    </section>

    <section class="admin-card">
        <header>
            <h2>Transaksi terbaru</h2>
        </header>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Invoice</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Metode</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentTransactions as $transaction)
                        <tr>
                            <th scope="row">{{ $transaction->invoice_number }}</th>
                            <td>{{ $transaction->created_at?->format('d M Y H:i') }}</td>
                            <td>{{ ucfirst($transaction->payment_method) }}</td>
                            <td>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada transaksi lunas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.admin>
