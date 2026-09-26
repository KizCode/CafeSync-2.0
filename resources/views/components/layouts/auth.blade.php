@props([
    'title',
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
    <div class="auth-gradient" aria-hidden="true"></div>

    <main class="auth-wrap">
        <section class="auth-card">
            <a href="{{ route('login') }}" class="auth-logo">
                <img src="{{ asset('images/cafesync-mark.svg') }}" width="40" height="30" alt="">
                <strong>CafeSync</strong>
            </a>

            {{ $slot }}
        </section>
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
