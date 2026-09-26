<x-layouts.admin title="Detail User | CafeSync" heading="Detail pengguna">
    <div class="page-heading">
        <div>
            <p><a class="button-link" href="{{ route('admin.users.index') }}">&larr; Kembali</a></p>
            <h1>{{ $user->name }}</h1>
            <p>{{ $user->email }} &middot; {{ $user->role }}</p>
        </div>
    </div>
    <section class="panel">
        <p>Status: {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</p><a class="button-link"
            href="{{ route('admin.users.edit', $user) }}">Edit user</a>
    </section>
</x-layouts.admin>
