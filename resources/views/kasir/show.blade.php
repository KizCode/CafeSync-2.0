<x-layouts.kasir title="Pembayaran berhasil | CafeSync">
    <div class="kasir-success">
        <section class="kasir-panel kasir-success-card">
            <p class="kasir-success-mark" aria-hidden="true">✓</p>
            <h1>Pembayaran berhasil!</h1>
            <p>{{ $transaction->invoice_number }}</p>
            <p class="kasir-muted">{{ $transaction->customer_name ?: 'Umum' }} ·
                {{ strtoupper($transaction->payment_method) }}</p>

            <ul class="kasir-order-list">
                @foreach ($transaction->items as $item)
                    <li>
                        <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                        <span>Rp {{ number_format($item->total_price, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="kasir-cart-total">
                <span>Total</span>
                <strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong>
            </div>
            <p>Dibayar Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }} · Kembali Rp
                {{ number_format($transaction->change_amount, 0, ',', '.') }}</p>

            <div class="kasir-success-actions">
                <a class="kasir-btn" href="{{ route('kasir.struk', $transaction) }}">Cetak struk</a>
                <a class="kasir-btn-ghost" href="{{ route('kasir.index') }}">Transaksi baru</a>
                <a class="kasir-text-link" href="{{ route('kasir.edit', $transaction) }}">Ubah pembayaran</a>
                <form action="{{ route('kasir.destroy', $transaction) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="kasir-text-danger" type="submit">Batalkan transaksi</button>
                </form>
            </div>
        </section>
    </div>
</x-layouts.kasir>
