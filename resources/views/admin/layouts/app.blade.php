<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Sejucara</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Nunito:wght@400;500;600;700;800&display=swap');

        :root {
            --admin-bg: #f5eddf;
            --admin-surface: #fffaf2;
            --admin-panel: #8b3707;
            --admin-panel-soft: #a94b18;
            --admin-text: #3b2a1f;
            --admin-muted: #6f5b4b;
            --admin-primary: #c96a31;
            --admin-primary-dark: #a84f1f;
            --admin-border: #e9cfaa;
            --admin-good: #2f7d4f;
            --admin-warning: #a85f13;
            --admin-danger: #b63a3a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Nunito", "Segoe UI", Tahoma, sans-serif;
            color: var(--admin-text);
            background: linear-gradient(180deg, #fbf5ea 0%, var(--admin-bg) 100%);
        }

        a {
            color: inherit;
        }

        h1, h2, h3 {
            color: #8b3a0f;
            letter-spacing: 0;
        }

        .admin-wrapper {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            min-height: 100vh;
        }

        .admin-sidebar {
            position: sticky;
            top: 0;
            min-height: 100vh;
            padding: 22px 18px;
            background: linear-gradient(180deg, var(--admin-panel) 0%, var(--admin-panel-soft) 100%);
            color: #fff1d4;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 8px 22px;
            border-bottom: 1px solid rgba(255, 238, 205, 0.22);
            margin-bottom: 18px;
        }

        .admin-brand img {
            width: 46px;
            height: 46px;
            border-radius: 999px;
            object-fit: cover;
            background: #fffaf2;
        }

        .admin-brand strong {
            display: block;
            font-family: "Bagel Fat One", "Nunito", sans-serif;
            font-size: 25px;
            line-height: 1;
            color: #f6b25b;
            font-weight: 400;
        }

        .admin-brand span {
            display: block;
            margin-top: 3px;
            font-size: 12px;
            color: #ffe2b4;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .admin-nav a,
        .admin-nav button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 0;
            border-radius: 12px;
            padding: 11px 12px;
            background: transparent;
            color: #ffe4bb;
            text-decoration: none;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
            text-align: left;
        }

        .admin-nav a:hover,
        .admin-nav button:hover,
        .admin-nav a.active {
            background: rgba(255, 250, 242, 0.14);
            color: #fffaf2;
        }

        .nav-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: currentColor;
            opacity: 0.75;
            flex-shrink: 0;
        }

        .admin-nav-badge {
            min-width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            padding: 0 6px;
            border-radius: 999px;
            background: #fffaf2;
            color: var(--admin-danger);
            font-size: 11px;
            font-weight: 900;
        }

        .admin-sidebar-footer {
            margin-top: 24px;
            padding: 14px;
            border-radius: 14px;
            background: rgba(255, 250, 242, 0.1);
            color: #ffe2b4;
            font-size: 13px;
            line-height: 1.6;
        }

        .admin-main {
            min-width: 0;
            padding: 24px;
        }

        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }

        .admin-topbar-title p {
            margin: 4px 0 0;
            color: var(--admin-muted);
        }

        .admin-user-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border: 1px solid var(--admin-border);
            border-radius: 999px;
            background: var(--admin-surface);
            color: var(--admin-muted);
            font-weight: 800;
            white-space: nowrap;
        }

        .admin-avatar {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffe3c2;
            color: var(--admin-primary-dark);
        }

        .card,
        .admin-card {
            background: var(--admin-surface);
            border: 1px solid var(--admin-border);
            border-radius: 16px;
            box-shadow: 0 12px 28px rgba(90, 44, 20, 0.08);
        }

        .card {
            padding: 18px;
        }

        .muted {
            color: var(--admin-muted);
        }

        .btn-primary,
        .btn-danger {
            border-radius: 12px;
            padding: 10px 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            font-weight: 800;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-primary {
            color: #fff;
            background: var(--admin-primary);
            border-color: var(--admin-primary);
        }

        .btn-primary:hover {
            background: var(--admin-primary-dark);
            border-color: var(--admin-primary-dark);
        }

        .btn-danger {
            color: #fff;
            background: var(--admin-danger);
            border-color: var(--admin-danger);
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 11px 10px;
            border-bottom: 1px solid var(--admin-border);
            text-align: left;
            vertical-align: top;
        }

        th {
            color: #5e3419;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        input, textarea, select {
            width: 100%;
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            padding: 10px;
            font: inherit;
            background: #fff;
            color: var(--admin-text);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 800;
            color: #5e3419;
        }

        .field {
            margin-bottom: 12px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 5px 9px;
            border-radius: 999px;
            background: #ffe3c2;
            color: #8b3a0f;
            font-size: 12px;
            font-weight: 900;
        }

        .flash {
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 14px;
            font-size: 14px;
        }

        .flash-success {
            background: #e9f8eb;
            border: 1px solid #bce5c3;
            color: #245b2d;
        }

        .flash-error {
            background: #fdecec;
            border: 1px solid #f2c0c0;
            color: #7a2222;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 18px;
            color: var(--admin-muted);
            font-size: 14px;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] > div:first-child {
            display: none;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] > div:last-child {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] p {
            margin: 0;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] a,
        nav[role="navigation"][aria-label="Pagination Navigation"] span[aria-current] > span,
        nav[role="navigation"][aria-label="Pagination Navigation"] span[aria-disabled] > span {
            min-width: 36px;
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
            padding: 8px 11px;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            background: var(--admin-surface);
            color: var(--admin-text);
            text-decoration: none;
            font-weight: 800;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] a:hover {
            border-color: var(--admin-primary);
            color: var(--admin-primary-dark);
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] span[aria-current] > span {
            background: var(--admin-primary);
            border-color: var(--admin-primary);
            color: #fff;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] span[aria-disabled] > span {
            opacity: 0.45;
            cursor: not-allowed;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] svg {
            width: 18px;
            height: 18px;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] .hidden {
            display: none;
        }

        @media (max-width: 940px) {
            .admin-wrapper {
                grid-template-columns: 1fr;
            }

            .admin-sidebar {
                position: static;
                min-height: auto;
            }

            .admin-main {
                padding: 18px;
            }

            .admin-topbar {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    @php
        $adminUnreadChatCount = \App\Models\ChatMessage::whereHas(
            'sender',
            fn ($query) => $query->where('is_admin', false)
        )->whereNull('read_at')->count();
        $adminPendingOrderCount = \App\Models\Order::whereIn('status', [
            'menunggu_pembayaran',
            'menunggu_verifikasi',
        ])->count();
        $adminUnansweredForumCount = \App\Models\ForumThread::whereDoesntHave(
            'replies.user',
            fn ($query) => $query->where('is_admin', true)
        )->count();
    @endphp
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <img src="{{ asset('image/logosc2.png') }}" alt="Sejucara">
                <div>
                    <strong>Sejucara</strong>
                    <span>Admin Panel</span>
                </div>
            </div>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span class="nav-dot"></span>Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><span class="nav-dot"></span>Produk</a>
                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <span class="nav-dot"></span>Pesanan
                    @if($adminPendingOrderCount > 0)
                        <span class="admin-nav-badge">{{ $adminPendingOrderCount > 99 ? '99+' : $adminPendingOrderCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.reports.sales') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><span class="nav-dot"></span>Laporan</a>
                <a href="{{ route('admin.articles.index') }}" class="{{ request()->routeIs('admin.articles.*') ? 'active' : '' }}"><span class="nav-dot"></span>Artikel</a>
                <a href="{{ route('admin.forum.index') }}" class="{{ request()->routeIs('admin.forum.*') ? 'active' : '' }}">
                    <span class="nav-dot"></span>Forum
                    @if($adminUnansweredForumCount > 0)
                        <span class="admin-nav-badge">{{ $adminUnansweredForumCount > 99 ? '99+' : $adminUnansweredForumCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.chat.index') }}" class="{{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                    <span class="nav-dot"></span>Live Chat
                    @if($adminUnreadChatCount > 0)
                        <span class="admin-nav-badge">{{ $adminUnreadChatCount > 99 ? '99+' : $adminUnreadChatCount }}</span>
                    @endif
                </a>
                <a href="{{ route('home') }}"><span class="nav-dot"></span>Lihat Website</a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"><span class="nav-dot"></span>Logout</button>
                </form>
            </nav>

            <div class="admin-sidebar-footer">
                Panel ini dipakai untuk mengelola katalog produk, pesanan QRIS, artikel edukasi, forum, dan live chat pelanggan.
            </div>
        </aside>

        <main class="admin-main">
            <div class="admin-topbar">
                <div class="admin-topbar-title">
                    <strong class="muted">Sejucara Admin</strong>
                    <p>Kelola operasional website dengan data yang ringkas dan mudah dipantau.</p>
                </div>
                <div class="admin-user-pill">
                    <span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                    {{ auth()->user()->name ?? 'Admin' }}
                </div>
            </div>

            @if(session('success'))
                <div class="flash flash-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="flash flash-error">
                    <ul style="margin:0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
