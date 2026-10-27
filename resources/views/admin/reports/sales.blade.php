@extends('admin.layouts.app')

@section('content')
    <style>
        .report-header h1 {
            margin: 0 0 8px;
        }

        .report-header p {
            max-width: 780px;
            line-height: 1.7;
            margin: 0;
        }

        .report-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 16px;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            background: #fffaf3;
            color: #8b3a0f;
            font-weight: 900;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: #fff1df;
        }

        .report-filter {
            display: grid;
            grid-template-columns: repeat(3, minmax(160px, 1fr));
            gap: 12px;
            align-items: end;
            margin-bottom: 16px;
        }

        .report-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(170px, 1fr));
            gap: 14px;
            margin-bottom: 16px;
        }

        .report-summary-card {
            padding: 18px;
        }

        .report-summary-card span {
            display: block;
            color: var(--admin-muted);
            font-size: 14px;
            font-weight: 800;
        }

        .report-summary-card strong {
            display: block;
            margin-top: 8px;
            color: #8b3a0f;
            font-size: 24px;
        }

        .report-grid {
            display: grid;
            grid-template-columns: minmax(280px, 0.9fr) minmax(320px, 1.1fr);
            gap: 14px;
            margin-bottom: 16px;
        }

        .report-list {
            display: grid;
            gap: 10px;
        }

        .report-list-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 11px 12px;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            background: #fffdf8;
        }

        .report-list-row strong {
            color: #6e2d0b;
        }

        .report-table-wrapper {
            overflow-x: auto;
        }

        .report-status-note {
            display: inline-flex;
            width: fit-content;
            margin-top: 8px;
            padding: 7px 10px;
            border-radius: 999px;
            background: #fff1df;
            color: #7c451c;
            font-size: 13px;
            font-weight: 800;
        }

        @media (max-width: 980px) {
            .report-summary-grid,
            .report-grid,
            .report-filter {
                grid-template-columns: 1fr;
            }

            .report-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>

    <div class="report-header" style="margin-bottom:18px;">
        <h1>Laporan Penjualan</h1>
        <p class="muted">
            Laporan ini menghitung pesanan yang sudah valid secara operasional, yaitu status
            {{ implode(', ', array_map(fn ($status) => str_replace('_', ' ', $status), $salesStatuses)) }}.
            Omzet dihitung dari subtotal produk, sehingga ongkir tidak dimasukkan sebagai pendapatan Sejucara.
        </p>
        <span class="report-status-note">
            Periode: {{ $startAt->format('d M Y') }} - {{ $endAt->format('d M Y') }}
        </span>
    </div>

    <form class="card report-filter" action="{{ route('admin.reports.sales') }}" method="GET">
        <div class="field" style="margin-bottom:0;">
            <label for="start_date">Tanggal Mulai</label>
            <input id="start_date" type="date" name="start_date" value="{{ $startAt->format('Y-m-d') }}">
        </div>
        <div class="field" style="margin-bottom:0;">
            <label for="end_date">Tanggal Akhir</label>
            <input id="end_date" type="date" name="end_date" value="{{ $endAt->format('Y-m-d') }}">
        </div>
        <div class="report-actions">
            <button class="btn-primary" type="submit">Tampilkan Laporan</button>
            <a class="btn-secondary" href="{{ route('admin.reports.sales.download', request()->only(['start_date', 'end_date'])) }}">Download</a>
        </div>
    </form>

    <div class="report-summary-grid">
        <div class="card report-summary-card">
            <span>Total Omzet Produk</span>
            <strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>
        </div>
        <div class="card report-summary-card">
            <span>Pesanan Valid</span>
            <strong>{{ number_format($totalOrders, 0, ',', '.') }}</strong>
        </div>
        <div class="card report-summary-card">
            <span>Produk Terjual</span>
            <strong>{{ number_format($totalProductsSold, 0, ',', '.') }}</strong>
        </div>
        <div class="card report-summary-card">
            <span>Rata-rata Transaksi</span>
            <strong>Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="report-grid">
        <section class="card">
            <h2 style="margin-top:0;">Produk Terlaris</h2>
            <div class="report-list">
                @forelse($topProducts as $item)
                    <div class="report-list-row">
                        <div>
                            <strong>{{ $item->product?->name ?? 'Produk terhapus' }}</strong>
                            <div class="muted">{{ number_format($item->total_quantity, 0, ',', '.') }} item terjual</div>
                        </div>
                        <strong>Rp {{ number_format($item->total_sales, 0, ',', '.') }}</strong>
                    </div>
                @empty
                    <p class="muted" style="margin:0;">Belum ada produk terjual pada periode ini.</p>
                @endforelse
            </div>
        </section>

        <section class="card">
            <h2 style="margin-top:0;">Rekap Harian</h2>
            <div class="report-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pesanan</th>
                            <th>Omzet Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailySales as $day)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($day->sale_date)->format('d M Y') }}</td>
                                <td>{{ number_format($day->total_orders, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($day->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="muted">Belum ada penjualan pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <section class="card">
        <h2 style="margin-top:0;">Daftar Transaksi</h2>
        <div class="report-table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Status</th>
                        <th>Item</th>
                        <th>Subtotal Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $productSubtotal = $order->items->sum('subtotal');
                        @endphp
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td><span class="badge">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span></td>
                            <td>
                                @foreach($order->items as $item)
                                    <div class="muted">{{ $item->product?->name ?? 'Produk terhapus' }} ({{ $item->quantity }}x)</div>
                                @endforeach
                            </td>
                            <td>
                                <strong>Rp {{ number_format($productSubtotal, 0, ',', '.') }}</strong>
                                <div class="muted">Ongkir tidak dihitung omzet</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="muted">Belum ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 14px;">{{ $orders->links() }}</div>
    </section>
@endsection
