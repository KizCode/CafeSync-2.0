<x-layouts.kasir title="Ubah pembayaran | CafeSync">
    <header class="kasir-topbar">
        <div>
            <p class="kasir-kicker">Pembayaran</p>
            <h1>Ubah pembayaran</h1>
        </div>
        <a class="kasir-btn-ghost" href="{{ route('kasir.show', $transaction) }}">Kembali</a>
    </header>

    <form class="kasir-panel kasir-pay-form" action="{{ route('kasir.update', $transaction) }}" method="POST">
        @csrf
        @method('PUT')
        <label class="kasir-field">
            Nama pelanggan
            <input id="customer_name" name="customer_name"
                value="{{ old('customer_name', $transaction->customer_name) }}" maxlength="50">
        </label>
        <div class="kasir-methods">
            @foreach (['tunai' => 'Tunai', 'qris' => 'QRIS', 'transfer' => 'Transfer'] as $value => $label)
                <label>
                    <input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method', $transaction->payment_method) === $value)
                        required>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <label class="kasir-field">
            Jumlah dibayar
            <input id="paid_amount" name="paid_amount" type="number" min="0" step="0.01"
                value="{{ old('paid_amount', $transaction->paid_amount) }}" required>
        </label>
        <button class="kasir-btn" type="submit">Simpan perubahan</button>
    </form>
</x-layouts.kasir>
