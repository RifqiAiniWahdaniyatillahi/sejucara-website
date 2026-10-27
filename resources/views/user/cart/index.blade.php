@extends('layouts.app')

@section('content')
    <h1>Keranjang Belanja</h1>

    @if(count($items) === 0)
        <div class="card">
            <p class="muted">Keranjang masih kosong. Yuk pilih produk cascara terlebih dahulu.</p>
            <a class="btn-primary" href="{{ route('products.index') }}">Lihat Produk</a>
        </div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item['product']->name }}</td>
                            <td>Rp {{ number_format($item['product']->price, 0, ',', '.') }}</td>
                            <td>
                                <form class="actions" action="{{ route('cart.update', $item['product']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" min="1" max="{{ $item['product']->stock }}" value="{{ $item['quantity'] }}" style="width: 80px;">
                                    <button class="btn-primary" type="submit">Update</button>
                                </form>
                            </td>
                            <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="actions" style="justify-content: space-between; margin-top: 16px;">
                <strong>Total: Rp {{ number_format($total, 0, ',', '.') }}</strong>
                <a class="btn-primary" href="{{ route('checkout.create') }}">Lanjut Checkout</a>
            </div>
        </div>
    @endif
@endsection
