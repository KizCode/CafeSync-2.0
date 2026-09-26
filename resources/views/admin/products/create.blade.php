<x-layouts.admin title="Tambah Produk | CafeSync" heading="Tambah produk">
    <div class="page-heading">
        <div>
            <p><a class="button-link" href="{{ route('admin.products.index') }}">&larr; Kembali</a></p>
            <h1>Tambah Produk</h1>
        </div>
    </div>
    <form class="panel form-grid" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.products.form')
        <button class="button-primary" type="submit">Simpan produk</button>
    </form>
</x-layouts.admin>
