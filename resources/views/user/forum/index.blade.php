@extends('layouts.app')

@section('content')
    <style>
        .forum-page {
            display: grid;
            gap: 16px;
        }

        .forum-hero {
            background: var(--surface);
            text-align: center;
            padding-top: 28px;
            padding-bottom: 28px;
        }

        .forum-hero h1 {
            margin: 0 0 8px;
            font-size: clamp(28px, 3.2vw, 38px);
            line-height: 1.2;
        }

        .forum-hero-copy {
            max-width: 780px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.68;
            margin-bottom: 0;
        }

        .forum-section-header {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 12px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .forum-section-header h2,
        .forum-form-card h3 {
            margin: 0;
        }

        .forum-form-card {
            border-radius: 12px;
        }

        .forum-form-card form {
            display: grid;
            grid-template-columns: minmax(0, 0.82fr) minmax(0, 1.18fr);
            gap: 12px;
            align-items: start;
            margin-top: 12px;
        }

        .forum-form-card .field {
            margin-bottom: 0;
        }

        .forum-form-card .field-wide {
            grid-row: span 2;
        }

        .forum-form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 12px;
        }

        .forum-form-card textarea {
            min-height: 118px;
            resize: vertical;
        }

        .forum-thread-list {
            display: grid;
            gap: 12px;
        }

        .forum-thread-card {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: start;
            border-radius: 12px;
        }

        .forum-thread-meta {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .forum-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
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

        .forum-thread-card h3 {
            margin: 0 0 8px;
            font-size: 20px;
            line-height: 1.25;
            font-family: "Nunito", "Segoe UI", sans-serif;
            font-weight: 900;
            color: var(--text);
        }

        .forum-thread-card p {
            line-height: 1.65;
        }

        .forum-thread-action {
            align-self: center;
            white-space: nowrap;
            border-radius: 10px;
        }

        .forum-file-hint {
            margin-top: 6px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .forum-empty {
            text-align: center;
            padding: 34px 18px;
        }

        @media (max-width: 980px) {
            .forum-form-card form {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 620px) {
            .forum-thread-card {
                grid-template-columns: 1fr;
            }

            .forum-thread-action {
                justify-self: start;
            }
        }
    </style>

    <div class="forum-page">
        <section class="card forum-hero">
            <div>
                <h1>Forum Diskusi Cascara</h1>
                <p class="muted forum-hero-copy">
                    Forum ini digunakan sebagai media komunikasi dua arah antara pembeli, pengunjung, dan admin Sejucara.
                    Pengguna dapat bertanya seputar produk, pemesanan, pengiriman, cara seduh, serta memberi masukan untuk pengembangan sistem.
                </p>
            </div>
        </section>

        <section class="card forum-form-card">
            @auth
                @if(!auth()->user()->is_admin)
                    <h3>Buat Pertanyaan Baru</h3>
                    <p class="muted" style="line-height:1.65; margin-bottom:0;">Tulis pertanyaan seputar produk, pemesanan, cara seduh, atau saran untuk Sejucara.</p>
                    <form action="{{ route('forum.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <div class="field">
                                <label for="title">Judul Pertanyaan</label>
                                <input id="title" name="title" type="text" value="{{ old('title') }}" placeholder="Contoh: Berapa lama cascara diseduh?" required>
                            </div>
                            <div class="field" style="margin-top:12px;">
                                <label for="image">Lampiran Foto <span class="muted">(opsional)</span></label>
                                <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp">
                                <div class="forum-file-hint">Maksimal 2 MB. Gunakan jika foto membantu menjelaskan pertanyaan.</div>
                            </div>
                        </div>
                        <div class="field field-wide">
                            <label for="body">Isi Pertanyaan</label>
                            <textarea id="body" name="body" rows="5" placeholder="Tuliskan pertanyaan, pengalaman, atau saran kamu di sini." required>{{ old('body') }}</textarea>
                            <div class="forum-form-actions">
                                <button class="btn-primary" type="submit">Posting Pertanyaan</button>
                            </div>
                        </div>
                    </form>
                @else
                    <h3>Forum Diskusi</h3>
                    <p class="muted">Admin dapat membalas diskusi dari halaman detail atau mengelola forum melalui dashboard admin.</p>
                    <a class="btn-primary" href="{{ route('admin.forum.index') }}">Moderasi Forum</a>
                @endif
            @else
                <h3>Ikut Berdiskusi</h3>
                <p class="muted" style="line-height:1.65;">Login terlebih dahulu untuk membuat pertanyaan atau membalas diskusi.</p>
                <a class="btn-primary" href="{{ route('login') }}">Login</a>
            @endauth
        </section>

        <section>
            <div class="forum-section-header">
                <div>
                    <h2>Diskusi Terbaru</h2>
                    <p class="muted" style="margin:4px 0 0;">Label status menunjukkan apakah diskusi sudah mendapat jawaban admin.</p>
                </div>
            </div>

            <div class="forum-thread-list">
                @forelse($threads as $thread)
                    <article class="card forum-thread-card">
                        <div>
                            <div class="forum-thread-meta">
                                @if($thread->has_admin_reply)
                                    <span class="forum-status answered">Sudah dijawab admin</span>
                                @else
                                    <span class="forum-status waiting">Menunggu admin</span>
                                @endif
                                <span class="badge">{{ $thread->replies_count }} balasan</span>
                                <span class="muted">Oleh {{ $thread->user->name }}</span>
                                <span class="muted">{{ $thread->created_at->translatedFormat('d M Y H:i') }}</span>
                            </div>

                            <h3>{{ $thread->title }}</h3>
                            <p class="muted" style="margin-bottom:0;">{{ Str::limit($thread->body, 170) }}</p>
                        </div>
                        <a class="btn-primary forum-thread-action" href="{{ route('forum.show', $thread) }}">Buka Diskusi</a>
                    </article>
                @empty
                    <div class="card forum-empty">
                        <h3 style="margin-top:0;">Belum ada diskusi</h3>
                        <p class="muted" style="margin-bottom:0;">Jadilah pengguna pertama yang membuka pertanyaan di forum Sejucara.</p>
                    </div>
                @endforelse

                <div style="margin-top: 4px;">{{ $threads->links() }}</div>
            </div>
        </section>
    </div>
@endsection
