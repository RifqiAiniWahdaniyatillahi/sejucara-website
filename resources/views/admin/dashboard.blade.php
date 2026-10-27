@extends('admin.layouts.app')

@section('content')
    <style>
        .dashboard-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            align-items: center;
            margin-bottom: 18px;
            padding: 24px;
            border-radius: 18px;
            background:
                linear-gradient(135deg, rgba(139, 55, 7, 0.93) 0%, rgba(201, 106, 49, 0.86) 100%),
                url('{{ asset('image/bg.jpg') }}') center/cover no-repeat;
            color: #fff8e9;
            box-shadow: 0 16px 36px rgba(90, 44, 20, 0.14);
        }

        .dashboard-hero h1 {
            margin: 0 0 8px;
            color: #fff7e8;
            font-size: 32px;
        }

        .dashboard-hero p {
            margin: 0;
            max-width: 680px;
            color: #ffe8c5;
            line-height: 1.7;
        }

        .dashboard-hero-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .dashboard-hero .btn-primary {
            background: #fffaf2;
            border-color: #fffaf2;
            color: #8b3707;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .stat-card {
            padding: 18px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 5px;
            background: var(--admin-primary);
        }

        .stat-label {
            color: var(--admin-muted);
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .stat-value {
            margin-top: 8px;
            color: #5e2d11;
            font-size: 31px;
            font-weight: 900;
            line-height: 1;
        }

        .stat-note {
            margin-top: 8px;
            color: var(--admin-muted);
            font-size: 13px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(320px, 0.75fr);
            gap: 14px;
            align-items: start;
        }

        .insight-card {
            margin-bottom: 18px;
        }

        .insight-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .insight-header h3 {
            margin: 0 0 4px;
        }

        .insight-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .insight-item {
            padding: 14px;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            background: #fffdf8;
        }

        .insight-label {
            display: block;
            color: var(--admin-muted);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .insight-value {
            margin-top: 7px;
            color: #5e2d11;
            font-size: 26px;
            font-weight: 900;
            line-height: 1;
        }

        .insight-note {
            margin: 7px 0 0;
            color: var(--admin-muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .insight-list {
            display: grid;
            gap: 10px;
        }

        .insight-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
        }

        .insight-bar {
            height: 8px;
            margin-top: 6px;
            border-radius: 999px;
            background: #f6dfc4;
            overflow: hidden;
        }

        .insight-bar span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: var(--admin-primary);
        }

        .section-card h3 {
            margin: 0 0 12px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 4px 9px;
            border-radius: 999px;
            background: #fff1df;
            color: #8b3a0f;
            font-size: 12px;
            font-weight: 800;
        }

        .quick-actions {
            display: grid;
            gap: 10px;
        }

        .quick-action {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            padding: 12px;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            background: #fffdf8;
            text-decoration: none;
        }

        .quick-action strong {
            display: block;
            color: var(--admin-text);
        }

        .quick-action span {
            color: var(--admin-muted);
            font-size: 13px;
        }

        .quick-action:hover {
            background: #fff2de;
        }

        .stock-list {
            display: grid;
            gap: 10px;
        }

        .stock-item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--admin-border);
        }

        .stock-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        @media (max-width: 1100px) {
            .dashboard-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .insight-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
            .dashboard-hero {
                grid-template-columns: 1fr;
            }

            .dashboard-hero-actions {
                justify-content: flex-start;
            }

            .dashboard-stats {
                grid-template-columns: 1fr;
            }

            .insight-header {
                flex-direction: column;
            }

            .insight-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="dashboard-hero">
        <div>
            <h1>Dashboard Admin</h1>
            <p>
                Pantau pesanan QRIS, stok produk, artikel edukasi, dan diskusi forum Sejucara dari satu halaman ringkas.
            </p>
        </div>
        <div class="dashboard-hero-actions">
            <a class="btn-primary" href="{{ route('admin.orders.index') }}">Kelola Pesanan</a>
            <a class="btn-primary" href="{{ route('admin.products.create') }}">Tambah Produk</a>
        </div>
    </section>

    <section class="dashboard-stats">
        <article class="admin-card stat-card">
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ $totalOrders }}</div>
            <div class="stat-note">{{ $pendingOrders }} menunggu verifikasi</div>
        </article>
        <article class="admin-card stat-card">
            <div class="stat-label">Pendapatan</div>
            <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-note">Omzet produk dari pesanan valid</div>
        </article>
        <article class="admin-card stat-card">
            <div class="stat-label">Produk</div>
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-note">{{ $lowStockProducts->count() }} stok perlu dicek</div>
        </article>
        <article class="admin-card stat-card">
            <div class="stat-label">Komunitas</div>
            <div class="stat-value">{{ $totalThreads }}</div>
            <div class="stat-note">{{ $totalUsers }} user dan {{ $totalArticles }} artikel</div>
        </article>
    </section>

    <section class="card insight-card">
        <div class="insight-header">
            <div>
                <h3>Insight Interaksi Pelanggan</h3>
                <p class="muted" style="margin:0;">
                    Ringkasan ini membantu admin membaca kebutuhan pelanggan dari forum, live chat, dan transaksi.
                </p>
            </div>
            <a class="btn-primary" href="{{ route('admin.forum.index') }}">Lihat Forum</a>
        </div>

        <div class="insight-grid">
            <article class="insight-item">
                <span class="insight-label">Diskusi Belum Dijawab</span>
                <div class="insight-value">{{ $unansweredThreads }}</div>
                <p class="insight-note">Topik forum yang belum mendapat tanggapan admin.</p>
            </article>
            <article class="insight-item">
                <span class="insight-label">Chat Menunggu Admin</span>
                <div class="insight-value">{{ $chatWaitingResponse }}</div>
                <p class="insight-note">Percakapan terakhir dari pelanggan yang perlu dibalas.</p>
            </article>
            <article class="insight-item">
                <span class="insight-label">Pesanan Diproses/Dikirim</span>
                <div class="insight-value">{{ $processingOrders }}</div>
                <p class="insight-note">Pesanan aktif yang sedang ditangani admin.</p>
            </article>
            <article class="insight-item">
                <span class="insight-label">Pesanan Selesai</span>
                <div class="insight-value">{{ $completedOrders }}</div>
                <p class="insight-note">Transaksi yang sudah selesai ditangani.</p>
            </article>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="card section-card">
            <div class="actions" style="justify-content: space-between; margin-bottom: 10px;">
                <h3>Pesanan Terbaru</h3>
                <a class="btn-primary" href="{{ route('admin.orders.index') }}">Lihat Semua</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pemesan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td><span class="status-badge">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span></td>
                            <td><a href="{{ route('admin.orders.show', $order) }}">Detail</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="muted">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <aside class="card section-card">
            <h3>Aksi Cepat</h3>
            <div class="quick-actions">
                <a class="quick-action" href="{{ route('admin.orders.index') }}">
                    <div>
                        <strong>Verifikasi Pesanan</strong>
                        <span>{{ $pendingOrders }} pesanan menunggu pembayaran dicek.</span>
                    </div>
                    <span>Masuk</span>
                </a>
                <a class="quick-action" href="{{ route('admin.articles.create') }}">
                    <div>
                        <strong>Tulis Artikel</strong>
                        <span>Tambah edukasi cascara untuk pengunjung.</span>
                    </div>
                    <span>Buat</span>
                </a>
                <a class="quick-action" href="{{ route('admin.forum.index') }}">
                    <div>
                        <strong>Moderasi Forum</strong>
                        <span>Jawab diskusi dan pertanyaan pengguna.</span>
                    </div>
                    <span>Lihat</span>
                </a>
                <a class="quick-action" href="{{ route('admin.chat.index') }}">
                    <div>
                        <strong>Balas Live Chat</strong>
                        <span>Tanggapi pertanyaan langsung dari pelanggan.</span>
                    </div>
                    <span>Buka</span>
                </a>
            </div>
        </aside>
    </section>

    <section class="dashboard-grid" style="margin-top: 14px;">
        <div class="card section-card">
            <h3>Produk Terbaru</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="muted">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <aside class="card section-card">
            <h3>Stok Rendah</h3>
            <div class="stock-list">
                @forelse($lowStockProducts as $product)
                    <div class="stock-item">
                        <span>{{ $product->name }}</span>
                        <strong>{{ $product->stock }} tersisa</strong>
                    </div>
                @empty
                    <p class="muted" style="margin:0;">Semua stok masih aman.</p>
                @endforelse
            </div>
        </aside>
    </section>

    <section class="dashboard-grid" style="margin-top: 14px;">
        <div class="card section-card">
            <h3>Topik Forum yang Sering Ditanyakan</h3>
            <div class="insight-list">
                @php $maxTopicCount = max($forumTopicInsights->max('count') ?? 0, 1); @endphp
                @foreach($forumTopicInsights as $topic)
                    <div class="insight-row">
                        <div>
                            <strong>{{ $topic['label'] }}</strong>
                            <div class="insight-bar">
                                <span style="width: {{ ($topic['count'] / $maxTopicCount) * 100 }}%;"></span>
                            </div>
                        </div>
                        <span class="status-badge">{{ $topic['count'] }} diskusi</span>
                    </div>
                @endforeach
            </div>
        </div>

        <aside class="card section-card">
            <h3>Produk Paling Diminati</h3>
            <div class="stock-list">
                @forelse($topOrderedProducts as $item)
                    <div class="stock-item">
                        <span>{{ $item->product?->name ?? 'Produk tidak tersedia' }}</span>
                        <strong>{{ (int) $item->total_quantity }} terjual</strong>
                    </div>
                @empty
                    <p class="muted" style="margin:0;">Belum ada data produk terjual.</p>
                @endforelse
            </div>
        </aside>
    </section>

    <section class="card section-card" style="margin-top: 14px;">
        <div class="actions" style="justify-content: space-between; margin-bottom: 10px;">
            <h3>Diskusi Forum Terbaru</h3>
            <a class="btn-primary" href="{{ route('admin.forum.index') }}">Kelola Forum</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Topik</th>
                    <th>Pembuat</th>
                    <th>Balasan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestThreads as $thread)
                    <tr>
                        <td>{{ $thread->title }}</td>
                        <td>{{ $thread->user->name }}</td>
                        <td>{{ $thread->replies_count }}</td>
                        <td>{{ $thread->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted">Belum ada diskusi forum.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
