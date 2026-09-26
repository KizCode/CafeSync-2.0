<x-layouts.admin title="Edit User | CafeSync" heading="Edit pengguna">
    <div class="page-heading">
        <div>
            <p><a class="button-link" href="{{ route('admin.users.index') }}">&larr; Kembali</a></p>
            <h1>Edit User</h1>
        </div>
    </div>
    <form class="panel form-grid" action="{{ route('admin.users.update', $user) }}" method="POST">@csrf @method('PUT')
        @include('admin.users.form')<button class="button-primary" type="submit">Simpan perubahan</button></form>
</x-layouts.admin>
