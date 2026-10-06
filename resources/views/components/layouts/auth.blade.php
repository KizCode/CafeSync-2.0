@props([
    'title',
    'panel' => 'login',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | CafeSync</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page">
    <aside class="auth-hero" style="background-image: linear-gradient(180deg, rgb(17 24 39 / 28%) 0%, rgb(17 24 39 / 55%) 100%), url('{{ asset('images/auth-hero.jpg') }}');">
        <a href="{{ route('login') }}" class="auth-logo">
            <x-brand-mark />
            <strong>CafeSync</strong>
        </a>

        @if ($panel === 'login')
            <div class="auth-hero-copy">
                <p class="auth-hero-quote">Satu sistem untuk seluruh operasional kafe — dari inventaris hingga laporan
                    keuangan.</p>
                <ul class="auth-role-grid">
                    <li><strong>Admin</strong><span>Kelola akun sistem</span></li>
                    <li><strong>Owner</strong><span>Monitor bisnis</span></li>
                    <li><strong>Warehouse</strong><span>Kelola inventaris</span></li>
                    <li><strong>Cashier</strong><span>Proses transaksi</span></li>
                </ul>
            </div>
        @else
            <div class="auth-hero-copy">
                <h2>Bergabung sekarang.</h2>
                <p>Daftar sebagai customer dan nikmati kemudahan memesan kopi favorit Anda — walk-in, reservasi, atau
                    online.</p>
                <ul class="auth-perk-list">
                    <li><strong>Pesan Online</strong><span>Pesan dari mana saja kapan saja</span></li>
                    <li><strong>Riwayat Pesanan</strong><span>Lihat semua pesanan Anda</span></li>
                    <li><strong>Promo Eksklusif</strong><span>Dapatkan khusus member terdaftar</span></li>
                </ul>
            </div>
        @endif
    </aside>

    <main class="auth-panel">
        <div class="auth-panel-inner">
            {{ $slot }}
        </div>
    </main>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.getAttribute('aria-controls'));

                if (!input) {
                    return;
                }

                const isHidden = input.getAttribute('type') === 'password';
                input.setAttribute('type', isHidden ? 'text' : 'password');
                button.setAttribute('aria-label', isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            });
        });
    </script>
</body>

</html>
