@props([
    'home' => null,
])

@php
    $user = auth()->user();
    $role = $user->role;
    $isAdmin = $role === 'admin';
    $home = $home ?? route($user->homeRoute());
    $roleLabel = match ($role) {
        'gudang' => 'Gudang',
        default => ucfirst($role),
    };
@endphp

<aside {{ $attributes->class(['app-sidebar mz-sidebar is-dark']) }} id="app-sidebar" data-sidebar>
    <a class="mz-brand" href="{{ $home }}">
        <x-brand-mark />
        <span>
            <strong>CafeSync</strong>
            <small>{{ $roleLabel }}</small>
        </span>
    </a>

    @unless ($role === 'owner')
        <p class="kasir-nav-label">Home</p>
    @endunless
    <nav class="mz-nav" aria-label="Navigasi utama">
        @if ($isAdmin)
            <a href="{{ route('admin.index') }}" @class(['is-active' => request()->routeIs('admin.index')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z" />
                </svg>
                Dashboard
            </a>
            <a href="{{ route('gudang.index') }}" @class(['is-active' => request()->routeIs('gudang.index')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                    <path d="M3.3 7 12 12l8.7-5M12 22V12" />
                </svg>
                Gudang
            </a>
        @endif

        @if ($role === 'kasir')
            <a href="{{ route('kasir.dashboard') }}" @class(['is-active' => request()->routeIs('kasir.dashboard')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z" />
                </svg>
                Dashboard
            </a>
        @endif

        @if ($role === 'owner')
            <a href="{{ route('owner.index') }}" @class(['is-active' => request()->routeIs('owner.index')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z" />
                </svg>
                Dashboard
            </a>
            <a href="{{ route('admin.laporan') }}" @class(['is-active' => request()->routeIs('admin.laporan')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 19V5M4 19h16M8 16V9M12 16V7M16 16v-4" />
                </svg>
                Laporan
            </a>
            <a href="{{ route('admin.analitik') }}" @class(['is-active' => request()->routeIs('admin.analitik')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 19 10 9l4 5 6-10" />
                </svg>
                Analitik
            </a>
        @endif

        @if ($role === 'gudang')
            <a href="{{ route('gudang.index') }}" @class(['is-active' => request()->routeIs('gudang.index')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z" />
                </svg>
                Dashboard
            </a>
        @endif

        @if ($isAdmin || $role === 'kasir')
            <p class="kasir-nav-label">Operasi</p>
            <a href="{{ route('kasir.index') }}" @class(['is-active' => request()->routeIs('kasir.index', 'kasir.create')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="14" rx="2" />
                    <path d="M8 21h8M12 18v3" />
                </svg>
                POS
            </a>
            <a href="{{ route('kasir.pesanan') }}" @class(['is-active' => request()->routeIs('kasir.pesanan')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M7 7h10M7 12h10M7 17h6" />
                    <rect x="3" y="4" width="18" height="16" rx="2" />
                </svg>
                Pesanan
            </a>
            <a href="{{ route('kasir.riwayat') }}" @class(['is-active' => request()->routeIs('kasir.riwayat')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <circle cx="12" cy="12" r="8" />
                    <path d="M12 8v4l3 2" />
                </svg>
                Riwayat
            </a>
        @endif

        @if ($isAdmin)
            <a href="{{ route('admin.laporan') }}" @class(['is-active' => request()->routeIs('admin.laporan')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 19V5M4 19h16M8 16V9M12 16V7M16 16v-4" />
                </svg>
                Laporan
            </a>
            <a href="{{ route('admin.analitik') }}" @class(['is-active' => request()->routeIs('admin.analitik')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 19 10 9l4 5 6-10" />
                </svg>
                Analitik
            </a>
            <p class="kasir-nav-label">Manajemen</p>
            <a href="{{ route('admin.products.index') }}" @class(['is-active' => request()->routeIs('admin.products.*')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                    <path d="M3.3 7 12 12l8.7-5M12 22V12" />
                </svg>
                Menu
            </a>
            <a href="{{ route('admin.users.index') }}" @class(['is-active' => request()->routeIs('admin.users.*')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                    <circle cx="9.5" cy="7" r="3" />
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                Pengguna
            </a>
            <a href="{{ route('admin.access') }}" @class(['is-active' => request()->routeIs('admin.access')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <rect x="5" y="11" width="14" height="10" rx="2" />
                    <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                </svg>
                Hak Akses
            </a>
        @endif
    </nav>
</aside>
