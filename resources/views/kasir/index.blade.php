<x-layouts.kasir title="POS | CafeSync">
    <form class="kasir-pos" action="{{ route('kasir.prepare') }}" method="POST">
        @csrf

        <section class="kasir-pos-main">
            <header class="kasir-topbar">
                <div>
                    <p class="kasir-kicker">POS</p>
                    <h1>Pilih menu</h1>
                </div>
                <label class="kasir-search">
                    <span class="sr-only">Cari menu</span>
                    <input type="search" data-menu-search placeholder="Cari menu...">
                </label>
            </header>

            <div class="kasir-filters" role="tablist">
                <button type="button" class="is-active" data-category-filter="">Semua</button>
                @foreach ($categories as $category)
                    <button type="button" data-category-filter="{{ $category->id }}">{{ $category->name }}</button>
                @endforeach
            </div>

            <div class="kasir-menu-grid">
                @foreach ($products as $product)
                    <article class="kasir-menu-card" data-menu-card data-price="{{ $product->price }}"
                        data-name="{{ $product->name }}" data-category="{{ $product->category_id }}">
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                        <div>
                            <h2>{{ $product->name }}</h2>
                            <p>Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <small>Stok {{ $product->stock }}</small>
                        </div>
                        <div class="kasir-qty">
                            <button type="button" data-quantity-button="decrease"
                                aria-label="Kurangi {{ $product->name }}">&minus;</button>
                            <input id="product-{{ $product->id }}" data-quantity-input type="number" min="0"
                                max="{{ $product->stock }}" name="items[{{ $loop->index }}][quantity]" value="0"
                                aria-label="Jumlah {{ $product->name }}">
                            <button type="button" data-quantity-button="increase"
                                aria-label="Tambah {{ $product->name }}">&plus;</button>
                        </div>
                        <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $product->id }}">
                    </article>
                @endforeach
            </div>
        </section>

        <aside class="kasir-cart">
            <h2>Pesanan</h2>
            <label class="kasir-field">
                Nama pelanggan
                <input name="customer_name" value="{{ old('customer_name') }}" maxlength="50" placeholder="Umum">
            </label>
            <p id="selected-count" class="kasir-cart-count">0 item</p>
            <ul class="kasir-cart-lines" data-cart-lines></ul>
            <div class="kasir-cart-total">
                <span>Total</span>
                <strong id="order-total">Rp 0</strong>
            </div>
            <button class="kasir-btn" type="submit">Bayar</button>
        </aside>
    </form>
</x-layouts.kasir>
