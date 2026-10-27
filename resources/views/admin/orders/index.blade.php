@extends('admin.layouts.app')

@section('content')
    <style>
        .orders-page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 18px;
        }

        .orders-page-header h1 {
            margin: 0 0 8px;
        }

        .orders-page-header p {
            max-width: 820px;
            line-height: 1.7;
            margin: 0;
        }

        .orders-list {
            display: grid;
            gap: 14px;
        }

        .order-card {
            padding: 0;
            overflow: hidden;
        }

        .order-card-header {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
            padding: 16px 18px;
            border-bottom: 1px solid var(--admin-border);
            background: #fff6e9;
        }

        .order-id {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .order-id strong {
            font-size: 20px;
            color: #8b3a0f;
        }

        .order-date {
            color: var(--admin-muted);
            font-size: 13px;
        }

        .order-status-badge {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 6px 11px;
            border-radius: 999px;
            background: #ffe3c2;
            color: #8b3a0f;
            font-size: 13px;
            font-weight: 900;
        }

        .order-card-body {
            display: grid;
            grid-template-columns: minmax(240px, 0.9fr) minmax(240px, 1fr) minmax(280px, 0.9fr);
            gap: 16px;
            padding: 18px;
        }

        .order-section-title {
            margin: 0 0 10px;
            color: #5e3419;
            font-size: 13px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .order-info-list {
            display: grid;
            gap: 8px;
        }

        .order-info-row {
            display: grid;
            grid-template-columns: 86px 1fr;
            gap: 10px;
            font-size: 14px;
        }

        .order-info-row span:first-child {
            color: var(--admin-muted);
            font-weight: 800;
        }

        .order-address {
            line-height: 1.55;
        }

        .order-items {
            display: grid;
            gap: 8px;
            margin-bottom: 14px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 9px 10px;
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            background: #fffdf8;
            font-size: 14px;
        }

        .cost-box {
            display: grid;
            gap: 7px;
            padding: 12px;
            border-radius: 12px;
            background: #fff6e9;
        }

        .cost-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: var(--admin-muted);
            font-size: 14px;
        }

        .cost-row.total {
            padding-top: 8px;
            border-top: 1px solid var(--admin-border);
            color: var(--admin-text);
            font-weight: 900;
        }

        .order-form {
            display: grid;
            gap: 10px;
        }

        .order-form .actions {
            margin-top: 2px;
        }

        .order-help {
            margin: 0;
            color: var(--admin-muted);
            font-size: 13px;
            line-height: 1.55;
        }

        .order-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            padding: 14px 18px;
            border-top: 1px solid var(--admin-border);
            background: #fffdf8;
        }

        .empty-orders {
            padding: 20px;
            text-align: center;
        }

        .admin-orders-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 16px;
            padding: 14px 16px;
            border: 1px solid var(--admin-border);
            border-radius: 16px;
            background: var(--admin-surface);
        }

        .admin-orders-pagination-info {
            color: var(--admin-muted);
            font-size: 14px;
        }

        .admin-orders-pagination-links {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .admin-orders-page-link,
        .admin-orders-page-disabled,
        .admin-orders-page-current {
            min-width: 38px;
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 12px;
            border: 1px solid var(--admin-border);
            text-decoration: none;
            font-weight: 900;
        }

        .admin-orders-page-link {
            background: #fffdf8;
            color: var(--admin-text);
        }

        .admin-orders-page-link:hover {
            border-color: var(--admin-primary);
            color: var(--admin-primary-dark);
        }

        .admin-orders-page-current {
            background: var(--admin-primary);
            border-color: var(--admin-primary);
            color: #fff;
        }

        .admin-orders-page-disabled {
            background: #f7eadc;
            color: var(--admin-muted);
            opacity: 0.5;
        }

        @media (max-width: 1150px) {
            .order-card-body {
                grid-template-columns: 1fr 1fr;
            }

            .order-card-body > section:last-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 760px) {
            .orders-page-header,
            .order-card-header,
            .order-card-footer,
            .admin-orders-pagination {
                align-items: flex-start;
                flex-direction: column;
            }

            .order-card-body {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="orders-page-header">
        <div>
            <h1>Daftar Pesanan Masuk</h1>
            <p class="muted">
                Alur resi: admin memverifikasi pembayaran, menyiapkan paket berdasarkan ID pesanan,
                mengirim paket ke ekspedisi, lalu memasukkan nomor resi resmi dari ekspedisi ke sistem.
            </p>
        </div>
        <a class="btn-primary" href="{{ route('admin.dashboard') }}">Dashboard</a>
    </div>

    <div class="orders-list">
        @forelse($orders as $order)
            <article class="card order-card">
                <header class="order-card-header">
                    <div class="order-id">
                        <strong>#{{ $order->id }}</strong>
                        <span class="order-status-badge">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span>
                        @if($order->status === 'dibatalkan' && $order->cancel_reason)
                            <span class="order-date">Alasan: {{ $order->cancel_reason }}</span>
                        @endif
                        <span class="order-date">{{ $order->created_at->timezone(config('app.timezone'))->format('d M Y H:i') }} WIB</span>
                    </div>
                    <div class="actions">
                        <a class="btn-primary" href="{{ route('admin.orders.show', $order) }}">Detail Pesanan</a>
                        @if($order->payment_proof_path)
                            <a class="btn-primary" href="{{ route('admin.orders.payment-proof', $order) }}" target="_blank" rel="noopener">Bukti Bayar</a>
                        @endif
                    </div>
                </header>

                <div class="order-card-body">
                    <section>
                        <h3 class="order-section-title">Data Penerima</h3>
                        <div class="order-info-list">
                            <div class="order-info-row">
                                <span>Nama</span>
                                <strong>{{ $order->customer_name }}</strong>
                            </div>
                            <div class="order-info-row">
                                <span>Kontak</span>
                                <span>{{ $order->customer_phone }}</span>
                            </div>
                            <div class="order-info-row">
                                <span>Alamat</span>
                                <span class="order-address">{{ $order->shipping_address }}</span>
                            </div>
                            @if($order->shipping_destination_label)
                                <div class="order-info-row">
                                    <span>Tujuan</span>
                                    <span class="order-address">{{ $order->shipping_destination_label }}</span>
                                </div>
                            @endif
                            <div class="order-info-row">
                                <span>Kurir</span>
                                <span>{{ strtoupper(trim(($order->courier ?? '-').' '.($order->shipping_service ?? ''))) }}</span>
                            </div>
                            <div class="order-info-row">
                                <span>Estimasi</span>
                                <span>{{ $order->shippingEstimateLabel() }}</span>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h3 class="order-section-title">Item & Pembayaran</h3>
                        <div class="order-items">
                            @foreach($order->items as $item)
                                <div class="order-item">
                                    <span>{{ $item->product?->name ?? 'Produk terhapus' }}</span>
                                    <strong>{{ $item->quantity }}x</strong>
                                </div>
                            @endforeach
                        </div>

                        <div class="cost-box">
                            <div class="cost-row">
                                <span>Metode</span>
                                <strong>{{ strtoupper($order->payment_method ?? 'qris') }}</strong>
                            </div>
                            <div class="cost-row">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($order->subtotal_amount ?? ($order->total_amount - ($order->shipping_cost ?? 0)), 0, ',', '.') }}</span>
                            </div>
                            <div class="cost-row">
                                <span>Ongkir</span>
                                <span>Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="cost-row total">
                                <span>Total</span>
                                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h3 class="order-section-title">Update Status & Resi</h3>
                        <form class="order-form" action="{{ route('admin.orders.status.update', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="field">
                                <label for="status-{{ $order->id }}">Status Pesanan</label>
                                <select id="status-{{ $order->id }}" name="status">
                                    <option value="menunggu_pembayaran" @selected($order->status === 'menunggu_pembayaran')>Menunggu Pembayaran</option>
                                    <option value="menunggu_verifikasi" @selected($order->status === 'menunggu_verifikasi')>Menunggu Verifikasi</option>
                                    <option value="diproses" @selected($order->status === 'diproses')>Diproses</option>
                                    <option value="dikirim" @selected($order->status === 'dikirim')>Dikirim</option>
                                    <option value="selesai" @selected($order->status === 'selesai')>Selesai</option>
                                    <option value="dibatalkan" @selected($order->status === 'dibatalkan')>Dibatalkan</option>
                                </select>
                            </div>
                            <div class="field">
                                <label for="tracking-{{ $order->id }}">No. Resi Resmi Ekspedisi</label>
                                <input
                                    id="tracking-{{ $order->id }}"
                                    type="text"
                                    name="tracking_number"
                                    value="{{ old('tracking_number', $order->tracking_number) }}"
                                    placeholder="Contoh: JNT1234567890"
                                >
                            </div>
                            <div class="actions">
                                <button class="btn-primary" type="submit">Simpan Perubahan</button>
                            </div>
                            <p class="order-help">
                                No. resi wajib diisi saat status menjadi Dikirim. Resi diambil dari bukti resmi ekspedisi.
                            </p>
                        </form>
                    </section>
                </div>

                <footer class="order-card-footer">
                    <span class="muted">
                        Cocokkan ID pesanan #{{ $order->id }} dengan paket sebelum input resi.
                    </span>
                    <span class="muted">
                        Resi: {{ $order->tracking_number ?: 'Belum diisi, input setelah paket diproses ekspedisi' }}
                    </span>
                </footer>
            </article>
        @empty
            <div class="card empty-orders">
                <p class="muted" style="margin:0;">Belum ada pesanan masuk.</p>
            </div>
        @endforelse

        @if($orders->hasPages())
            <nav class="admin-orders-pagination" aria-label="Navigasi halaman pesanan">
                <div class="admin-orders-pagination-info">
                    Menampilkan {{ $orders->firstItem() }}-{{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
                </div>
                <div class="admin-orders-pagination-links">
                    @if($orders->onFirstPage())
                        <span class="admin-orders-page-disabled">Sebelumnya</span>
                    @else
                        <a class="admin-orders-page-link" href="{{ $orders->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                    @endif

                    @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        @if($page === $orders->currentPage())
                            <span class="admin-orders-page-current">{{ $page }}</span>
                        @else
                            <a class="admin-orders-page-link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($orders->hasMorePages())
                        <a class="admin-orders-page-link" href="{{ $orders->nextPageUrl() }}" rel="next">Berikutnya</a>
                    @else
                        <span class="admin-orders-page-disabled">Berikutnya</span>
                    @endif
                </div>
            </nav>
        @endif
    </div>
@endsection
