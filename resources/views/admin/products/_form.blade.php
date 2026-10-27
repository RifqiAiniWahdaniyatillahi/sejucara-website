@csrf
<div class="field">
    <label for="name">Nama Produk</label>
    <input id="name" type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required>
</div>

<div class="field">
    <label for="description">Deskripsi</label>
    <textarea id="description" name="description" rows="4" required>{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="field">
    <label for="price">Harga</label>
    <input id="price" type="number" min="0" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required>
</div>

<div class="field">
    <label for="stock">Stok</label>
    <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required>
</div>

<div class="field">
    <label for="image">Upload Gambar Produk</label>
    <input id="image" type="file" name="image" accept="image/png,image/jpeg,image/webp">
    <p class="muted" style="margin: 6px 0 0;">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</p>

    @isset($product)
        @if($product->image_url)
            <div style="margin-top: 10px;">
                <p class="muted" style="margin: 0 0 6px;">Gambar saat ini:</p>
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 160px; height: 120px; object-fit: cover; border-radius: 10px; border: 1px solid var(--admin-border);">
            </div>
        @endif
    @endisset
</div>

<button class="btn-primary" type="submit">{{ $submitLabel }}</button>
