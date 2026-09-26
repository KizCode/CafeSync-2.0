<x-layouts.admin title="Dashboard Admin | CafeSync" heading="Dashboard">
    <div class="page-heading">
        <div>
            <h1>Ringkasan backoffice</h1>
            <p>Kelola pengguna, menu, dan pantau penjualan tanpa masuk ke POS kasir.</p>
        </div>
    </div>

    <section class="admin-shortcuts" aria-label="Aksi cepat">
        <a href="{{ route('admin.users.index') }}">
            <strong>Pengguna</strong>
            <span>Tambah, ubah, dan nonaktifkan akun tim.</span>
        </a>
        <a href="{{ route('admin.products.index') }}">
            <strong>Produk</strong>
            <span>Atur menu, harga, dan stok untuk kasir.</span>
        </a>
        <a href="{{ route('admin.access') }}">
            <strong>Hak akses</strong>
            <span>Lihat matriks peran. Halaman ini khusus admin.</span>
        </a>
    </section>

    <section class="dashboard-grid" aria-label="Ringkasan">
        <div class="stat-card">
            <dt>Total pengguna</dt>
            <dd>{{ $userCount }}</dd>
        </div>
        <div class="stat-card">
            <dt>Transaksi hari ini</dt>
            <dd>{{ $todayTransactionCount }}</dd>
        </div>
        <div class="stat-card">
            <dt>Pendapatan hari ini</dt>
            <dd>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</dd>
        </div>
    </section>

    <div class="dashboard-columns">
        <section class="panel">
            <h2>Stok menipis</h2>
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
            <p><a class="button-link" href="{{ route('admin.products.index') }}">Kelola produk</a></p>
        </section>

        <section class="panel">
            <h2>Penjualan terbaru</h2>
            <ul class="transaction-list">
                @forelse ($recentTransactions as $transaction)
                    <li>
                        <span>{{ $transaction->invoice_number }}</span>
                        <strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong>
                    </li>
                @empty
                    <li>Belum ada transaksi.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-layouts.admin>
