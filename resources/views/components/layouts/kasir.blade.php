@props([
    'title' => 'Kasir | CafeSync',
])

@php
    $heading = match (true) {
        request()->routeIs('kasir.dashboard') => 'Dashboard',
        request()->routeIs('kasir.index') => 'Point of Sale',
        request()->routeIs('kasir.create') => 'Pembayaran',
        request()->routeIs('kasir.pesanan') => 'Pesanan',
        request()->routeIs('kasir.riwayat') => 'Riwayat',
        request()->routeIs('kasir.show', 'kasir.edit') => 'Detail transaksi',
        default => 'Kasir',
    };
    $initials = strtoupper(mb_substr(auth()->user()->name, 0, 1));
    $home = route('kasir.dashboard');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="kasir-body mz-body">
    <div class="sidebar-overlay" data-sidebar-overlay></div>

    <x-sidebar :home="$home" role="Kasir">
        <p class="kasir-nav-label">Home</p>
        <nav class="mz-nav" aria-label="Navigasi kasir">
            <a href="{{ route('kasir.dashboard') }}" @class(['is-active' => request()->routeIs('kasir.dashboard')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z" />
                </svg>
                Dashboard
            </a>
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
        </nav>
    </x-sidebar>

    <div class="kasir-frame">
        <header class="kasir-navbar mz-header">
            <button class="kasir-nav-toggle mz-icon-btn" type="button" aria-controls="app-sidebar" aria-expanded="false"
                data-sidebar-toggle>
                <span></span><span></span><span></span>
                <span class="sr-only">Buka navigasi</span>
            </button>

            <div class="kasir-navbar-title">
                <p>Kasir</p>
                <h1>{{ $heading }}</h1>
            </div>

            <div class="kasir-navbar-tools">
                <time data-clock></time>
                <a class="mz-icon-btn" href="{{ route('kasir.pesanan') }}" aria-label="Pesanan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="21" height="21">
                        <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5" />
                        <path d="M9 17a3 3 0 0 0 6 0" />
                    </svg>
                </a>
                @if (! request()->routeIs('kasir.index'))
                    <a class="kasir-navbar-pos mz-btn" href="{{ route('kasir.index') }}">Buka POS</a>
                @endif
                <x-profile-menu :home="$home" :initials="$initials" />
            </div>
        </header>

        <div class="kasir-shell">
            @if (session('status'))
                <p class="kasir-alert" role="status">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <ul class="kasir-alert kasir-alert-error" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            {{ $slot }}
        </div>
    </div>
</body>

</html>
