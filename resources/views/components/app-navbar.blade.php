@props([
    'heading',
    'home',
    'initials',
    'roleLabel',
])

@php
    $role = auth()->user()->role;
@endphp

<header class="app-navbar kasir-navbar mz-header">
    <button class="kasir-nav-toggle mz-icon-btn" type="button" aria-controls="app-sidebar" aria-expanded="false"
        data-sidebar-toggle>
        <span></span><span></span><span></span>
        <span class="sr-only">Buka navigasi</span>
    </button>
    <div class="kasir-navbar-title">
        <p>{{ $roleLabel }}</p>
        <h1>{{ $heading }}</h1>
    </div>
    <div class="kasir-navbar-tools">
        <time data-clock></time>
        @if (in_array($role, ['kasir', 'admin'], true))
            <a class="mz-icon-btn" href="{{ route('kasir.pesanan') }}" aria-label="Pesanan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="21" height="21">
                    <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5" />
                    <path d="M9 17a3 3 0 0 0 6 0" />
                </svg>
            </a>
            @if (! request()->routeIs('kasir.index'))
                <a class="kasir-navbar-pos mz-btn" href="{{ route('kasir.index') }}">Buka POS</a>
            @endif
        @endif
        <x-profile-menu :initials="$initials" />
    </div>
</header>
