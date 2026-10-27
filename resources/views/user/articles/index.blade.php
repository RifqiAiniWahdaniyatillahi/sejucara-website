@extends('layouts.app')

@section('content')
    <style>
        .articles-listing { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
        @media (min-width: 1024px) { .articles-listing { grid-template-columns: repeat(3, 1fr); } }
        .article-card { display: flex; flex-direction: column; }
        .article-image-wrapper { width: 100%; height: 180px; overflow: hidden; border-radius: 8px 8px 0 0; }
        .article-image-wrapper img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .article-card-content { padding: 16px; display: flex; flex-direction: column; flex-grow: 1; }
        .article-card h3 { font-size: 18px; margin: 0 0 8px; min-height: 56px; }
        .article-date {
            display: inline-flex;
            width: fit-content;
            margin-bottom: 10px;
            padding: 5px 9px;
            border-radius: 999px;
            background: #fff0d8;
            color: #7a421d;
            font-size: 12px;
            font-weight: 800;
        }
        .article-card p { flex-grow: 1; margin-bottom: 12px; }
        .article-card .btn-primary { align-self: flex-start; margin-top: auto; }
    </style>

    <h1>Informasi Artikel Cascara</h1>
    <p class="muted">Artikel seputar manfaat, cara seduh, dan pengetahuan tentang cascara.</p>

    <div class="articles-listing">
        @forelse($articles as $article)
            <article class="card article-card">
                <div class="article-image-wrapper">
                    <a href="{{ route('articles.show', $article->slug) }}" class="article-link">
                        <img class="article-image" src="{{ $article->imageUrl() }}" alt="{{ $article->title }}">
                    </a>
                </div>
                <div class="article-card-content">
                    <span class="article-date">Rilis: {{ $article->created_at->translatedFormat('d F Y') }}</span>
                    <h3>{{ $article->title }}</h3>
                    <p class="muted">{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 100) }}</p>
                    <a class="btn-primary" href="{{ route('articles.show', $article->slug) }}">Baca Selengkapnya</a>
                </div>
            </article>
        @empty
            <p class="muted">Artikel belum tersedia.</p>
        @endforelse
    </div>

    <div style="margin-top: 14px;">{{ $articles->links() }}</div>
@endsection
