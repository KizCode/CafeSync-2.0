<div class="field">
    <label for="name">Nama produk</label><input id="name" name="name"
        value="{{ old('name', $product->name ?? '') }}" required>
</div>
<div class="field"><label for="category_id">Kategori</label><select id="category_id" name="category_id" required>
        <option value="">Pilih kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select></div>
<div class="field"><label for="price">Harga</label><input id="price" name="price" type="number"
        min="0" step="0.01" value="{{ old('price', $product->price ?? '') }}" required></div>
<div class="field"><label for="stock">Stok</label><input id="stock" name="stock" type="number" min="0"
        value="{{ old('stock', $product->stock ?? 0) }}" required></div>
<div class="field"><label for="description">Deskripsi</label>
    <textarea id="description" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
</div>
<div class="field"><label for="image">Foto produk</label><input id="image" name="image" type="file"
        accept="image/*"></div>
