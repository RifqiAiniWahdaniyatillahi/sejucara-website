@extends('admin.layouts.app')

@section('content')
    <div class="actions" style="justify-content: space-between; margin-bottom: 12px;">
        <h1 style="margin:0;">Manajemen Artikel</h1>
        <a class="btn-primary" href="{{ route('admin.articles.create') }}">Tambah Artikel</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td>
                            <img src="{{ $article->imageUrl() }}" alt="{{ $article->title }}" style="width: 72px; height: 54px; object-fit: cover; border-radius: 8px; border: 1px solid var(--admin-border);">
                        </td>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->user->name }}</td>
                        <td>
                            <div class="actions">
                                <a class="btn-primary" href="{{ route('admin.articles.edit', $article) }}">Edit</a>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Yakin hapus artikel ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">Belum ada artikel.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 14px;">{{ $articles->links() }}</div>
    </div>
@endsection
