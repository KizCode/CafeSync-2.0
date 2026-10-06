<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk {{ $transaction->invoice_number }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="kasir-receipt-page">
    <article class="kasir-receipt">
        <header>
            <span class="kasir-receipt-mark">C</span>
            <strong>CafeSync</strong>
            <p>{{ $transaction->invoice_number }}</p>
            <p>{{ $transaction->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</p>
            <p>Kasir: {{ $transaction->cashier?->name ?? '—' }}</p>
        </header>
        <ul>
            @foreach ($transaction->items as $item)
                <li>
                    <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                    <span>{{ number_format($item->total_price, 0, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>
        <p><span>Total</span><strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></p>
        <p><span>Dibayar</span><span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span></p>
        <p><span>Kembali</span><span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span></p>
        <p class="kasir-receipt-thanks">Terima kasih</p>
    </article>
    <div class="kasir-receipt-actions">
        <button type="button" class="kasir-btn" onclick="window.print()">Cetak</button>
        <a class="kasir-btn-ghost" href="{{ route('kasir.show', $transaction) }}">Kembali</a>
    </div>
</body>

</html>
