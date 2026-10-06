<x-layouts.admin title="Hak Akses | CafeSync" heading="Hak Akses">
    <div class="page-heading">
        <div>
            <h1>Hak akses peran</h1>
            <p>Admin dapat membuka semua halaman. Peran lain tidak masuk ke area admin.</p>
        </div>
    </div>

    <section class="admin-table-card access-legend" aria-label="Keterangan kode">
        <div class="admin-table-toolbar">
            <h2>Keterangan</h2>
        </div>
        <dl>
            <div><dt>C</dt><dd>Create</dd></div>
            <div><dt>R</dt><dd>Read</dd></div>
            <div><dt>U</dt><dd>Update</dd></div>
            <div><dt>D</dt><dd>Delete</dd></div>
            <div><dt>CRUD</dt><dd>Create, Read, Update, Delete</dd></div>
            <div><dt>RU</dt><dd>Read, Update</dd></div>
            <div><dt>CR</dt><dd>Create, Read</dd></div>
            <div><dt>—</dt><dd>Tidak memiliki akses</dd></div>
        </dl>
    </section>

    <section class="admin-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table access-matrix">
                <caption class="sr-only">Matriks hak akses per fitur dan peran</caption>
                <thead>
                    <tr>
                        <th scope="col">Fitur</th>
                        @foreach ($roles as $label)
                            <th scope="col">{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <th scope="row">{{ $row['feature'] }}</th>
                            @foreach (array_keys($roles) as $role)
                                @php
                                    $access = $row[$role];
                                @endphp
                                <td>
                                    <span @class(['admin-badge', 'is-none' => $access === '—', 'is-read' => $access === 'R', 'is-write' => in_array($access, ['CRUD', 'RU', 'CR'], true)])>{{ $access }}</span>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.admin>
