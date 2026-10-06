<x-layouts.kasir title="Pembayaran | CafeSync">
    <header class="kasir-topbar">
        <div>
            <p class="kasir-kicker">Pembayaran</p>
            <h1>Konfirmasi pesanan</h1>
        </div>
        <a class="kasir-btn-ghost" href="{{ route('kasir.index') }}">Kembali ke POS</a>
    </header>

    <div class="kasir-pay kasir-pay-stage">
        <section class="kasir-panel">
            <h2>Ringkasan pesanan</h2>
            <p class="kasir-muted">Pelanggan: {{ $cart['customer_name'] ?: 'Umum' }}</p>
            <ul class="kasir-order-list">
                @foreach ($lines as $line)
                    <li>
                        <div>
                            <strong>{{ $line['product']->name }}</strong>
                            <small>x{{ $line['quantity'] }}</small>
                        </div>
                        <span>Rp {{ number_format($line['line_total'], 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="kasir-cart-total">
                <span>Total tagihan</span>
                <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
            </div>
        </section>

        <form class="kasir-panel kasir-pay-form" action="{{ route('kasir.store') }}" method="POST">
            @csrf
            <input type="hidden" name="customer_name" value="{{ $cart['customer_name'] }}">
            @foreach ($cart['items'] as $index => $item)
                <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item['product_id'] }}">
                <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
            @endforeach

            <h2>Metode pembayaran</h2>
            <div class="kasir-methods">
                @foreach (['tunai' => 'Tunai', 'qris' => 'QRIS', 'transfer' => 'Transfer'] as $value => $label)
                    <label>
                        <input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method', 'tunai') === $value)
                            required>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <label class="kasir-field">
                Jumlah dibayar
                <input id="paid_amount" name="paid_amount" type="number" min="{{ $subtotal }}" step="0.01"
                    value="{{ old('paid_amount', $subtotal) }}" data-pay-total="{{ $subtotal }}" required>
            </label>

            <div class="kasir-quick-pay">
                <button type="button" data-pay-amount="{{ $subtotal }}">Uang pas</button>
                <button type="button" data-pay-amount="50000">Rp 50.000</button>
                <button type="button" data-pay-amount="100000">Rp 100.000</button>
            </div>

            <p class="kasir-change">Kembalian <strong data-pay-change>Rp 0</strong></p>
            <button class="kasir-btn" type="submit">Konfirmasi pembayaran</button>
        </form>
    </div>
</x-layouts.kasir>
