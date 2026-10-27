@extends('layouts.app')

@section('content')
    @php
        $heroImage = asset('image/bg.jpg');
        $productImages = [
            'cascara1.jpg',
            'cascara2.jpg',
            'cascara3.jpg',
            'pouch.jpg',
            'pouch1.jpg',
            'pouch2.jpg',
        ];
    @endphp

    <section class="home-hero card">
        <div class="home-hero-content">
            <h1>Segarkan Hari dengan <span class="accent-word">Sejucara</span></h1>
            <p class="muted">
                Platform artikel dan e-commerce teh cascara untuk kamu yang ingin menikmati produk alami
                sekaligus belajar manfaatnya.
            </p>
            <div class="actions">
                <a class="btn-primary" href="{{ route('products.index') }}">Lihat Produk</a>
                <a class="btn-link" href="{{ route('articles.index') }}">Baca Artikel</a>
            </div>
        </div>
        <div class="home-hero-media">
            <img
                class="home-hero-photo"
                src="{{ $heroImage }}"
                alt="Bahan cascara kering"
            >
        </div>
    </section>

    <section class="card home-why">
        <h2>Kenapa memilih sejucara?</h2>
        <div class="home-why-grid">
            <article class="home-feature-box">
                <h3>Produk Alami</h3>
                <p class="muted">Teh cascara dari bahan pilihan tanpa tambahan berbahaya.</p>
            </article>
            <article class="home-feature-box">
                <h3>Artikel Mudah Dipahami</h3>
                <p class="muted">Informasi singkat dan jelas untuk pemula sampai pecinta teh.</p>
            </article>
            <article class="home-feature-box">
                <h3>Komunitas Aktif</h3>
                <p class="muted">Forum diskusi untuk berbagi pengalaman dan rekomendasi produk.</p>
            </article>
        </div>
    </section>

    <section class="card home-story">
        <h2>Cerita Kami</h2>
        <div class="home-story-grid">
            <div class="home-story-media">
                <img
                    class="home-story-photo"
                    src="{{ asset('image/ceritasc2.jpg') }}"
                    alt="Cerita Sejucara"
                >
            </div>
            <div class="home-story-content">
                <p>
                    Sejucara hadir untuk memperkenalkan teh cascara sebagai minuman sehat yang mudah dinikmati.
                    Kami memadukan produk berkualitas, artikel yang ramah pemula, dan forum komunitas untuk
                    mendukung gaya hidup sehat.
                </p>
                <p class="muted">
                    Mulai dari pengenalan cascara, tips penyeduhan, sampai rekomendasi produk terbaik, semuanya
                    tersedia dalam satu platform.
                </p>
                <a class="btn-primary home-story-cta" href="{{ route('about') }}">Detail Tentang Kami</a>
            </div>
        </div>
    </section>

    <section class="home-products">
        <h2>Produk-Produk</h2>
        <div class="grid home-products-grid">
            @forelse($featuredProducts as $product)
                @php
                    // Use only images from public/image so user can manage them easily.
                    $productImage = null;
                    $exts = ['jpg','jpeg','png','webp','gif'];
                    foreach ($exts as $ext) {
                        $p = public_path('image/'.$product->slug.'.'.$ext);
                        if (file_exists($p)) {
                            $productImage = asset('image/'.$product->slug.'.'.$ext);
                            break;
                        }
                    }
                    if (!$productImage) {
                        // try any file that contains the slug
                        $files = glob(public_path('image/*'.$product->slug.'*.{jpg,jpeg,png,webp,gif}'), GLOB_BRACE);
                        if (!empty($files)) {
                            $productImage = asset('image/'.basename($files[0]));
                        }
                    }
                    if (!$productImage) {
                        // fallback to first image in the folder so the user can swap files there
                        $all = glob(public_path('image/*.{jpg,jpeg,png,webp,gif}'), GLOB_BRACE);
                        if (!empty($all)) { $productImage = asset('image/'.basename($all[0])); }
                        else { $productImage = 'https://via.placeholder.com/400x300?text=No+Image'; }
                    }
                @endphp
                <article class="card home-product-card">
                    <img class="product-image" src="{{ $productImage }}" alt="{{ $product->name }}">
                    <h3>{{ $product->name }}</h3>
                    <p class="muted">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <a class="btn-primary" href="{{ route('products.show', $product->slug) }}">Detail Produk</a>
                </article>
            @empty
                <p class="muted">Belum ada produk tersedia.</p>
            @endforelse
        </div>
        <style>
            .home-products .product-cta { text-align: center; margin-top: 18px; }
            .home-products .product-cta .btn-primary { display: inline-flex; margin-top: 6px; }
        </style>
        <div class="product-cta">
            <a class="btn-primary" href="{{ route('products.index') }}">Lihat Semua Produk</a>
        </div>
    </section>

    <!-- Articles Section -->
    <section class="card home-article-section">
        <style>
            .home-article-section .section-header {
                text-align: center;
            }

            .home-article-section .article-cta {
                text-align: center;
                margin-top: 18px;
            }

            .home-article-section .article-cta .btn-primary {
                display: inline-flex;
                margin-top: 6px;
            }
        </style>

        <div class="section-header">
            <h2>Artikel Terbaru</h2>
            <p class="muted">Pelajari lebih lanjut tentang cascara dan tips sehat dari kami</p>
        </div>

        <div class="grid home-articles-grid">
            @forelse($articles as $article)
                @php
                    $img = null;
                    if (!empty($article->image_url)) {
                        $img = $article->image_url;
                    } else {
                        $exts = ['jpg','jpeg','png','webp','gif'];
                        foreach ($exts as $ext) {
                            $p = public_path('image/'.$article->slug.'.'.$ext);
                            if (file_exists($p)) { $img = asset('image/'.$article->slug.'.'.$ext); break; }
                        }
                    }
                    if (!$img) {
                        $files = glob(public_path('image/*.{jpg,jpeg,png,webp,gif}'), GLOB_BRACE);
                        if (!empty($files)) { $first = basename($files[0]); $img = asset('image/'.$first); }
                        else { $img = 'https://via.placeholder.com/800x400?text=No+Image'; }
                    }
                @endphp

                <article class="card home-article-card">
                    <div class="article-image-wrapper">
                        <a href="{{ route('articles.show', $article->slug) }}" class="article-link">
                            <img class="article-image" src="{{ $img }}" alt="{{ $article->title }}">
                        </a>
                    </div>

                    <div class="article-info">
                        <h3 class="article-title">{{ $article->title }}</h3>
                        <p class="article-excerpt">{{ Str::limit($article->excerpt, 100) }}</p>

                        <div class="article-footer">
                            <small class="article-date">{{ $article->created_at->format('d M Y') }}</small>
                        </div>
                    </div>
                </article>
            @empty
                <p class="muted">Belum ada artikel tersedia.</p>
            @endforelse
        </div>

        <div class="article-cta">
            <a class="btn-primary" href="{{ route('articles.index') }}">Lihat Semua Artikel</a>
        </div>
    </section>

@endsection
