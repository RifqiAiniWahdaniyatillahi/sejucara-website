@extends('admin.layouts.app')

@section('content')
    <style>
        .forum-admin-attachment {
            margin-top: 10px;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
        }

        .forum-admin-attachment img {
            width: 100%;
            max-height: 280px;
            display: block;
            object-fit: contain;
            background: #fff;
        }

        .forum-like-note {
            display: inline-block;
            margin-top: 8px;
            color: var(--muted);
            font-size: 13px;
        }
    </style>

    <div class="card" style="margin-bottom: 14px;">
        <h1 style="margin-top:0;">{{ $thread->title }}</h1>
        <p class="muted">Ditanyakan oleh {{ $thread->user->name }}</p>
        <p>{{ $thread->body }}</p>
        @if($thread->image_path)
            <div class="forum-admin-attachment">
                <img src="{{ asset('storage/'.$thread->image_path) }}" alt="Lampiran foto thread">
            </div>
        @endif
        <form action="{{ route('admin.forum.destroy', $thread) }}" method="POST" onsubmit="return confirm('Yakin hapus thread ini?');">
            @csrf
            @method('DELETE')
            <button class="btn-danger" type="submit">Hapus Thread</button>
        </form>
    </div>

    <div class="card" style="margin-bottom: 14px;">
        <h3 style="margin-top:0;">Balasan Diskusi</h3>
        @forelse($thread->replies as $reply)
            <div style="padding: 10px 0; border-bottom: 1px solid var(--border);">
                <div class="actions" style="justify-content: space-between;">
                    <strong>{{ $reply->user->name }}</strong>
                    <form action="{{ route('admin.forum.reply.destroy', [$thread, $reply]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn-danger" type="submit">Hapus Balasan</button>
                    </form>
                </div>
                <p style="margin: 6px 0 0;">{{ $reply->body }}</p>
                @if($reply->image_path)
                    <div class="forum-admin-attachment">
                        <img src="{{ asset('storage/'.$reply->image_path) }}" alt="Lampiran foto balasan">
                    </div>
                @endif
                <span class="forum-like-note">{{ $reply->likes_count }} suka dari pengguna</span>
            </div>
        @empty
            <p class="muted">Belum ada balasan.</p>
        @endforelse
    </div>

    <div class="card">
        <h3 style="margin-top:0;">Balas Sebagai Admin</h3>
        <form action="{{ route('admin.forum.reply.store', $thread) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="field">
                <label for="body">Isi Balasan</label>
                <textarea id="body" name="body" rows="4" required>{{ old('body') }}</textarea>
            </div>
            <div class="field">
                <label for="image">Lampiran Foto <span class="muted">(opsional)</span></label>
                <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp">
            </div>
            <button class="btn-primary" type="submit">Kirim Balasan</button>
        </form>
    </div>
@endsection
