<x-layouts.admin title="Tambah User | CafeSync" heading="Tambah pengguna">
    <div class="page-heading">
        <div>
            <p><a class="button-link" href="{{ route('admin.users.index') }}">&larr; Kembali</a></p>
            <h1>Tambah User</h1>
        </div>
    </div>
    <form class="panel form-grid" action="{{ route('admin.users.store') }}" method="POST">@csrf
        @include('admin.users.form')<button class="button-primary" type="submit">Simpan user</button></form>
</x-layouts.admin>
