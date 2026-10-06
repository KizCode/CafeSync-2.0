@props([
    'title' => 'Admin | CafeSync',
    'heading' => 'Dashboard',
    'surface' => 'paper',
])

@php
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

<body @class(['mz-body', 'admin-body' => $surface === 'paper', 'kasir-body owner-body' => $surface === 'cream'])>
    <div class="sidebar-overlay" data-sidebar-overlay></div>
    <x-sidebar :home="$home" />

    <div class="app-frame">
        <x-app-navbar :heading="$heading" :role-label="$roleLabel" :home="$home" :initials="$initials" />

        <main @class(['app-main' => $surface === 'paper', 'kasir-shell' => $surface === 'cream'])>
            @if (session('status'))
                <p @class(['alert' => $surface === 'paper', 'kasir-alert' => $surface === 'cream']) role="status">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <ul @class([
                    'alert' => $surface === 'paper',
                    'kasir-alert' => $surface === 'cream',
                    'kasir-alert-error' => $surface === 'cream',
                ]) role="alert">
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
