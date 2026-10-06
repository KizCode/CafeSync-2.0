@php
    $title = 'Profil | CafeSync';
    $heading = 'Profil';
    $role = auth()->user()->role;
@endphp

@if (in_array($role, ['admin', 'owner'], true))
    <x-layouts.admin :title="$title" :heading="$heading" :surface="$role === 'owner' ? 'cream' : 'paper'">
        @include('profile.form')
    </x-layouts.admin>
@elseif ($role === 'kasir')
    <x-layouts.kasir :title="$title">
        @include('profile.form')
    </x-layouts.kasir>
@else
    <x-layouts.app :title="$title" heading="Profil">
        @include('profile.form')
    </x-layouts.app>
@endif
