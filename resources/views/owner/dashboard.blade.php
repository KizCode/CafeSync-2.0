@php
    $hour = now()->timezone(config('app.timezone'))->hour;
    $greeting = match (true) {
        $hour < 11 => 'Selamat pagi',
        $hour < 15 => 'Selamat siang',
        $hour < 18 => 'Selamat sore',
        default => 'Selamat malam',
    };

    $donut = 'conic-gradient(#e5e7eb 0 100%)';
    $cursor = 0;
    $colors = ['#22c55e', '#4ade80', '#86efac', '#bbf7d0'];
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

<header class="owner-hero">
    <div>
        <p class="kasir-kicker">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h1>{{ $greeting }}, {{ auth()->user()->name }}</h1>
        <p>Pantau pendapatan, transaksi, dan stok toko dari satu layar.</p>
    </div>
</header>

<section class="owner-stats" aria-label="Ringkasan hari ini">
    <article>
        <span class="owner-stat-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M4 10h16M6 10V8a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2M7 14h.01M12 14h.01M17 14h.01" />
                <rect x="4" y="10" width="16" height="10" rx="2" />
            </svg>
        </span>
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
    <article>
        <span class="owner-stat-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M7 7h10M7 12h10M7 17h6" />
                <rect x="3" y="4" width="18" height="16" rx="2" />
            </svg>
        </span>
        <p>Total transaksi</p>
        <strong>{{ $todayTransactionCount }}</strong>
        <small>Pembayaran lunas hari ini</small>
    </article>
    <article>
        <span class="owner-stat-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                <circle cx="9.5" cy="7" r="3" />
                <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
        </span>
        <p>Pengguna</p>
        <strong>{{ $userCount }}</strong>
        <small>Akun terdaftar</small>
    </article>
    <article>
        <span class="owner-stat-icon is-warn" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M12 9v4M12 17h.01" />
                <path d="M10.3 4.7 2.8 18a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3L13.7 4.7a2 2 0 0 0-3.4 0Z" />
            </svg>
        </span>
        <p>Peringatan stok</p>
        <strong>{{ $lowStockCount }}</strong>
        <small>Item stok ≤ 5</small>
    </article>
</section>

<div class="owner-charts">
    <section class="owner-panel">
        <header>
            <div>
                <h2>Penjualan 7 hari</h2>
                <p>Pendapatan transaksi lunas</p>
            </div>
        </header>
        <div class="admin-bars owner-bars" role="img" aria-label="Grafik penjualan tujuh hari">
            @foreach ($dailySales as $day)
                <div class="admin-bar">
                    <span style="height: {{ max($day['percent'], 8) }}%"></span>
                    <small>{{ $day['label'] }}</small>
                </div>
            @endforeach
        </div>
    </section>
    <section class="owner-panel">
        <header>
            <div>
                <h2>Metode pembayaran</h2>
                <p>Bagi hasil pembayaran</p>
            </div>
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

<div class="owner-split">
    <section class="owner-panel">
        <header>
            <div>
                <h2>Produk terlaris</h2>
                <p>Menu dengan penjualan tertinggi</p>
            </div>
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

    <div class="owner-side">
        <section class="owner-panel">
            <header>
                <div>
                    <h2>Peringatan stok</h2>
                    <p>Item yang hampir habis</p>
                </div>
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
        <section class="owner-panel">
            <header>
                <div>
                    <h2>Transaksi terbaru</h2>
                    <p>Pembayaran lunas terakhir</p>
                </div>
            </header>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th scope="col">Invoice</th>
                            <th scope="col">Metode</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $transaction)
                            <tr>
                                <th scope="row">{{ $transaction->invoice_number }}</th>
                                <td>{{ ucfirst($transaction->payment_method) }}</td>
                                <td>Rp {{ number_format((float) $transaction->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Belum ada penjualan lunas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
