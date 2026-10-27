@extends('admin.layouts.app')

@section('content')
    <style>
        .order-admin-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 14px;
            align-items: start;
        }

        .shipping-label {
            border: 2px dashed var(--border);
            background: #fff;
        }

        .print-only {
            display: none;
        }

        .print-order-header {
            display: none;
        }

        .shipping-label h2,
        .shipping-label h3 {
            font-family: "Nunito", sans-serif;
            font-weight: 800;
            letter-spacing: 0;
        }

        .shipping-label-row {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 10px;
            padding: 7px 0;
            border-bottom: 1px solid #f1ddbf;
        }

        .shipping-label-row:last-child {
            border-bottom: none;
        }

        .admin-order-note {
            line-height: 1.7;
            margin-bottom: 0;
        }

        @media print {
            @page {
                size: A4;
                margin: 12mm;
            }

            .admin-sidebar,
            .admin-topbar,
            .no-print,
            .flash {
                display: none !important;
            }

            .print-only,
            .print-order-header {
                display: block !important;
            }

            th.print-only,
            td.print-only {
                display: table-cell !important;
            }

            * {
                box-shadow: none !important;
                text-shadow: none !important;
            }

            html,
            body {
                background: #fff !important;
                color: #111 !important;
                font-family: Arial, sans-serif !important;
            }

            .admin-wrapper {
                display: block !important;
                min-height: auto !important;
            }

            .admin-main {
                display: block !important;
                padding: 0 !important;
                width: 100% !important;
            }

            .order-admin-grid {
                display: block !important;
            }

            .shipping-label {
                width: 100% !important;
                border: 1px solid #333 !important;
                border-radius: 0 !important;
                padding: 18px !important;
                background: #fff !important;
                color: #111 !important;
                page-break-inside: avoid;
            }

            .shipping-label h2,
            .shipping-label h3 {
                color: #111 !important;
                font-family: Arial, sans-serif !important;
                margin: 14px 0 8px !important;
            }

            .shipping-label > h2,
            .shipping-label > .muted {
                display: none !important;
            }

            .print-order-header {
                margin-bottom: 14px;
                padding-bottom: 10px;
                border-bottom: 2px solid #111;
            }

            .print-order-header h1 {
                margin: 0 0 4px !important;
                color: #111 !important;
                font-family: Arial, sans-serif !important;
                font-size: 22px !important;
            }

            .print-order-header p {
                margin: 2px 0 !important;
                font-size: 12px !important;
                color: #333 !important;
            }

            .shipping-label-row {
                grid-template-columns: 125px 1fr !important;
                border-bottom: 1px solid #bbb !important;
                padding: 8px 0 !important;
                font-size: 13px !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 12px !important;
                margin-top: 6px !important;
            }

            th,
            td {
                border: 1px solid #999 !important;
                padding: 8px !important;
                color: #111 !important;
            }

            th {
                background: #f2f2f2 !important;
                text-transform: none !important;
                letter-spacing: 0 !important;
            }

            .print-total-row th,
            .print-total-row td {
                font-weight: 700 !important;
                background: #f7f7f7 !important;
            }
        }

        @media (max-width: 860px) {
            .order-admin-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="actions no-print" style="justify-content: space-between; margin-bottom: 14px;">
        <h1 style="margin: 0;">Detail Pesanan #{{ $order->id }}</h1>
        <div class="actions">
            <button class="btn-primary" type="button" onclick="window.print()">Cetak Detail Pesanan</button>
            <a class="btn-primary" href="{{ route('admin.orders.index') }}">Kembali</a>
        </div>
    </div>

    @if($order->status === 'dibatalkan')
        <div class="card no-print" style="margin-bottom: 14px;">
            <h3 style="margin: 0 0 8px;">Pesanan Dibatalkan</h3>
            <p class="muted" style="margin: 0;">
                {{ $order->canceled_at ? 'Dibatalkan pada '.$order->canceled_at->format('d M Y H:i') : 'Pesanan sudah dibatalkan.' }}
                @if($order->cancel_reason)
                    <br>Alasan: {{ $order->cancel_reason }}
                @endif
            </p>
        </div>
    @endif

    <div class="order-admin-grid">
        <section class="card shipping-label">
            <div class="print-order-header">
                <h1>Detail Pesanan Sejucara</h1>
                <p>ID Pesanan: #{{ $order->id }}</p>
                <p>Tanggal cetak: {{ now()->translatedFormat('d F Y H:i') }}</p>
            </div>

            <h2 style="margin-top:0;">Label / Checklist Pengiriman</h2>
            <p class="muted">Tempel/catat ID pesanan ini pada paket agar tidak tertukar saat menerima resi dari ekspedisi.</p>

            <div class="shipping-label-row">
                <strong>ID Pesanan</strong>
                <span>#{{ $order->id }}</span>
            </div>
            <div class="shipping-label-row">
                <strong>Penerima</strong>
                <span>{{ $order->customer_name }}</span>
            </div>
            <div class="shipping-label-row">
                <strong>No. HP</strong>
                <span>{{ $order->customer_phone }}</span>
            </div>
            <div class="shipping-label-row">
                <strong>Alamat</strong>
                <span>{{ $order->shipping_address }}</span>
            </div>
            @if($order->shipping_destination_label)
                <div class="shipping-label-row">
                    <strong>Tujuan Ongkir</strong>
                    <span>{{ $order->shipping_destination_label }}</span>
                </div>
            @endif
            <div class="shipping-label-row">
                <strong>Kurir</strong>
                <span>{{ strtoupper(trim(($order->courier ?? '-').' '.($order->shipping_service ?? ''))) }}</span>
            </div>
            <div class="shipping-label-row">
                <strong>No. Resi</strong>
                <span>{{ $order->tracking_number ?: 'Diisi setelah paket diproses ekspedisi' }}</span>
            </div>

            <h3>Item Paket</h3>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th class="print-only">Harga</th>
                        <th class="print-only">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product?->name ?? 'Produk terhapus' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="print-only">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="print-only">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="print-only">
                <h3>Ringkasan Pembayaran</h3>
                <table>
                    <tbody>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>{{ strtoupper($order->payment_method ?? 'qris') }}</td>
                        </tr>
                        <tr>
                            <th>Subtotal Produk</th>
                            <td>Rp {{ number_format($order->subtotal_amount ?? ($order->total_amount - ($order->shipping_cost ?? 0)), 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Ongkir</th>
                            <td>Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="print-total-row">
                            <th>Total Pembayaran</th>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card no-print">
            <h3 style="margin-top:0;">Update Status & Resi</h3>
            <p class="muted admin-order-note">
                Nomor resi tidak dibuat oleh sistem. Resi resmi diperoleh setelah paket diproses oleh ekspedisi,
                lalu admin memasukkannya ke pesanan yang sesuai.
            </p>

            <form action="{{ route('admin.orders.status.update', $order) }}" method="POST" style="margin-top: 14px;">
                @csrf
                @method('PATCH')
                <div class="field">
                    <label for="status">Status Pesanan</label>
                    <select id="status" name="status" required>
                        <option value="menunggu_pembayaran" @selected($order->status === 'menunggu_pembayaran')>Menunggu Pembayaran</option>
                        <option value="menunggu_verifikasi" @selected($order->status === 'menunggu_verifikasi')>Menunggu Verifikasi</option>
                        <option value="diproses" @selected($order->status === 'diproses')>Diproses</option>
                        <option value="dikirim" @selected($order->status === 'dikirim')>Dikirim</option>
                        <option value="selesai" @selected($order->status === 'selesai')>Selesai</option>
                        <option value="dibatalkan" @selected($order->status === 'dibatalkan')>Dibatalkan</option>
                    </select>
                </div>
                <div class="field">
                    <label for="tracking_number">No. Resi Resmi Ekspedisi</label>
                    <input
                        id="tracking_number"
                        type="text"
                        name="tracking_number"
                        value="{{ old('tracking_number', $order->tracking_number) }}"
                        placeholder="Contoh: JNT1234567890"
                    >
                    <p class="muted" style="margin: 6px 0 0;">Wajib diisi jika status diubah menjadi Dikirim.</p>
                </div>
                <button class="btn-primary" type="submit">Simpan Perubahan</button>
            </form>
        </section>
    </div>

    <section class="card no-print" style="margin-top: 14px;">
        <h3 style="margin-top:0;">Ringkasan Pembayaran</h3>
        <p class="muted">Metode: {{ strtoupper($order->payment_method ?? 'qris') }}</p>
        @if($order->payment_proof_path)
            <p><a href="{{ route('admin.orders.payment-proof', $order) }}" target="_blank" rel="noopener">Lihat bukti pembayaran</a></p>
        @endif
        <table>
            <tbody>
                <tr>
                    <th>Subtotal Produk</th>
                    <td>Rp {{ number_format($order->subtotal_amount ?? ($order->total_amount - ($order->shipping_cost ?? 0)), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Ongkir</th>
                    <td>Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </section>
@endsection
