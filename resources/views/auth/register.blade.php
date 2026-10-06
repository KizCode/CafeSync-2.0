<x-layouts.auth title="Daftar" panel="register">
    <h1 class="auth-title">Buat akun baru</h1>
    <p class="auth-subtext">Daftar sebagai customer CafeSync</p>

    <ol class="auth-steps">
        <li class="{{ $step === 1 ? 'is-active' : 'is-done' }}">
            <span>1</span>
            Data Pribadi
        </li>
        <li class="{{ $step === 2 ? 'is-active' : '' }}">
            <span>2</span>
            Keamanan Akun
        </li>
    </ol>

    @if ($step === 1)
        <form action="{{ route('register.details') }}" method="POST" class="auth-form">
            @csrf

            <div class="auth-field">
                <label for="name">Nama Lengkap <em>*</em></label>
                <input id="name" name="name" type="text" value="{{ old('name', $details['name'] ?? '') }}"
                    autocomplete="name" placeholder="cth. Budi Santoso" required>
                @error('name')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <label for="email">Email <em>*</em></label>
                <input id="email" name="email" type="email" value="{{ old('email', $details['email'] ?? '') }}"
                    autocomplete="email" placeholder="email@contoh.com" required>
                @error('email')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <label for="phone">Nomor Telepon <em>*</em></label>
                <div class="auth-phone">
                    <span>+62</span>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $details['phone'] ?? '') }}"
                        autocomplete="tel" placeholder="812-xxxx-xxxx" required>
                </div>
                @error('phone')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth-submit">Lanjut →</button>
        </form>
    @else
        <form action="{{ route('register.store') }}" method="POST" class="auth-form">
            @csrf

            <div class="auth-field">
                <label for="password">Password <em>*</em></label>
                <div class="auth-input-wrap">
                    <input id="password" name="password" type="password" autocomplete="new-password" required>
                    <button type="button" class="auth-eye" data-password-toggle aria-controls="password"
                        aria-label="Tampilkan kata sandi">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <label for="password_confirmation">Konfirmasi Password <em>*</em></label>
                <div class="auth-input-wrap">
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        autocomplete="new-password" required>
                    <button type="button" class="auth-eye" data-password-toggle
                        aria-controls="password_confirmation" aria-label="Tampilkan kata sandi">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>

            <label class="auth-remember auth-terms">
                <input type="checkbox" name="terms" value="1" @checked(old('terms')) required>
                <span>Saya menyetujui Syarat &amp; Ketentuan serta Kebijakan Privasi CafeSync</span>
            </label>
            @error('terms')
                <p class="auth-error">{{ $message }}</p>
            @enderror

            <div class="auth-actions">
                <a href="{{ route('register') }}" class="auth-back">Kembali</a>
                <button type="submit" class="auth-submit">Daftar</button>
            </div>
        </form>
    @endif

    <p class="auth-help">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="auth-text-link">Masuk di sini</a>
    </p>
</x-layouts.auth>
