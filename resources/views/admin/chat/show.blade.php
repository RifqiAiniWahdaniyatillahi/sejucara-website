@extends('admin.layouts.app')

@section('content')
    <style>
        .admin-chat-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 14px;
        }

        .admin-chat-heading h1 {
            margin: 0 0 4px;
        }

        .admin-chat-shell {
            padding: 0;
            overflow: hidden;
        }

        .admin-chat-user {
            padding: 14px 18px;
            border-bottom: 1px solid var(--admin-border);
            background: #fff6e9;
        }

        .admin-chat-user strong {
            display: block;
            margin-bottom: 2px;
        }

        .admin-chat-messages {
            height: min(58vh, 540px);
            min-height: 360px;
            overflow-y: auto;
            padding: 20px;
            background: #fffdf9;
        }

        .admin-chat-empty {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--admin-muted);
            text-align: center;
        }

        .admin-chat-empty.is-hidden {
            display: none !important;
        }

        .admin-chat-message {
            display: flex;
            margin-bottom: 14px;
        }

        .admin-chat-message.is-mine {
            justify-content: flex-end;
        }

        .admin-chat-bubble {
            max-width: min(74%, 580px);
            padding: 10px 13px 8px;
            border: 1px solid var(--admin-border);
            border-radius: 14px 14px 14px 4px;
            background: #fff;
        }

        .admin-chat-message.is-mine .admin-chat-bubble {
            border-color: var(--admin-primary);
            border-radius: 14px 14px 4px 14px;
            background: var(--admin-primary);
            color: #fff;
        }

        .admin-chat-sender {
            display: block;
            margin-bottom: 3px;
            color: #8b3a0f;
            font-size: 12px;
            font-weight: 900;
        }

        .admin-chat-message.is-mine .admin-chat-sender {
            color: #fff1df;
        }

        .admin-chat-text {
            margin: 0;
            line-height: 1.5;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .admin-chat-image {
            display: block;
            width: min(260px, 100%);
            max-height: 260px;
            margin-top: 8px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid var(--admin-border);
        }

        .admin-chat-message.is-mine .admin-chat-image {
            border-color: rgba(255, 255, 255, 0.35);
        }

        .admin-chat-time {
            display: block;
            margin-top: 5px;
            color: var(--admin-muted);
            font-size: 11px;
            text-align: right;
        }

        .admin-chat-message.is-mine .admin-chat-time {
            color: #ffe4ce;
        }

        .admin-chat-form {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            gap: 10px;
            align-items: end;
            padding: 14px;
            border-top: 1px solid var(--admin-border);
            background: var(--admin-surface);
        }

        .admin-chat-form textarea {
            min-height: 48px;
            max-height: 130px;
            resize: vertical;
        }

        .admin-chat-form button {
            min-height: 48px;
        }

        .admin-chat-attach {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            color: var(--admin-heading);
            background: #fff;
            cursor: pointer;
        }

        .admin-chat-attach input {
            display: none;
        }

        .admin-chat-attach svg {
            width: 21px;
            height: 21px;
            stroke-width: 2.2;
        }

        .admin-chat-attach.has-file {
            border-color: var(--admin-primary);
            color: var(--admin-primary);
            background: #fff4e8;
        }

        .admin-chat-selected-file {
            display: none;
            grid-column: 1 / -1;
            margin: -2px 0 0 58px;
            color: var(--admin-muted);
            font-size: 12px;
        }

        .admin-chat-selected-file.is-visible {
            display: block;
        }

        .admin-chat-feedback {
            display: none;
            padding: 8px 14px 0;
            color: var(--admin-danger);
        }

        .admin-chat-feedback.is-visible {
            display: block;
        }

        @media (max-width: 640px) {
            .admin-chat-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .admin-chat-messages {
                min-height: 330px;
                padding: 14px;
            }

            .admin-chat-bubble {
                max-width: 88%;
            }

            .admin-chat-form {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="admin-chat-heading">
        <div>
            <h1>Chat dengan {{ $conversation->user->name }}</h1>
            <p class="muted" style="margin:0;">{{ $conversation->user->email }}</p>
        </div>
        <a class="btn-primary" href="{{ route('admin.chat.index') }}">Kembali ke Daftar Chat</a>
    </div>

    <section class="card admin-chat-shell">
        <div class="admin-chat-user">
            <strong>{{ $conversation->user->name }}</strong>
            <span class="muted">{{ $conversation->user->email }}</span>
        </div>

        <div class="admin-chat-messages" id="admin-chat-messages">
            <div class="admin-chat-empty {{ $messages->isNotEmpty() ? 'is-hidden' : '' }}" id="admin-chat-empty">
                Belum ada pesan dalam percakapan ini.
            </div>

            @foreach($messages as $message)
                <div class="admin-chat-message {{ (int) $message->sender_id === (int) auth()->id() ? 'is-mine' : '' }}" data-message-id="{{ $message->id }}">
                    <div class="admin-chat-bubble">
                        <span class="admin-chat-sender">{{ $message->sender->is_admin ? 'Admin Sejucara' : $message->sender->name }}</span>
                        @if($message->message !== '')
                            <p class="admin-chat-text">{{ $message->message }}</p>
                        @endif
                        @if($message->image_path)
                            <a href="{{ Storage::url($message->image_path) }}" target="_blank" rel="noopener">
                                <img class="admin-chat-image" src="{{ Storage::url($message->image_path) }}" alt="Lampiran foto chat">
                            </a>
                        @endif
                        <span class="admin-chat-time">{{ $message->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="admin-chat-feedback" id="admin-chat-feedback"></div>
        <form class="admin-chat-form" id="admin-chat-form" action="{{ route('admin.chat.store', $conversation) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="admin-chat-attach" id="admin-chat-attach" title="Tambahkan foto">
                <input type="file" name="image" id="admin-chat-image" accept="image/png,image/jpeg,image/webp">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="m3 15 4-4a2 2 0 0 1 2.8 0L15 16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="m14 15 2-2a2 2 0 0 1 2.8 0L21 15" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 9h.01" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </label>
            <textarea name="message" id="admin-chat-input" maxlength="2000" placeholder="Tulis balasan untuk pelanggan..."></textarea>
            <button class="btn-primary" type="submit">Kirim Balasan</button>
            <div class="admin-chat-selected-file" id="admin-chat-selected-file"></div>
        </form>
    </section>

    <script>
        (() => {
            const box = document.getElementById('admin-chat-messages');
            const form = document.getElementById('admin-chat-form');
            const input = document.getElementById('admin-chat-input');
            const imageInput = document.getElementById('admin-chat-image');
            const attach = document.getElementById('admin-chat-attach');
            const selectedFile = document.getElementById('admin-chat-selected-file');
            const feedback = document.getElementById('admin-chat-feedback');
            const empty = document.getElementById('admin-chat-empty');
            let lastId = Number(box.querySelector('[data-message-id]:last-of-type')?.dataset.messageId || 0);
            let polling = false;

            const scrollToBottom = (behavior = 'auto') => {
                box.scrollTo({ top: box.scrollHeight, behavior });
            };

            const appendMessage = (message) => {
                if (box.querySelector(`[data-message-id="${message.id}"]`)) {
                    return;
                }

                empty?.classList.add('is-hidden');
                const row = document.createElement('div');
                row.className = `admin-chat-message${message.is_mine ? ' is-mine' : ''}`;
                row.dataset.messageId = message.id;

                const bubble = document.createElement('div');
                bubble.className = 'admin-chat-bubble';

                const sender = document.createElement('span');
                sender.className = 'admin-chat-sender';
                sender.textContent = message.sender_name;

                if (message.message) {
                    const text = document.createElement('p');
                    text.className = 'admin-chat-text';
                    text.textContent = message.message;
                    bubble.appendChild(text);
                }

                if (message.image_url) {
                    const link = document.createElement('a');
                    link.href = message.image_url;
                    link.target = '_blank';
                    link.rel = 'noopener';

                    const image = document.createElement('img');
                    image.className = 'admin-chat-image';
                    image.src = message.image_url;
                    image.alt = 'Lampiran foto chat';

                    link.appendChild(image);
                    bubble.appendChild(link);
                }

                const time = document.createElement('span');
                time.className = 'admin-chat-time';
                time.textContent = message.time;

                bubble.prepend(sender);
                bubble.appendChild(time);
                row.appendChild(bubble);
                box.appendChild(row);
                lastId = Math.max(lastId, Number(message.id));
                scrollToBottom('smooth');
            };

            const showError = (message) => {
                feedback.textContent = message;
                feedback.classList.add('is-visible');
            };

            imageInput.addEventListener('change', () => {
                const file = imageInput.files[0];
                attach.classList.toggle('has-file', Boolean(file));
                selectedFile.textContent = file ? `Foto dipilih: ${file.name}` : '';
                selectedFile.classList.toggle('is-visible', Boolean(file));
            });

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const button = form.querySelector('button');
                feedback.classList.remove('is-visible');

                if (!input.value.trim() && imageInput.files.length === 0) {
                    showError('Tulis balasan atau tambahkan foto terlebih dahulu.');
                    return;
                }

                button.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: new FormData(form),
                    });

                    if (!response.ok) {
                        const data = await response.json().catch(() => null);
                        const message = data?.message || Object.values(data?.errors || {})[0]?.[0] || 'Balasan belum berhasil dikirim. Silakan coba lagi.';
                        throw new Error(message);
                    }

                    appendMessage(await response.json());
                    form.reset();
                    attach.classList.remove('has-file');
                    selectedFile.textContent = '';
                    selectedFile.classList.remove('is-visible');
                    input.focus();
                } catch (error) {
                    showError(error.message);
                } finally {
                    button.disabled = false;
                }
            });

            const poll = async () => {
                if (polling || document.hidden) {
                    return;
                }

                polling = true;
                try {
                    const response = await fetch(`{{ route('admin.chat.messages', $conversation) }}?after=${lastId}`, {
                        headers: { 'Accept': 'application/json' },
                    });

                    if (response.ok) {
                        const data = await response.json();
                        data.messages.forEach(appendMessage);
                    }
                } finally {
                    polling = false;
                }
            };

            scrollToBottom();
            window.setInterval(poll, 4000);
        })();
    </script>
@endsection
