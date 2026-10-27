@extends('layouts.app')

@section('content')
    <div class="actions" style="justify-content: space-between; margin-bottom: 14px;">
        <h1 style="margin: 0;">Detail Pesanan #{{ $order->id }}</h1>
        <div class="actions">
            <a class="btn-primary" href="{{ route('orders.index') }}">Kembali</a>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 14px;">
        <div class="card">
            <h3 style="margin-top:0;">Status Pesanan</h3>
            <p><span class="badge">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span></p>
            <p class="muted" style="margin-bottom: 0;">Dibuat: {{ $order->created_at->format('d M Y H:i') }}</p>
            @if($order->status === 'dibatalkan')
                <p class="cancel-reason-note">
                    Pesanan dibatalkan{{ $order->canceled_at ? ' pada '.$order->canceled_at->format('d M Y H:i') : '' }}.
                    @if($order->cancel_reason)
                        <br>Alasan: {{ $order->cancel_reason }}
                    @endif
                </p>
            @endif
            <p class="muted" style="margin-bottom: 0;">Metode pembayaran: {{ strtoupper($order->payment_method ?? 'qris') }}</p>
            <p class="muted" style="margin-bottom: 0;">Kurir: {{ strtoupper(trim(($order->courier ?? '-').' '.($order->shipping_service ?? ''))) }}</p>
            <p class="muted" style="margin-bottom: 0;">Estimasi tiba: {{ $order->shippingEstimateLabel() }}</p>
            @if($order->tracking_number)
                <p class="muted" style="margin-bottom: 0;">No. Resi: {{ $order->tracking_number }}</p>
            @else
                <p class="muted" style="margin-bottom: 0;">No. Resi: menunggu pembayaran diverifikasi dan paket diproses oleh admin bersama pihak ekspedisi.</p>
            @endif

            @if(in_array($order->status, ['menunggu_pembayaran', 'menunggu_verifikasi'], true))
                <details class="cancel-order-details" style="margin-top: 14px;">
                    <summary class="cancel-order-summary">Batalkan Pesanan</summary>
                    <form class="cancel-order-panel" action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?');">
                        @csrf
                        @method('PATCH')
                        <label for="cancel-reason-detail-{{ $order->id }}">Alasan pembatalan</label>
                        <select id="cancel-reason-detail-{{ $order->id }}" name="cancel_reason" required>
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

        <div class="card">
            <h3 style="margin-top:0;">Alamat Pengiriman</h3>
            <p style="margin-bottom: 6px;"><strong>{{ $order->customer_name }}</strong></p>
            <p class="muted" style="margin: 0 0 6px;">{{ $order->customer_phone }}</p>
            <p class="muted" style="margin: 0;">{{ $order->shipping_address }}</p>
            @if($order->shipping_destination_label)
                <p class="muted" style="margin: 8px 0 0;">Tujuan ongkir: {{ $order->shipping_destination_label }}</p>
            @endif
        </div>

        <div class="card">
            <h3 style="margin-top:0;">Ringkasan Biaya</h3>
            <p class="muted" style="margin-bottom: 6px;">Subtotal: Rp {{ number_format($order->subtotal_amount ?? ($order->total_amount - ($order->shipping_cost ?? 0)), 0, ',', '.') }}</p>
            <p class="muted" style="margin-bottom: 6px;">Ongkir: Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</p>
            <p style="margin: 0;"><strong>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></p>
        </div>
    </div>

    <div class="card" style="margin-top: 14px;">
        <h3 style="margin-top:0;">Item Pesanan</h3>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product?->name ?? 'Produk terhapus' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
