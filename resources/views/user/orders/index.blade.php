@extends('layouts.app')

@section('content')
    <h1>Pesanan Saya</h1>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Pesanan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Estimasi Tiba</th>
                    <th>Resi</th>
                    <th>Item</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span>
                            @if($order->status === 'dibatalkan' && $order->cancel_reason)
                                <div class="muted" style="margin-top: 6px;">Alasan: {{ $order->cancel_reason }}</div>
                            @endif
                        </td>
                        <td>{{ $order->shippingEstimateLabel() }}</td>
                        <td>
                            @if($order->tracking_number)
                                {{ $order->tracking_number }}
                            @else
                                <span class="muted">Menunggu proses ekspedisi</span>
                            @endif
                        </td>
                        <td>
                            @foreach($order->items as $item)
                                <div class="muted">
                                    {{ $item->product?->name ?? 'Produk terhapus' }} ({{ $item->quantity }}x)
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <div class="order-action-stack">
                                <a class="btn-primary" href="{{ route('orders.show', $order) }}">Detail</a>
                            @if(in_array($order->status, ['menunggu_pembayaran', 'menunggu_verifikasi'], true))
                                    <details class="cancel-order-details">
                                        <summary class="cancel-order-summary">Batalkan</summary>
                                        <form class="cancel-order-panel" action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <label for="cancel-reason-{{ $order->id }}">Alasan pembatalan</label>
                                            <select id="cancel-reason-{{ $order->id }}" name="cancel_reason" required>
                                                <option value="">Pilih alasan</option>
                                                <option value="Ingin mengubah produk">Ingin mengubah produk</option>
                                                <option value="Ingin mengubah alamat">Ingin mengubah alamat</option>
                                                <option value="Belum jadi melakukan pembayaran">Belum jadi melakukan pembayaran</option>
                                                <option value="Salah memilih varian atau jumlah produk">Salah memilih varian atau jumlah produk</option>
                                                <option value="Alasan lainnya">Alasan lainnya</option>
                                            </select>
                                            <button class="btn-danger" type="submit">Konfirmasi Batal</button>
                                        </form>
                                    </details>
                            @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="muted">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 14px;">{{ $orders->links() }}</div>
    </div>
@endsection
