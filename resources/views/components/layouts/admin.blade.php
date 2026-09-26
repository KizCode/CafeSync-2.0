@props([
    'title' => 'Admin | CafeSync',
    'heading' => 'Dashboard',
])

@php
    $initials = strtoupper(mb_substr(auth()->user()->name, 0, 1));
    $home = route('admin.index');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="mz-body admin-body">
    <div class="sidebar-overlay" data-sidebar-overlay></div>

    <x-sidebar :home="$home" role="Admin">
        <p class="kasir-nav-label">Home</p>
        <nav class="mz-nav" aria-label="Navigasi admin">
            <a href="{{ route('admin.index') }}" @class(['is-active' => request()->routeIs('admin.index')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z" />
                </svg>
                Dashboard
            </a>
            <p class="kasir-nav-label">Manajemen</p>
            <a href="{{ route('admin.users.index') }}" @class(['is-active' => request()->routeIs('admin.users.*')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                    <circle cx="9.5" cy="7" r="3" />
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                Pengguna
            </a>
            <a href="{{ route('admin.products.index') }}" @class(['is-active' => request()->routeIs('admin.products.*')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                    <path d="M3.3 7 12 12l8.7-5M12 22V12" />
                </svg>
                Produk
            </a>
            <a href="{{ route('admin.access') }}" @class(['is-active' => request()->routeIs('admin.access')])>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <rect x="5" y="11" width="14" height="10" rx="2" />
                    <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                </svg>
                Hak Akses
            </a>
        </nav>
    </x-sidebar>

    <div class="app-frame">
        <header class="app-navbar mz-header">
            <button class="kasir-nav-toggle mz-icon-btn" type="button" aria-controls="app-sidebar" aria-expanded="false"
                data-sidebar-toggle>
                <span></span><span></span><span></span>
                <span class="sr-only">Buka navigasi</span>
            </button>
            <div class="kasir-navbar-title">
                <p>Admin</p>
                <h1>{{ $heading }}</h1>
            </div>
            <div class="kasir-navbar-tools">
                <time data-clock></time>
                <x-profile-menu :home="$home" :initials="$initials" />
            </div>
        </header>

        <main class="app-main">
            @if (session('status'))
                <p class="alert" role="status">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <ul class="alert" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>

</html>
