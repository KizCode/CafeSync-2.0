<div class="page-heading">
    <div>
        @if (auth()->user()->role === 'owner')
            <p class="kasir-kicker">Akun</p>
        @endif
        <h1>Profil</h1>
        <p>Perbarui nama, username, email, atau kata sandi akun Anda.</p>
    </div>
</div>

<form class="panel form-grid profile-card" action="{{ route('profile.update') }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="field">
        <label for="name">Nama</label>
        <input id="name" name="name" value="{{ old('name', $user->name) }}" required>
    </div>
    <div class="field">
        <label for="username">Username</label>
        <input id="username" name="username" value="{{ old('username', $user->username) }}" required>
    </div>
    <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
    </div>
    <div class="field">
        <label>Peran</label>
        <p class="muted">{{ ucfirst($user->role === 'gudang' ? 'Gudang' : $user->role) }}</p>
    </div>
    <div class="field">
        <label for="password">Password baru (kosongkan jika tidak diubah)</label>
        <input id="password" name="password" type="password" minlength="8" autocomplete="new-password">
    </div>
    <div class="field">
        <label for="password_confirmation">Konfirmasi password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" minlength="8"
            autocomplete="new-password">
    </div>

    <button class="button-primary" type="submit">Simpan profil</button>
</form>
