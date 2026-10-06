<x-layouts.admin title="Analitik Bisnis | CafeSync" heading="Analitik" :surface="auth()->user()->role === 'owner' ? 'cream' : 'paper'">
    <div class="page-heading">
        <div>
            @if (auth()->user()->role === 'owner')
                <p class="kasir-kicker">Analitik</p>
            @endif
            <h1>Analitik bisnis</h1>
            <p>Pola pembayaran dan tren penjualan.</p>
        </div>
    </div>

    <div @class(['admin-chart-grid owner-analytics' => auth()->user()->role === 'owner'])>
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
            <h2>Metode pembayaran</h2>
        </header>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Metode</th>
                        <th scope="col">Transaksi</th>
                        <th scope="col">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paymentMix as $row)
                        <tr>
                            <th scope="row">{{ ucfirst($row->payment_method) }}</th>
                            <td>{{ $row->aggregate }}</td>
                            <td>Rp {{ number_format((float) $row->revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Belum ada data analitik.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    </div>
</x-layouts.admin>
