<x-layouts.app title="Gudang Dashboard | CafeSync">
    <div class="page-heading">
        <div>
            <h1>Dashboard Gudang</h1>
            <p>Pantau ketersediaan menu dan stok bahan.</p>
        </div>
    </div>
    <section class="dashboard-grid" aria-label="Ringkasan gudang">
        <div class="stat-card">
            <dt>Total produk</dt>
            <dd>{{ $productCount }}</dd>
        </div>
        <div class="stat-card">
            <dt>Stok menipis</dt>
            <dd>{{ $lowStockProducts->count() }}</dd>
        </div>
        <div class="stat-card">
            <dt>Supplier</dt>
            <dd>Segera</dd>
        </div>
    </section>
    <section class="panel">
        <h2>Perlu perhatian</h2>
        <ul class="stock-list">
            @forelse ($lowStockProducts as $product)
            <li><span>{{ $product->name }}</span><strong>{{ $product->stock }} tersisa</strong></li>@empty<li>Semua
                    stok aman.</li>
            @endforelse
        </ul>
    </section>
</x-layouts.app>
