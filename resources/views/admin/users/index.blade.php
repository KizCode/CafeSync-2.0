<x-layouts.admin title="Kelola User | CafeSync" heading="Pengguna">
    <div class="page-heading">
        <div>
            <h1>Kelola User</h1>
            <p>Atur akses tim coffee shop.</p>
        </div>
        <a class="button-primary" href="{{ route('admin.users.create') }}">Tambah user</a>
    </div>

    <section class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Peran</th>
                        <th scope="col">Status</th>
                        <th scope="col"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->name }}</th>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="admin-badge is-read">{{ ucfirst($user->role) }}</span></td>
                            <td>
                                <span @class(['admin-badge', 'is-write' => $user->is_active, 'is-none' => ! $user->is_active])>
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="admin-table-actions">
                                <a class="button-link" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.admin>
