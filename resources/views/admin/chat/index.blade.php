@extends('admin.layouts.app')

@section('content')
    <style>
        .chat-list-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
        }

        .chat-list-header h1 {
            margin: 0 0 5px;
        }

        .chat-list {
            display: grid;
            gap: 10px;
        }

        .chat-list-item {
            display: grid;
            grid-template-columns: 44px minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
            padding: 14px;
            color: inherit;
            text-decoration: none;
        }

        .chat-list-item:hover {
            border-color: #d68b51;
            background: #fff6e9;
        }

        .chat-user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffe3c2;
            color: #8b3707;
            font-weight: 900;
        }

        .chat-user-name {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            font-weight: 900;
        }

        .chat-preview {
            margin: 0;
            color: var(--admin-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-list-meta {
            text-align: right;
            white-space: nowrap;
        }

        .chat-unread {
            display: inline-flex;
            min-width: 22px;
            height: 22px;
            align-items: center;
            justify-content: center;
            margin-top: 6px;
            padding: 0 6px;
            border-radius: 999px;
            background: var(--admin-danger);
            color: #fff;
            font-size: 12px;
            font-weight: 900;
        }

        .chat-list-empty {
            padding: 34px 18px;
            text-align: center;
        }

        @media (max-width: 640px) {
            .chat-list-item {
                grid-template-columns: 40px minmax(0, 1fr);
            }

            .chat-list-meta {
                grid-column: 2;
                text-align: left;
            }
        }
    </style>

    <div class="chat-list-header">
        <div>
            <h1>Live Chat Pelanggan</h1>
            <p class="muted" style="margin:0;">Balas pertanyaan pelanggan tentang produk, pembayaran, dan pesanan.</p>
        </div>
    </div>

    <div class="chat-list">
        @forelse($conversations as $conversation)
            <a class="admin-card chat-list-item" href="{{ route('admin.chat.show', $conversation) }}">
                <span class="chat-user-avatar">{{ strtoupper(substr($conversation->user->name, 0, 1)) }}</span>
                <div style="min-width:0;">
                    <div class="chat-user-name">
                        {{ $conversation->user->name }}
                        @if($conversation->unread_count > 0)
                            <span class="chat-unread">{{ $conversation->unread_count > 99 ? '99+' : $conversation->unread_count }}</span>
                        @endif
                    </div>
                    <p class="chat-preview">
                        @if($conversation->latestMessage)
                            @php
                                $previewMessage = trim($conversation->latestMessage->message);
                                $previewText = $previewMessage !== '' ? $previewMessage : 'Mengirim foto';
                            @endphp
                            {{ $conversation->latestMessage->sender->is_admin ? 'Admin: ' : '' }}{{ $previewText }}
                        @else
                            Belum ada pesan.
                        @endif
                    </p>
                </div>
                <div class="chat-list-meta">
                    <span class="muted">{{ $conversation->latestMessage?->created_at?->translatedFormat('d M, H:i') ?? '-' }}</span>
                </div>
            </a>
        @empty
            <div class="card chat-list-empty">
                <h3 style="margin:0 0 6px;">Belum ada percakapan</h3>
                <p class="muted" style="margin:0;">Percakapan akan muncul setelah pengguna membuka atau mengirim pesan melalui live chat.</p>
            </div>
        @endforelse

        <div>{{ $conversations->links() }}</div>
    </div>
@endsection
