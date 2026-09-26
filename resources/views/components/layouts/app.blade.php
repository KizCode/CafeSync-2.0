@props([
    'title' => 'CafeSync',
    'heading' => null,
])

@php
    $navTitle = $heading ?? trim(explode('|', $title)[0]);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="app-body mz-body">
    @auth
        @php
            $initials = strtoupper(mb_substr(auth()->user()->name, 0, 1));
            $home = route(auth()->user()->homeRoute());
            $roleLabel = match (auth()->user()->role) {
                'gudang' => 'Gudang',
                default => ucfirst(auth()->user()->role),
            };
        @endphp

        <div class="sidebar-overlay" data-sidebar-overlay></div>
        <x-sidebar :home="$home" :role="$roleLabel">
            <p class="kasir-nav-label">Home</p>
            <nav class="mz-nav" aria-label="Navigasi utama">
                @if (auth()->user()->role === 'owner')
                    <a href="{{ route('owner.index') }}" @class(['is-active' => request()->routeIs('owner.index')])>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z" />
                        </svg>
                        Dashboard
                    </a>
                @endif
                @if (auth()->user()->role === 'gudang')
                    <a href="{{ route('gudang.index') }}" @class(['is-active' => request()->routeIs('gudang.index')])>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z" />
                        </svg>
                        Dashboard
                    </a>
                @endif
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
                    <p>{{ $roleLabel }}</p>
                    <h1>{{ $navTitle }}</h1>
                </div>
                <div class="kasir-navbar-tools">
                    <time data-clock></time>
                    <x-profile-menu :home="$home" :initials="$initials" />
                </div>
            </header>
            <main class="app-main">
    @else
        <main class="app-main app-main-guest">
    @endauth
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
        @auth
        </div>
        @endauth
</body>

</html>
