<x-layouts.admin title="Edit Produk | CafeSync" heading="Edit produk">
    <div class="page-heading">
        <div>
            <p><a class="button-link" href="{{ route('admin.products.index') }}">&larr; Kembali</a></p>
            <h1>Edit Produk</h1>
        </div>
    </div>
    <form class="panel form-grid" action="{{ route('admin.products.update', $product) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.products.form')
        <button class="button-primary" type="submit">Simpan perubahan</button>
    </form>
</x-layouts.admin>
