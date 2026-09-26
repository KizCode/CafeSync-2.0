<x-layouts.admin title="Detail Produk | CafeSync" heading="Detail produk">
    <div class="page-heading">
        <div>
            <p><a class="button-link" href="{{ route('admin.products.index') }}">&larr; Kembali</a></p>
            <h1>{{ $product->name }}</h1>
            <p>{{ $product->category?->name ?? 'Tanpa kategori' }}</p>
        </div>
    </div>
    <section class="panel">
        <p>{{ $product->description ?: 'Tidak ada deskripsi.' }}</p>
        <p>Harga: <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></p>
        <p>Stok: <strong>{{ $product->stock }}</strong></p><a class="button-link"
            href="{{ route('admin.products.edit', $product) }}">Edit produk</a>
    </section>
</x-layouts.admin>
