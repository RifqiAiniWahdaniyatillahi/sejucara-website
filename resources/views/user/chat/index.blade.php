@extends('layouts.app')

@section('content')
    <style>
        .chat-page {
            max-width: 900px;
            margin: 0 auto;
        }

        .chat-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 14px;
        }

        .chat-heading h1 {
            margin: 0 0 5px;
        }

        .chat-shell {
            padding: 0;
            overflow: hidden;
        }

        .chat-intro {
            padding: 16px 18px;
            border-bottom: 1px solid var(--border);
            background: #fff6e9;
        }

        .chat-intro strong {
            display: block;
            margin-bottom: 3px;
            color: var(--heading);
        }

        .chat-messages {
            height: min(58vh, 540px);
            min-height: 360px;
            overflow-y: auto;
            padding: 20px;
            background: #fffdf9;
        }

        .chat-empty {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--muted);
        }

        .chat-empty strong {
            margin-bottom: 6px;
            color: var(--text);
        }

        .chat-empty.is-hidden {
            display: none !important;
        }

        .chat-message {
            display: flex;
            margin-bottom: 14px;
        }

        .chat-message.is-mine {
            justify-content: flex-end;
        }

        .chat-bubble {
            max-width: min(74%, 580px);
            padding: 10px 13px 8px;
            border: 1px solid var(--border);
            border-radius: 14px 14px 14px 4px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(90, 44, 20, 0.05);
        }

        .chat-message.is-mine .chat-bubble {
            border-color: #c96a31;
            border-radius: 14px 14px 4px 14px;
            background: #c96a31;
            color: #fff;
        }

        .chat-sender {
            display: block;
            margin-bottom: 3px;
            color: var(--heading);
            font-size: 12px;
            font-weight: 900;
        }

        .chat-message.is-mine .chat-sender {
            color: #fff4e8;
        }

        .chat-text {
            margin: 0;
            line-height: 1.5;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .chat-image {
            display: block;
            width: min(260px, 100%);
            max-height: 260px;
            margin-top: 8px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }

        .chat-message:not(.is-mine) .chat-image {
            border-color: var(--border);
        }

        .chat-time {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 11px;
            text-align: right;
        }

        .chat-message.is-mine .chat-time {
            color: #ffe7d4;
        }

        .chat-form {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            gap: 10px;
            align-items: end;
            padding: 14px;
            border-top: 1px solid var(--border);
            background: var(--surface);
        }

        .chat-form textarea {
            min-height: 48px;
            max-height: 130px;
            resize: vertical;
        }

        .chat-form button {
            min-height: 48px;
        }

        .chat-attach {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--heading);
            background: #fff;
            cursor: pointer;
        }

        .chat-attach input {
            display: none;
        }

        .chat-attach svg {
            width: 21px;
            height: 21px;
            stroke-width: 2.2;
        }

        .chat-attach.has-file {
            border-color: #c96a31;
            color: #c96a31;
            background: #fff4e8;
        }

        .chat-selected-file {
            display: none;
            grid-column: 1 / -1;
            margin: -2px 0 0 58px;
            color: var(--muted);
            font-size: 12px;
        }

        .chat-selected-file.is-visible {
            display: block;
        }

        .chat-feedback {
            display: none;
            padding: 8px 14px 0;
            color: var(--danger);
            font-size: 13px;
            background: var(--surface);
        }

        .chat-feedback.is-visible {
            display: block;
        }

        @media (max-width: 640px) {
            .chat-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .chat-messages {
                height: 56vh;
                min-height: 330px;
                padding: 14px;
            }

            .chat-bubble {
                max-width: 88%;
            }

            .chat-form {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="chat-page">
        <div class="chat-heading">
            <div>
                <h1>Chat dengan Admin</h1>
                <p class="muted" style="margin:0;">Tanyakan produk, pembayaran, atau status pesanan kepada admin Sejucara.</p>
            </div>
        </div>

        <section class="card chat-shell">
            <div class="chat-intro">
                <strong>Admin Sejucara</strong>
                <span class="muted">Chatbot akan membantu menjawab pertanyaan umum. Admin tetap dapat membalas saat tersedia.</span>
            </div>

            <div class="chat-messages" id="chat-messages">
                <div class="chat-empty {{ $messages->isNotEmpty() ? 'is-hidden' : '' }}" id="chat-empty">
                    <strong>Belum ada percakapan</strong>
                    <span>Kirim pesan pertama untuk memulai chat dengan admin.</span>
                </div>

                @foreach($messages as $message)
                    <div class="chat-message {{ (int) $message->sender_id === (int) auth()->id() ? 'is-mine' : '' }}" data-message-id="{{ $message->id }}">
                        <div class="chat-bubble">
                            <span class="chat-sender">{{ $message->sender->is_admin ? 'Admin Sejucara' : $message->sender->name }}</span>
                            @if($message->message !== '')
                                <p class="chat-text">{{ $message->message }}</p>
                            @endif
                            @if($message->image_path)
                                <a href="{{ Storage::url($message->image_path) }}" target="_blank" rel="noopener">
                                    <img class="chat-image" src="{{ Storage::url($message->image_path) }}" alt="Lampiran foto chat">
                                </a>
                            @endif
                            <span class="chat-time">{{ $message->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="chat-feedback" id="chat-feedback"></div>
            <form class="chat-form" id="chat-form" action="{{ route('chat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="chat-attach" id="chat-attach" title="Tambahkan foto">
                    <input type="file" name="image" id="chat-image" accept="image/png,image/jpeg,image/webp">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="m3 15 4-4a2 2 0 0 1 2.8 0L15 16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="m14 15 2-2a2 2 0 0 1 2.8 0L21 15" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15 9h.01" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </label>
                <textarea name="message" id="chat-input" maxlength="2000" placeholder="Tulis pesan untuk admin..."></textarea>
                <button class="btn-primary" type="submit">Kirim Pesan</button>
                <div class="chat-selected-file" id="chat-selected-file"></div>
            </form>
        </section>
    </div>

    <script>
        (() => {
            const box = document.getElementById('chat-messages');
            const form = document.getElementById('chat-form');
            const input = document.getElementById('chat-input');
            const imageInput = document.getElementById('chat-image');
            const attach = document.getElementById('chat-attach');
            const selectedFile = document.getElementById('chat-selected-file');
            const feedback = document.getElementById('chat-feedback');
            const empty = document.getElementById('chat-empty');
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
                row.className = `chat-message${message.is_mine ? ' is-mine' : ''}`;
                row.dataset.messageId = message.id;

                const bubble = document.createElement('div');
                bubble.className = 'chat-bubble';

                const sender = document.createElement('span');
                sender.className = 'chat-sender';
                sender.textContent = message.sender_name;

                if (message.message) {
                    const text = document.createElement('p');
                    text.className = 'chat-text';
                    text.textContent = message.message;
                    bubble.appendChild(text);
                }

                if (message.image_url) {
                    const link = document.createElement('a');
                    link.href = message.image_url;
                    link.target = '_blank';
                    link.rel = 'noopener';

                    const image = document.createElement('img');
                    image.className = 'chat-image';
                    image.src = message.image_url;
                    image.alt = 'Lampiran foto chat';

                    link.appendChild(image);
                    bubble.appendChild(link);
                }

                const time = document.createElement('span');
                time.className = 'chat-time';
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
                    showError('Tulis pesan atau tambahkan foto terlebih dahulu.');
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
                        const message = data?.message || Object.values(data?.errors || {})[0]?.[0] || 'Pesan belum berhasil dikirim. Silakan coba lagi.';
                        throw new Error(message);
                    }

                    const data = await response.json();
                    appendMessage(data);

                    if (data.bot_reply) {
                        window.setTimeout(() => appendMessage(data.bot_reply), 450);
                    }

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
                    const response = await fetch(`{{ route('chat.messages') }}?after=${lastId}`, {
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
