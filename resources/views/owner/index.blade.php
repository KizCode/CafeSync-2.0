<x-layouts.app title="Owner Dashboard | CafeSync">
    <div class="page-heading">
        <div>
            <h1>Owner Dashboard</h1>
            <p>Pantau kesehatan bisnis coffee shop Anda.</p>
        </div>
    </div>
    <section class="dashboard-grid" aria-label="Ringkasan bisnis">
        <div class="stat-card">
            <dt>Penjualan hari ini</dt>
            <dd>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</dd>
        </div>
        <div class="stat-card">
            <dt>Transaksi hari ini</dt>
            <dd>{{ $todayTransactionCount }}</dd>
        </div>
        <div class="stat-card">
            <dt>Total produk</dt>
            <dd>{{ $productCount }}</dd>
        </div>
    </section>
    <div class="dashboard-columns">
        <section class="panel">
            <h2>Stok menipis</h2>
            <ul class="stock-list">
                @forelse ($lowStockProducts as $product)
                <li><span>{{ $product->name }}</span><strong>{{ $product->stock }}</strong></li>@empty<li>Aman</li>
                @endforelse
            </ul>
        </section>
        <section class="panel">
            <h2>Transaksi terbaru</h2>
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
</x-layouts.app>
