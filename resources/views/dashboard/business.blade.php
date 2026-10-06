@php
    $donut = 'conic-gradient(#e5e7eb 0 100%)';
    $cursor = 0;
    $colors = ['#22c55e', '#86efac', '#bbf7d0', '#4ade80'];
    if ($paymentMix->isNotEmpty()) {
        $stops = [];
        foreach ($paymentMix as $index => $slice) {
            $next = $cursor + $slice['percent'];
            $color = $colors[$index % count($colors)];
            $stops[] = "{$color} {$cursor}% {$next}%";
            $cursor = $next;
        }
        $donut = 'conic-gradient('.implode(', ', $stops).')';
    }
@endphp

<div class="page-heading">
    <div>
        @if (auth()->user()->role === 'owner')
            <p class="kasir-kicker">Ringkasan toko</p>
            <h1>Selamat datang, {{ auth()->user()->name }}</h1>
            <p>Pantau pendapatan, transaksi, dan stok toko dari satu layar.</p>
        @else
            <h1>Selamat datang</h1>
            <p>Ringkasan penjualan, menu terlaris, dan stok yang perlu perhatian.</p>
        @endif
    </div>
</div>

<section class="admin-kpi" aria-label="Ringkasan hari ini">
    <article class="admin-kpi-card">
        <p>Total pendapatan</p>
        <strong>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</strong>
        @if ($revenueChange !== null)
            <small class="{{ $revenueChange >= 0 ? 'is-up' : 'is-down' }}">
                {{ $revenueChange >= 0 ? '+' : '' }}{{ $revenueChange }}% vs kemarin
            </small>
        @else
            <small>Hari ini</small>
        @endif
    </article>
    <article class="admin-kpi-card">
        <p>Total transaksi</p>
        <strong>{{ $todayTransactionCount }}</strong>
        <small>Pembayaran lunas hari ini</small>
    </article>
    <article class="admin-kpi-card">
        <p>Pengguna</p>
        <strong>{{ $userCount }}</strong>
        <small>Akun terdaftar</small>
    </article>
    <article class="admin-kpi-card">
        <p>Peringatan stok</p>
        <strong>{{ $lowStockCount }}</strong>
        <small>Item stok ≤ 5</small>
    </article>
</section>

<div class="admin-chart-grid">
    <section class="admin-card">
        <header>
            <h2>Penjualan 7 hari</h2>
        </header>
        <div class="admin-bars" role="img" aria-label="Grafik penjualan tujuh hari">
            @foreach ($dailySales as $day)
                <div class="admin-bar">
                    <span style="height: {{ max($day['percent'], 6) }}%"></span>
                    <small>{{ $day['label'] }}</small>
                </div>
            @endforeach
        </div>
    </section>
    <section class="admin-card">
        <header>
            <h2>Metode pembayaran</h2>
        </header>
        <div class="admin-donut-wrap">
            <div class="admin-donut" style="background: {{ $donut }}"></div>
            <ul>
                @forelse ($paymentMix as $slice)
                    <li>
                        <span>{{ $slice['label'] }}</span>
                        <strong>{{ $slice['percent'] }}%</strong>
                    </li>
                @empty
                    <li>Belum ada penjualan lunas.</li>
                @endforelse
            </ul>
        </div>
    </section>
</div>

<div class="dashboard-columns">
    <section class="admin-card">
        <header>
            <h2>Produk terlaris</h2>
        </header>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Menu</th>
                        <th scope="col">Terjual</th>
                        <th scope="col">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topProducts as $item)
                        <tr>
                            <th scope="row">{{ $item->product?->name ?? 'Produk dihapus' }}</th>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format((float) $item->revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Belum ada penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <section class="admin-card">
        <header>
            <h2>Peringatan stok</h2>
            @if (auth()->user()->role === 'admin')
                <a class="button-link" href="{{ route('admin.products.index') }}">Kelola menu</a>
            @endif
        </header>
        <ul class="stock-list">
            @forelse ($lowStockProducts as $product)
                <li>
                    <span>{{ $product->name }}</span>
                    <strong>{{ $product->stock }} tersisa</strong>
                </li>
            @empty
                <li>Semua stok masih aman.</li>
            @endforelse
        </ul>
    </section>
</div>

@if (auth()->user()->role === 'owner')
    <section class="admin-card owner-recent">
        <header>
            <h2>Transaksi terbaru</h2>
        </header>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Invoice</th>
                        <th scope="col">Pelanggan</th>
                        <th scope="col">Metode</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentTransactions as $transaction)
                        <tr>
                            <th scope="row">{{ $transaction->invoice_number }}</th>
                            <td>{{ $transaction->customer_name ?: 'Umum' }}</td>
                            <td>{{ ucfirst($transaction->payment_method) }}</td>
                            <td>Rp {{ number_format((float) $transaction->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada penjualan lunas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif
