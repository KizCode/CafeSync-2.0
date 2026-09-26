@props([
    'home',
    'role',
])

<aside {{ $attributes->class('app-sidebar mz-sidebar') }} id="app-sidebar" data-sidebar>
    <a class="mz-brand" href="{{ $home }}">
        <img src="{{ asset('images/cafesync-mark.svg') }}" width="34" height="26" alt="">
        <span>
            <strong>CafeSync</strong>
            <small>{{ $role }}</small>
        </span>
    </a>

    {{ $slot }}
</aside>
