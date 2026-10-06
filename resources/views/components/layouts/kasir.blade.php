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
        request()->routeIs('profile.edit') => 'Profil',
        default => 'Kasir',
    };
    $initials = strtoupper(mb_substr(auth()->user()->name, 0, 1));
    $home = route(auth()->user()->homeRoute());
    $roleLabel = match (auth()->user()->role) {
        'gudang' => 'Gudang',
        default => ucfirst(auth()->user()->role),
    };
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
    <x-sidebar :home="$home" />

    <div class="app-frame">
        <x-app-navbar :heading="$heading" :role-label="$roleLabel" :home="$home" :initials="$initials" />

        <div @class(['kasir-shell', 'is-pos' => request()->routeIs('kasir.index')])>
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
