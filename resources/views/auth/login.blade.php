<x-layouts.auth title="Masuk" panel="login">
    <h1 class="auth-title">Selamat datang kembali</h1>
    <p class="auth-subtext">Masuk ke akun CafeSync Anda</p>

    <form action="{{ route('login.store') }}" method="POST" class="auth-form">
        @csrf

        <div class="auth-field">
            <label for="identifier">Email</label>
            <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}"
                autocomplete="username" placeholder="email@contoh.com" required>
            @error('identifier')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <div class="auth-input-wrap">
                <input id="password" name="password" type="password" autocomplete="current-password" required>
                <button type="button" class="auth-eye" data-password-toggle aria-controls="password"
                    aria-label="Tampilkan kata sandi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="auth-row">
            <label class="auth-remember">
                <input type="checkbox" name="remember" value="1">
                Ingat perangkat ini
            </label>
            <a href="#" class="auth-text-link">Lupa password?</a>
        </div>

        <button type="submit" class="auth-submit">Masuk</button>
    </form>

    <p class="auth-help">
        Belum punya akun?
        <a href="{{ route('register') }}" class="auth-text-link">Daftar sekarang</a>
    </p>
</x-layouts.auth>
