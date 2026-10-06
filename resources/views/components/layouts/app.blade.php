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
        <x-sidebar :home="$home" />

        <div class="app-frame">
            <x-app-navbar :heading="$navTitle" :role-label="$roleLabel" :home="$home" :initials="$initials" />
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
