@extends('layouts.app')

@section('content')
    @php
        $selectedZone = old('shipping_zone');
        $selectedShippingCost = old('shipping_cost') !== null
            ? (int) old('shipping_cost')
            : ($selectedZone && isset($shippingZones[$selectedZone])
            ? $shippingZones[$selectedZone]['cost']
            : 0);
        $selectedShippingEstimate = $selectedZone && isset($shippingZones[$selectedZone])
            ? $shippingZones[$selectedZone]['estimate']
            : (old('shipping_estimate') ?: 'Pilih tujuan dan cek ongkir');
    @endphp

    <style>
        .checkout-heading {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 14px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .checkout-heading h1 {
            margin: 0;
        }

        .checkout-shell {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 18px;
            align-items: start;
        }

        .checkout-main {
            display: grid;
            gap: 16px;
        }

        .checkout-card {
            padding: 0;
            overflow: hidden;
        }

        .checkout-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            background: #fff7ea;
        }

        .checkout-card-header h2,
        .checkout-card-header h3 {
            margin: 0;
            font-family: "Nunito", "Segoe UI", sans-serif;
            font-size: 18px;
            font-weight: 900;
            color: var(--heading);
        }

        .checkout-card-body {
            padding: 20px;
        }

        .checkout-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .checkout-form-grid .field-full {
            grid-column: 1 / -1;
        }

        .checkout-field-note {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .shipping-search-box {
            display: grid;
            gap: 10px;
        }

        .shipping-search-actions {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: end;
        }

        .shipping-destination-results,
        .shipping-rate-results {
            display: grid;
            gap: 8px;
        }

        .shipping-option {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 11px 12px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fffdf8;
            cursor: pointer;
        }

        .shipping-option:hover {
            border-color: var(--primary);
        }

        .shipping-option input {
            width: auto;
            margin-top: 4px;
        }

        .shipping-option strong {
            display: block;
            color: var(--heading);
        }

        .shipping-option span {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 13px;
        }

        .shipping-status {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .shipping-api-note {
            margin: 12px 0 4px;
            padding: 12px 14px;
            border: 1px dashed #e0a36b;
            border-radius: 12px;
            background: #fff7ea;
            color: #754018;
            font-size: 14px;
            line-height: 1.6;
        }

        .shipping-api-note strong {
            display: block;
            margin-bottom: 4px;
            color: #9c3f09;
        }

        .shipping-manual-fallback {
            display: none;
            margin-top: 12px;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fffdf8;
        }

        .shipping-manual-fallback.is-open {
            display: block;
        }

        .shipping-manual-fallback h3 {
            margin: 0 0 6px;
            font-family: "Nunito", "Segoe UI", sans-serif;
            font-size: 16px;
            color: var(--heading);
        }

        .checkout-select-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .payment-box {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 16px;
            align-items: center;
            border: 1px dashed var(--border);
            border-radius: 14px;
            padding: 16px;
            background: #fff8f0;
        }

        .qris-image-box {
            cursor: zoom-in;
            min-height: 150px;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #ead2ac;
        }

        .qris-image-box img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .upload-box input[type="file"] {
            padding: 12px;
            background: #fffdf8;
        }

        .qris-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .btn-secondary {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #fff;
            color: #5e3419;
            text-decoration: none;
            font-weight: 900;
            cursor: pointer;
            font-family: "Nunito", "Segoe UI", sans-serif;
            font-size: 14px;
        }

        .btn-secondary:hover {
            background: #fff2de;
        }

        .qris-modal {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 22px;
            background: rgba(45, 25, 12, 0.72);
        }

        .qris-modal.is-open {
            display: flex;
        }

        .qris-modal-card {
            width: min(520px, 100%);
            max-height: min(92vh, 760px);
            overflow: auto;
            border-radius: 18px;
            background: var(--surface);
            border: 1px solid var(--border);
            box-shadow: 0 24px 70px rgba(24, 12, 4, 0.35);
        }

        .qris-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            background: #fff7ea;
        }

        .qris-modal-header h3 {
            margin: 0;
            font-family: "Nunito", "Segoe UI", sans-serif;
            font-size: 17px;
            font-weight: 900;
        }

        .qris-close {
            width: 34px;
            height: 34px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: #fff;
            color: var(--text);
            cursor: pointer;
            font-size: 22px;
            line-height: 1;
        }

        .qris-modal-body {
            padding: 16px;
            text-align: center;
        }

        .qris-modal-body img {
            display: block;
            width: min(100%, 440px);
            margin: 0 auto;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: #fff;
        }

        body.qris-modal-open {
            overflow: hidden;
        }

        .checkout-summary {
            position: sticky;
            top: 120px;
        }

        .summary-items {
            display: grid;
            gap: 12px;
        }

        .summary-item {
            display: grid;
            grid-template-columns: 62px 1fr;
            gap: 12px;
            align-items: center;
        }

        .summary-item img {
            width: 62px;
            height: 62px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #fff;
        }

        .summary-item h4 {
            margin: 0 0 4px;
            font-family: "Nunito", "Segoe UI", sans-serif;
            font-size: 14px;
            font-weight: 900;
            color: var(--text);
        }

        .summary-item-meta {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            color: var(--muted);
            font-size: 13px;
        }

        .summary-divider {
            height: 1px;
            background: var(--border);
            margin: 16px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 10px;
            color: var(--muted);
            font-size: 14px;
        }

        .shipping-estimate-box {
            margin-top: 12px;
            padding: 11px 12px;
            border-radius: 12px;
            background: #fff8f0;
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .shipping-estimate-box strong {
            display: block;
            margin-bottom: 3px;
            color: var(--heading);
            font-size: 14px;
        }

        .shipping-note-box {
            margin-top: 10px;
            padding: 11px 12px;
            border-radius: 12px;
            background: #f9f0e4;
            border: 1px solid var(--border);
            color: #5f4c3d;
            font-size: 13px;
            line-height: 1.55;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
            margin-top: 12px;
            padding: 14px;
            border-radius: 14px;
            background: #fff1da;
            color: var(--heading);
            font-weight: 900;
            font-size: 18px;
        }

        .checkout-submit {
            width: 100%;
            text-align: center;
            margin-top: 14px;
        }

        .secure-note {
            margin: 10px 0 0;
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }

        @media (max-width: 960px) {
            .checkout-shell {
                grid-template-columns: 1fr;
            }

            .checkout-summary {
                position: static;
            }
        }

        @media (max-width: 680px) {
            .checkout-form-grid,
            .checkout-select-row,
            .payment-box {
                grid-template-columns: 1fr;
            }

            .checkout-card-header,
            .checkout-card-body {
                padding: 16px;
            }
        }
    </style>

    <div class="checkout-heading">
        <div>
            <h1>Checkout</h1>
            <p class="muted" style="margin:6px 0 0;">Lengkapi alamat, pilih pengiriman, lalu upload bukti pembayaran QRIS.</p>
        </div>
        <a class="btn-primary" href="{{ route('cart.index') }}">Kembali ke Keranjang</a>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="checkout-shell">
            <div class="checkout-main">
                <section class="card checkout-card">
                    <div class="checkout-card-header">
                        <h2>Data Penerima</h2>
                        <span class="badge">Alamat Pengiriman</span>
                    </div>
                    <div class="checkout-card-body">
                        <div class="checkout-form-grid">
                            <div class="field">
                                <label for="customer_name">Nama Penerima</label>
                                <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name', $lastOrder?->customer_name ?? auth()->user()->name) }}" placeholder="Nama lengkap penerima" required>
                            </div>
                            <div class="field">
                                <label for="customer_phone">No. Telepon</label>
                                <input id="customer_phone" type="text" name="customer_phone" value="{{ old('customer_phone', $lastOrder?->customer_phone) }}" placeholder="Contoh: 081234567890" required>
                            </div>
                            <div class="field field-full">
                                <label for="shipping_address">Alamat Lengkap</label>
                                <textarea id="shipping_address" name="shipping_address" rows="4" placeholder="Nama jalan/kampung, nomor rumah, RT/RW, desa/kelurahan, kecamatan, kota/kabupaten, provinsi, kode pos, dan patokan rumah" required>{{ old('shipping_address', $lastOrder?->shipping_address) }}</textarea>
                                <p class="checkout-field-note">
                                    @if($lastOrder)
                                        Data penerima otomatis diisi dari pesanan terakhir. Silakan ubah jika alamat atau nomor telepon berbeda.
                                    @else
                                        Isi alamat sedetail mungkin, termasuk RT/RW, desa/kelurahan, kecamatan, kode pos, dan patokan rumah agar paket mudah ditemukan kurir.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="card checkout-card">
                    <div class="checkout-card-header">
                        <h2>Pengiriman</h2>
                        <span class="badge">Kurir & Ongkir</span>
                    </div>
                    <div class="checkout-card-body">
                        <input id="shipping_zone" type="hidden" name="shipping_zone" value="{{ old('shipping_zone') }}">
                        <input id="shipping_destination_label" type="hidden" name="shipping_destination_label" value="{{ old('shipping_destination_label') }}">
                        <input id="shipping_service" type="hidden" name="shipping_service" value="{{ old('shipping_service') }}">
                        <input id="shipping_estimate" type="hidden" name="shipping_estimate" value="{{ old('shipping_estimate') }}">
                        <input id="shipping_cost" type="hidden" name="shipping_cost" value="{{ old('shipping_cost', $selectedShippingCost) }}">

                        @if($rajaOngkirReady)
                            <div class="shipping-search-box">
                                <div class="field">
                                    <label for="destination_keyword">Cari Wilayah untuk Perhitungan Ongkir</label>
                                    <div class="shipping-search-actions">
                                        <input id="destination_keyword" type="text" placeholder="Contoh: Cikawao Pacet atau Cimaung Bandung" autocomplete="off">
                                        <button class="btn-secondary" id="search_destination_button" type="button">Cari Tujuan</button>
                                    </div>
                                    <p class="checkout-field-note">
                                        Bagian ini dipakai untuk menghitung ongkir RajaOngkir. Coba cari dengan nama kecamatan, desa/kelurahan, atau kode pos.
                                        Jika wilayah tidak muncul persis, pilih wilayah terdekat yang paling sesuai dengan alamat lengkap di atas.
                                    </p>
                                    <div class="shipping-api-note">
                                        <strong>Catatan layanan ongkir</strong>
                                        Perhitungan ongkir memakai API RajaOngkir/Komerce. Karena layanan yang digunakan memiliki batas penggunaan, pencarian tujuan atau cek ongkir dapat sementara gagal ketika koneksi bermasalah, kuota API habis, atau layanan sedang tidak tersedia.
                                    </div>
                                </div>

                                <div id="destination_results" class="shipping-destination-results"></div>

                                <div class="checkout-select-row">
                                    <div class="field">
                                        <label for="courier">Kurir</label>
                                        <select id="courier" name="courier" required>
                                            <option value="">Pilih kurir</option>
                                            @foreach($couriers as $courierKey => $courierLabel)
                                                <option value="{{ $courierKey }}" @selected(old('courier') === $courierKey)>{{ $courierLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field" style="display:flex; align-items:end;">
                                        <button class="btn-secondary" id="check_shipping_button" type="button" style="width:100%;">Cek Ongkir</button>
                                    </div>
                                </div>

                                <p id="shipping_status" class="shipping-status">Pilih tujuan dan kurir untuk melihat ongkir RajaOngkir.</p>
                                <div id="shipping_rate_results" class="shipping-rate-results"></div>
                                <button class="btn-secondary" id="use_manual_shipping_button" type="button" style="width:fit-content;">Gunakan Ongkir Manual</button>

                                <div class="shipping-manual-fallback" id="manual_shipping_fallback">
                                    <h3>Opsi Ongkir Manual</h3>
                                    <p class="checkout-field-note" style="margin-bottom:12px;">
                                        Gunakan opsi ini jika RajaOngkir sedang limit/error atau wilayah tidak ditemukan.
                                        Kurir tetap dipilih pada kolom <strong>Kurir</strong> di atas, sedangkan ongkir dihitung berdasarkan zona pengiriman sederhana dan dapat dikonfirmasi kembali oleh admin.
                                    </p>
                                    <div class="field" style="margin-bottom:0;">
                                        <label for="manual_shipping_zone">Zona Pengiriman Manual</label>
                                        <select id="manual_shipping_zone">
                                            <option value="" data-cost="0">Pilih zona</option>
                                            @foreach($shippingZones as $zoneKey => $zone)
                                                <option
                                                    value="{{ $zoneKey }}"
                                                    data-cost="{{ $zone['cost'] }}"
                                                    data-estimate="{{ $zone['estimate'] }}"
                                                    @selected(old('shipping_zone') === $zoneKey)
                                                >
                                                    {{ $zone['label'] }} - Rp {{ number_format($zone['cost'], 0, ',', '.') }} ({{ $zone['estimate'] }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="checkout-field-note" style="margin-bottom:12px;">
                                RajaOngkir belum aktif karena ID asal toko belum diisi. Sementara checkout memakai ongkir manual.
                            </p>
                            <div class="checkout-select-row">
                                <div class="field">
                                    <label for="manual_shipping_zone">Zona Pengiriman</label>
                                    <select id="manual_shipping_zone" required>
                                        <option value="" data-cost="0">Pilih zona</option>
                                        @foreach($shippingZones as $zoneKey => $zone)
                                            <option
                                                value="{{ $zoneKey }}"
                                                data-cost="{{ $zone['cost'] }}"
                                                data-estimate="{{ $zone['estimate'] }}"
                                                @selected(old('shipping_zone') === $zoneKey)
                                            >
                                                {{ $zone['label'] }} - Rp {{ number_format($zone['cost'], 0, ',', '.') }} ({{ $zone['estimate'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="courier">Kurir</label>
                                    <select id="courier" name="courier" required>
                                        <option value="">Pilih kurir</option>
                                        @foreach($couriers as $courierKey => $courierLabel)
                                            <option value="{{ $courierKey }}" @selected(old('courier') === $courierKey)>{{ $courierLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="card checkout-card">
                    <div class="checkout-card-header">
                        <h2>Pembayaran QRIS</h2>
                        <span class="badge">Manual Verification</span>
                    </div>
                    <div class="checkout-card-body">
                        <div class="payment-box">
                            <div class="qris-image-box" role="button" tabindex="0" aria-label="Perbesar QRIS" data-qris-open>
                                <img src="{{ asset('image/Qris.jpeg') }}" alt="QRIS Sejucara">
                            </div>
                            <div>
                                <h3 style="margin:0 0 8px;">Scan QRIS toko</h3>
                                <p class="muted" style="margin:0 0 12px; line-height:1.6;">
                                    Lakukan pembayaran sesuai total checkout, lalu upload bukti pembayaran di bawah. Admin akan memverifikasi pesanan Anda.
                                </p>
                                <div class="qris-actions">
                                    <a class="btn-secondary" href="{{ asset('image/Qris.jpeg') }}" download="QRIS-Sejucara.jpeg">Download QRIS</a>
                                </div>
                                <div class="field upload-box" style="margin-bottom:0;">
                                    <label for="payment_proof">Upload Bukti Pembayaran</label>
                                    <input id="payment_proof" type="file" name="payment_proof" accept="image/png,image/jpeg" required>
                                    <p class="checkout-field-note">Format JPG/PNG, maksimal 2 MB.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="card checkout-card checkout-summary">
                <div class="checkout-card-header">
                    <h3>Ringkasan Pesanan</h3>
                    <span class="badge">{{ count($items) }} item</span>
                </div>
                <div class="checkout-card-body">
                    <div class="summary-items">
                        @foreach($items as $item)
                            <div class="summary-item">
                                <img src="{{ $item['product']->imageUrl() }}" alt="{{ $item['product']->name }}">
                                <div>
                                    <h4>{{ $item['product']->name }}</h4>
                                    <div class="summary-item-meta">
                                        <span>{{ $item['quantity'] }} x Rp {{ number_format($item['product']->price, 0, ',', '.') }}</span>
                                        <strong>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-row">
                        <span>Subtotal Produk</span>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    <div class="summary-row">
                        <span>Ongkir</span>
                        <strong id="shipping-cost-display">Rp {{ number_format($selectedShippingCost, 0, ',', '.') }}</strong>
                    </div>
                    <div class="shipping-estimate-box">
                        <strong>Estimasi Tiba</strong>
                        <span id="shipping-estimate-display">{{ $selectedShippingEstimate }}</span>
                    </div>
                    <div class="shipping-note-box">
                        Nomor resi akan tersedia setelah pembayaran diverifikasi dan paket diproses oleh admin bersama pihak ekspedisi.
                    </div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span id="grand-total-display">Rp {{ number_format($total + $selectedShippingCost, 0, ',', '.') }}</span>
                    </div>

                    <button class="btn-primary checkout-submit" type="submit">Konfirmasi Checkout</button>
                    <p class="secure-note">Pesanan akan masuk setelah bukti pembayaran dikirim.</p>
                </div>
            </aside>
        </div>
    </form>

    <div class="qris-modal" id="qris-modal" aria-hidden="true">
        <div class="qris-modal-card" role="dialog" aria-modal="true" aria-labelledby="qris-modal-title">
            <div class="qris-modal-header">
                <h3 id="qris-modal-title">QRIS Sejucara</h3>
                <button class="qris-close" type="button" aria-label="Tutup QRIS" data-qris-close>&times;</button>
            </div>
            <div class="qris-modal-body">
                <img src="{{ asset('image/Qris.jpeg') }}" alt="QRIS Sejucara ukuran besar">
                <div class="qris-actions" style="justify-content:center; margin:16px 0 0;">
                    <a class="btn-primary" href="{{ asset('image/Qris.jpeg') }}" download="QRIS-Sejucara.jpeg">Download QRIS</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const subtotal = {{ (int) $total }};
            const rajaOngkirReady = @json($rajaOngkirReady);
            const shippingZoneInput = document.getElementById('shipping_zone');
            const shippingDestinationLabelInput = document.getElementById('shipping_destination_label');
            const shippingServiceInput = document.getElementById('shipping_service');
            const shippingEstimateInput = document.getElementById('shipping_estimate');
            const shippingCostInput = document.getElementById('shipping_cost');
            const manualZoneSelect = document.getElementById('manual_shipping_zone');
            const manualShippingFallback = document.getElementById('manual_shipping_fallback');
            const useManualShippingButton = document.getElementById('use_manual_shipping_button');
            const courierSelect = document.getElementById('courier');
            const destinationKeyword = document.getElementById('destination_keyword');
            const searchDestinationButton = document.getElementById('search_destination_button');
            const checkShippingButton = document.getElementById('check_shipping_button');
            const destinationResults = document.getElementById('destination_results');
            const shippingRateResults = document.getElementById('shipping_rate_results');
            const shippingStatus = document.getElementById('shipping_status');
            const shippingDisplay = document.getElementById('shipping-cost-display');
            const grandTotalDisplay = document.getElementById('grand-total-display');
            const shippingEstimateDisplay = document.getElementById('shipping-estimate-display');

            const formatCurrency = (amount) => new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0,
            }).format(amount).replace(/\s/g, ' ');

            const updateTotals = (shippingCost = Number(shippingCostInput.value || 0), shippingEstimate = shippingEstimateInput.value || 'Pilih tujuan dan cek ongkir') => {
                shippingCostInput.value = shippingCost;
                shippingEstimateInput.value = shippingEstimate;
                shippingDisplay.textContent = formatCurrency(shippingCost);
                grandTotalDisplay.textContent = formatCurrency(subtotal + shippingCost);
                shippingEstimateDisplay.textContent = shippingEstimate;
            };

            const setStatus = (message) => {
                if (shippingStatus) {
                    shippingStatus.textContent = message;
                }
            };

            const openManualShipping = () => {
                if (manualShippingFallback) {
                    manualShippingFallback.classList.add('is-open');
                }
                setStatus('Ongkir manual aktif. Pilih zona pengiriman dan kurir sebelum checkout.');
            };

            useManualShippingButton?.addEventListener('click', openManualShipping);

            if (manualZoneSelect) {
                manualZoneSelect.addEventListener('change', () => {
                    const selectedOption = manualZoneSelect.options[manualZoneSelect.selectedIndex];
                    const shippingCost = Number(selectedOption?.dataset.cost || 0);
                    const shippingEstimate = selectedOption?.dataset.estimate || 'Pilih zona pengiriman';

                    shippingZoneInput.value = manualZoneSelect.value;
                    shippingServiceInput.value = '';
                    shippingDestinationLabelInput.value = selectedOption?.textContent?.trim() || '';
                    updateTotals(shippingCost, shippingEstimate);

                    if (manualZoneSelect.value) {
                        setStatus('Ongkir manual dipilih. Admin dapat mengonfirmasi kembali ongkir jika diperlukan.');
                    }
                });
            }

            if (!rajaOngkirReady && manualZoneSelect) {
                manualZoneSelect.dispatchEvent(new Event('change'));
                return;
            }

            const renderDestinationResults = (items) => {
                destinationResults.innerHTML = '';

                if (!items.length) {
                    destinationResults.innerHTML = '<p class="shipping-status">Tujuan tidak ditemukan atau layanan RajaOngkir sedang terbatas. Coba kata kunci/kode pos lain, atau coba lagi beberapa saat.</p>';
                    openManualShipping();
                    return;
                }

                items.forEach((item) => {
                    const label = document.createElement('label');
                    label.className = 'shipping-option';
                    label.innerHTML = `
                        <input type="radio" name="destination_choice" value="${item.id}">
                        <div>
                            <strong>${item.label}</strong>
                            <span>ID tujuan: ${item.id}</span>
                        </div>
                    `;

                    label.querySelector('input').addEventListener('change', () => {
                        shippingZoneInput.value = `ro:${item.id}`;
                        shippingDestinationLabelInput.value = item.label;
                        shippingServiceInput.value = '';
                        shippingRateResults.innerHTML = '';
                        updateTotals(0, 'Klik cek ongkir untuk melihat estimasi');
                        setStatus(`Tujuan dipilih: ${item.label}`);
                    });

                    destinationResults.appendChild(label);
                });
            };

            const renderRates = (rates) => {
                shippingRateResults.innerHTML = '';

                if (!rates.length) {
                    shippingRateResults.innerHTML = '<p class="shipping-status">Ongkir tidak ditemukan untuk kurir ini atau API RajaOngkir sedang limit/error. Coba kurir lain atau coba lagi beberapa saat.</p>';
                    updateTotals(0, 'Pilih layanan pengiriman');
                    openManualShipping();
                    return;
                }

                rates.forEach((rate) => {
                    const label = document.createElement('label');
                    label.className = 'shipping-option';
                    label.innerHTML = `
                        <input type="radio" name="shipping_rate_choice" value="${rate.service}">
                        <div>
                            <strong>${rate.courier} ${rate.service} - ${formatCurrency(rate.cost)}</strong>
                            <span>Estimasi: ${rate.estimate || 'Mengikuti kurir'}</span>
                        </div>
                    `;

                    label.querySelector('input').addEventListener('change', () => {
                        shippingServiceInput.value = rate.service;
                        updateTotals(Number(rate.cost || 0), rate.estimate || 'Mengikuti kurir');
                        setStatus(`Ongkir dipilih: ${rate.courier} ${rate.service}`);
                    });

                    shippingRateResults.appendChild(label);
                });
            };

            searchDestinationButton?.addEventListener('click', async () => {
                const keyword = destinationKeyword.value.trim();

                if (keyword.length < 3) {
                    setStatus('Ketik minimal 3 huruf untuk mencari tujuan.');
                    return;
                }

                setStatus('Mencari tujuan...');
                destinationResults.innerHTML = '';
                shippingRateResults.innerHTML = '';

                try {
                    const response = await fetch(`{{ route('checkout.destinations') }}?keyword=${encodeURIComponent(keyword)}`, {
                        headers: { 'Accept': 'application/json' },
                    });
                    const payload = await response.json();
                    renderDestinationResults(payload.data || []);
                    setStatus('Pilih tujuan yang paling sesuai, lalu cek ongkir.');
                } catch (error) {
                    setStatus('Tujuan belum bisa dicari. Kemungkinan koneksi bermasalah atau API RajaOngkir sedang limit/error.');
                    openManualShipping();
                }
            });

            checkShippingButton?.addEventListener('click', async () => {
                const destinationId = shippingZoneInput.value.replace('ro:', '');
                const courier = courierSelect.value;

                if (!destinationId || !courier) {
                    setStatus('Pilih tujuan dan kurir terlebih dahulu.');
                    return;
                }

                setStatus('Mengambil ongkir RajaOngkir...');
                shippingRateResults.innerHTML = '';

                try {
                    const response = await fetch(`{{ route('checkout.shipping-rates') }}`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ destination_id: destinationId, courier }),
                    });
                    const payload = await response.json();
                    renderRates(payload.data || []);
                    setStatus('Pilih salah satu layanan pengiriman.');
                } catch (error) {
                    setStatus('Ongkir belum bisa diambil. Kemungkinan API RajaOngkir sedang limit/error. Coba lagi beberapa saat atau gunakan kurir lain.');
                    openManualShipping();
                }
            });

            updateTotals();
        })();

        (() => {
            const modal = document.getElementById('qris-modal');
            const openButtons = document.querySelectorAll('[data-qris-open]');
            const closeButton = document.querySelector('[data-qris-close]');

            const openModal = () => {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('qris-modal-open');
                closeButton.focus();
            };

            const closeModal = () => {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('qris-modal-open');
            };

            openButtons.forEach((button) => {
                button.addEventListener('click', openModal);
                button.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        openModal();
                    }
                });
            });

            closeButton.addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeModal();
                }
            });
        })();
    </script>
@endsection
