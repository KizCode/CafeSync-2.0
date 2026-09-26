<x-layouts.admin title="Produk & Menu | CafeSync" heading="Produk">
    <div class="page-heading">
        <div>
            <h1>Produk & Menu</h1>
            <p>Kelola menu yang tersedia di kasir.</p>
        </div>
        <a class="button-primary" href="{{ route('admin.products.create') }}">Tambah produk</a>
    </div>

    <section class="admin-table-card">
        <div class="admin-table-toolbar">
            <div class="field">
                <label for="product-search">Cari menu</label>
                <input id="product-search" type="search" placeholder="Nama menu atau kategori">
            </div>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Menu</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Harga</th>
                        <th scope="col"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr data-product-row
                            data-search="{{ $product->name }} {{ $product->category?->name }}">
                            <th scope="row">{{ $product->name }}</th>
                            <td>{{ $product->category?->name ?? 'Tanpa kategori' }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="admin-table-actions">
                                <a class="button-link" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.admin>
