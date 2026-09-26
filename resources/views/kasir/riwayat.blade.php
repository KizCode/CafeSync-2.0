<x-layouts.kasir title="Riwayat transaksi | CafeSync">
    <header class="kasir-topbar">
        <div>
            <p class="kasir-kicker">Riwayat Transaksi</p>
            <h1>Semua transaksi akun ini</h1>
        </div>
    </header>

    <section class="kasir-panel">
        <table class="kasir-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pelanggan</th>
                    <th>Metode</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->invoice_number }}</td>
                        <td>{{ $transaction->customer_name ?: 'Umum' }}</td>
                        <td>{{ strtoupper($transaction->payment_method) }}</td>
                        <td>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                        <td><a href="{{ route('kasir.show', $transaction) }}">Detail</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada riwayat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $transactions->links() }}
    </section>
</x-layouts.kasir>
