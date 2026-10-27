@extends('admin.layouts.app')

@section('content')
    <div class="actions" style="justify-content: space-between; margin-bottom: 12px;">
        <h1 style="margin:0;">Kelola Produk</h1>
        <a class="btn-primary" href="{{ route('admin.products.create') }}">Tambah Produk</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 72px; height: 54px; object-fit: cover; border-radius: 8px; border: 1px solid var(--admin-border);">
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <div class="actions">
                                <a class="btn-primary" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 14px;">
            {{ $products->links() }}
        </div>
    </div>
@endsection
