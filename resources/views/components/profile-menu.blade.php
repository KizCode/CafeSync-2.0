@props([
    'home',
    'initials',
])

<details class="mz-dropdown">
    <summary class="mz-icon-btn mz-avatar-btn" aria-label="Menu akun">
        <span class="kasir-avatar kasir-avatar-sm">{{ $initials }}</span>
    </summary>
    <div class="mz-dropdown-panel">
        <p class="mz-dropdown-name">{{ auth()->user()->name }}</p>
        <p class="mz-dropdown-role">{{ ucfirst(auth()->user()->role) }}</p>
        <a href="{{ $home }}">Dashboard</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Keluar</button>
        </form>
    </div>
</details>
