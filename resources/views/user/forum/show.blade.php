@extends('layouts.app')

@section('content')
    @php
        $hasAdminReply = $thread->replies->contains(fn ($reply) => $reply->user->is_admin);
    @endphp

    <style>
        .forum-detail-page {
            display: grid;
            gap: 16px;
        }

        .forum-detail-header {
            background: var(--surface);
            border-radius: 16px;
        }

        .forum-detail-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            flex-wrap: wrap;
        }

        .forum-detail-header h1 {
            margin: 10px 0 8px;
            font-size: clamp(28px, 3.2vw, 38px);
            line-height: 1.18;
        }

        .forum-meta-row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .forum-status {
            display: inline-flex;
            align-items: center;
            padding: 4px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
        }

        .forum-status.answered {
            background: #e7f6e8;
            color: #23612c;
        }

        .forum-status.waiting {
            background: #fff0d2;
            color: #87500f;
        }

        .forum-question-body {
            margin: 14px 0 0;
            padding: 15px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid var(--border);
            line-height: 1.75;
            white-space: pre-line;
        }

        .forum-attachment {
            margin-top: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
        }

        .forum-attachment img {
            width: 100%;
            max-height: 320px;
            display: block;
            object-fit: contain;
            background: #fff;
        }

        .forum-back {
            border-radius: 10px;
            white-space: nowrap;
        }

        .forum-section-title {
            margin: 0 0 4px;
        }

        .forum-replies {
            display: grid;
            gap: 12px;
        }

        .forum-reply {
            display: grid;
            grid-template-columns: 42px 1fr;
            gap: 12px;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fffaf2;
        }

        .forum-reply.admin-reply {
            border-color: #d48a4d;
            background: #fff3e4;
        }

        .forum-avatar {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #8b3707;
            color: #fff7e8;
            font-weight: 900;
            text-transform: uppercase;
        }

        .forum-reply.admin-reply .forum-avatar {
            background: var(--primary);
        }

        .forum-reply-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 7px;
        }

        .forum-role-badge {
            display: inline-flex;
            padding: 4px 9px;
            border-radius: 999px;
            background: #f0e1ce;
            color: #684631;
            font-size: 12px;
            font-weight: 900;
        }

        .forum-role-badge.admin {
            background: #8b3707;
            color: #fff7e8;
        }

        .forum-reply-body {
            margin: 0;
            color: #433326;
            line-height: 1.7;
            white-space: pre-line;
        }

        .forum-reply-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .forum-like-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #e3c49b;
            border-radius: 999px;
            background: #fffaf2;
            color: #6b4529;
            padding: 7px 11px;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
        }

        .forum-like-button.is-liked {
            background: #fff0d2;
            border-color: #d48a4d;
            color: var(--primary-dark);
        }

        .forum-like-icon {
            width: 15px;
            height: 15px;
            fill: currentColor;
        }

        .forum-reply-empty {
            padding: 16px;
            text-align: left;
        }

        .forum-reply-form textarea {
            min-height: 128px;
            resize: vertical;
        }

        .forum-file-hint {
            margin-top: 6px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .forum-form-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(240px, 0.36fr);
            gap: 12px;
            align-items: start;
        }

        .forum-form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 12px;
        }

        @media (max-width: 760px) {
            .forum-form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            .forum-reply {
                grid-template-columns: 1fr;
            }

            .forum-avatar {
                width: 38px;
                height: 38px;
            }
        }
    </style>

    <div class="forum-detail-page">
        <section class="card forum-detail-header">
            <div class="forum-detail-top">
                <div>
                    <div class="forum-meta-row">
                        @if($hasAdminReply)
                            <span class="forum-status answered">Sudah dijawab admin</span>
                        @else
                            <span class="forum-status waiting">Menunggu admin</span>
                        @endif
                        <span class="badge">Diskusi Cascara</span>
                        <span class="muted">{{ $thread->replies->count() }} balasan</span>
                    </div>

                    <h1>{{ $thread->title }}</h1>
                    <p class="muted" style="margin:0;">
                        Ditanyakan oleh {{ $thread->user->name }} pada {{ $thread->created_at->translatedFormat('d M Y H:i') }}
                    </p>
                </div>

                <a class="btn-primary forum-back" href="{{ route('forum.index') }}">Kembali ke Forum</a>
            </div>

            <div class="forum-question-body">{{ $thread->body }}</div>
            @if($thread->image_path)
                <div class="forum-attachment">
                    <img src="{{ asset('storage/'.$thread->image_path) }}" alt="Lampiran foto diskusi">
                </div>
            @endif
        </section>

        <section>
            <h2 class="forum-section-title">Balasan Diskusi</h2>
            <p class="muted" style="margin-top:0;">Jawaban admin diberi label khusus sebagai tanggapan resmi Sejucara.</p>

            <div class="forum-replies">
                @forelse($thread->replies as $reply)
                    <article class="forum-reply {{ $reply->user->is_admin ? 'admin-reply' : '' }}">
                        <div class="forum-avatar" aria-hidden="true">{{ Str::substr($reply->user->name, 0, 1) }}</div>
                        <div>
                            <div class="forum-reply-meta">
                                <div>
                                    <strong>{{ $reply->user->name }}</strong>
                                    <span class="muted">- {{ $reply->created_at->translatedFormat('d M Y H:i') }}</span>
                                </div>
                                <span class="forum-role-badge {{ $reply->user->is_admin ? 'admin' : '' }}">
                                    {{ $reply->user->is_admin ? 'Jawaban Admin' : 'Pengguna' }}
                                </span>
                            </div>
                            <p class="forum-reply-body">{{ $reply->body }}</p>
                            @if($reply->image_path)
                                <div class="forum-attachment">
                                    <img src="{{ asset('storage/'.$reply->image_path) }}" alt="Lampiran foto balasan">
                                </div>
                            @endif
                            <div class="forum-reply-actions">
                                @auth
                                    @php
                                        $isLiked = $reply->likes->isNotEmpty();
                                    @endphp
                                    <form action="{{ route('forum.reply.like', $reply) }}" method="POST">
                                        @csrf
                                        <button class="forum-like-button {{ $isLiked ? 'is-liked' : '' }}" type="submit" aria-label="{{ $isLiked ? 'Batalkan like balasan' : 'Like balasan' }}">
                                            <svg class="forum-like-icon" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M12 21.3 10.6 20C5.4 15.3 2 12.2 2 8.4 2 5.3 4.4 3 7.5 3c1.7 0 3.4.8 4.5 2.1C13.1 3.8 14.8 3 16.5 3 19.6 3 22 5.3 22 8.4c0 3.8-3.4 6.9-8.6 11.6L12 21.3z"/>
                                            </svg>
                                            {{ $isLiked ? 'Disukai' : 'Suka' }}
                                        </button>
                                    </form>
                                @endauth
                                <span class="muted">{{ $reply->likes_count }} suka</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="card forum-reply-empty">
                        <h3 style="margin-top:0;">Belum ada balasan</h3>
                        <p class="muted" style="margin-bottom:0;">Diskusi ini masih menunggu tanggapan pertama dari pengguna atau admin.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @auth
            <section class="card forum-reply-form">
                <h3 style="margin-top:0;">Tambahkan Balasan</h3>
                <p class="muted">Tulis jawaban, pengalaman, atau pertanyaan lanjutan yang masih sesuai topik.</p>
                <form action="{{ route('forum.reply.store', $thread) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="forum-form-grid">
                        <div class="field">
                            <label for="body">Isi Balasan</label>
                            <textarea id="body" name="body" rows="5" placeholder="Bagikan jawaban atau pengalaman kamu di sini." required>{{ old('body') }}</textarea>
                        </div>
                        <div class="field">
                            <label for="image">Lampiran Foto <span class="muted">(opsional)</span></label>
                            <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp">
                            <div class="forum-file-hint">Tambahkan foto jika membantu menjelaskan jawaban. Maksimal 2 MB.</div>
                        </div>
                    </div>
                    <div class="forum-form-actions">
                        <button class="btn-primary" type="submit">Kirim Balasan</button>
                    </div>
                </form>
            </section>
        @else
            <section class="card">
                <h3 style="margin-top:0;">Ikut Berdiskusi</h3>
                <p class="muted">Login untuk membalas diskusi ini.</p>
                <a class="btn-primary" href="{{ route('login') }}">Login</a>
            </section>
        @endauth
    </div>
@endsection
