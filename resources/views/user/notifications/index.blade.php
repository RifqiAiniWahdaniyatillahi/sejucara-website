@extends('layouts.app')

@section('content')
    <style>
        .notification-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .notification-header h1 {
            margin: 0 0 6px;
        }

        .notification-list {
            display: grid;
            gap: 10px;
        }

        .notification-item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            align-items: center;
            border-radius: 12px;
        }

        .notification-item.is-unread {
            border-color: #d48a4d;
            background: #fff4e6;
        }

        .notification-meta {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 6px;
        }

        .notification-type {
            display: inline-flex;
            padding: 4px 9px;
            border-radius: 999px;
            background: #fff0d8;
            color: #7a421d;
            font-size: 12px;
            font-weight: 900;
            text-transform: capitalize;
        }

        .notification-item h3 {
            margin: 0 0 5px;
            font-size: 18px;
            font-family: "Nunito", "Segoe UI", sans-serif;
            font-weight: 900;
            color: var(--text);
        }

        .notification-item p {
            margin: 0;
            line-height: 1.55;
        }

        .notification-empty {
            text-align: center;
            padding: 32px 18px;
        }

        @media (max-width: 640px) {
            .notification-item {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="notification-header">
        <div>
            <h1>Notifikasi</h1>
            <p class="muted" style="margin:0;">Informasi terbaru tentang pesanan dan diskusi forum kamu.</p>
        </div>

        @if($notifications->whereNull('read_at')->isNotEmpty())
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button class="btn-primary" type="submit">Tandai Semua Dibaca</button>
            </form>
        @endif
    </div>

    <div class="notification-list">
        @forelse($notifications as $notification)
            <article class="card notification-item {{ $notification->read_at ? '' : 'is-unread' }}">
                <div>
                    <div class="notification-meta">
                        <span class="notification-type">{{ $notification->type === 'order' ? 'Pesanan' : 'Forum' }}</span>
                        <span class="muted">{{ $notification->created_at->translatedFormat('d F Y H:i') }}</span>
                        @if(! $notification->read_at)
                            <span class="badge">Baru</span>
                        @endif
                    </div>
                    <h3>{{ $notification->title }}</h3>
                    <p class="muted">{{ $notification->message }}</p>
                </div>
                <a class="btn-primary" href="{{ route('notifications.open', $notification) }}">Lihat</a>
            </article>
        @empty
            <div class="card notification-empty">
                <h3 style="margin-top:0;">Belum ada notifikasi</h3>
                <p class="muted" style="margin-bottom:0;">Nanti pemberitahuan pesanan dan forum akan muncul di sini.</p>
            </div>
        @endforelse

        <div style="margin-top: 4px;">{{ $notifications->links() }}</div>
    </div>
@endsection
