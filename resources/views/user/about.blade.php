@extends('layouts.app')

@section('content')
    <style>
        .about-hero-media {
            height: 420px;
            min-height: 420px;
        }

        .about-hero-photo {
            object-fit: cover;
            object-position: center 45%;
        }

        @media (max-width: 860px) {
            .about-hero-media {
                height: 340px;
                min-height: 340px;
            }
        }
    </style>

    <section class="card about-banner">
        <img
            class="about-banner-photo"
            src="{{ asset('image/pouch2.jpg') }}"
            alt="Banner Tentang Sejucara"
        >
        <div class="about-banner-overlay">
            <span class="about-banner-tag">Cerita Sejucara</span>
            <h2 class="about-banner-title">Mengenalkan Cascara Melalui Produk dan Edukasi Digital</h2>
        </div>
    </section>

    <section class="card about-hero">
        <div class="about-hero-content">
            <span class="badge">Tentang Sejucara</span>
            <h1>Detail Tentang Kami</h1>
            <p class="about-lead">
                Sejucara merupakan usaha yang menghadirkan produk teh cascara, yaitu minuman berbahan dasar
                kulit buah kopi yang dikeringkan dan diolah agar memiliki nilai manfaat serta nilai jual.
            </p>
            <p class="muted">
                Melalui website ini, Sejucara menyediakan informasi produk, artikel edukasi, forum diskusi,
                dan layanan pemesanan online agar konsumen lebih mudah mengenal cascara, memilih produk,
                serta berinteraksi langsung dengan pihak usaha.
            </p>
            <p class="muted">
                Pengembangan website ini juga menjadi sarana untuk memperluas pemasaran Sejucara secara digital,
                sehingga produk dapat dijangkau oleh calon konsumen di luar area penjualan langsung.
            </p>
            <div class="actions">
                <a class="btn-primary" href="{{ route('products.index') }}">Lihat Produk</a>
                <a class="btn-link about-link" href="{{ route('contact') }}">Hubungi Kami</a>
            </div>
        </div>
        <div class="about-hero-media">
            <img
                class="about-hero-photo"
                src="{{ asset('image/ceritasc.jpg') }}"
                alt="Produk cascara Sejucara"
            >
        </div>
    </section>

    <section class="about-highlights">
        <article class="about-highlight">
            <h3>Bahan Pilihan</h3>
            <p class="muted">Dipilih dari mitra tepercaya untuk menjaga aroma dan rasa terbaik.</p>
        </article>
        <article class="about-highlight">
            <h3>Edukasi Ramah Pemula</h3>
            <p class="muted">Artikel dan panduan disajikan singkat, jelas, dan mudah dipraktikkan.</p>
        </article>
        <article class="about-highlight">
            <h3>Layanan Responsif</h3>
            <p class="muted">Tim kami siap membantu kebutuhan produk dan informasi Anda.</p>
        </article>
    </section>

    <section class="card about-section">
        <h2>Visi dan Misi Kami</h2>
        <div class="about-split">
            <article class="about-panel">
                <h3 class="about-panel-title">
                    Visi
                </h3>
                <p>
                    Menjadi pelopor minuman cascara yang mendukung gaya hidup sehat, ramah lingkungan,
                    dan meningkatkan kesejahteraan petani lokal.
                </p>
            </article>
            <article class="about-panel">
                <h3 class="about-panel-title">
                    Misi
                </h3>
                <ul class="about-mission-list">
                    <li>Mengolah limbah kulit kopi menjadi produk bernilai tinggi.</li>
                    <li>Memberdayakan petani lokal secara berkelanjutan.</li>
                    <li>Mengedukasi masyarakat tentang manfaat cascara.</li>
                    <li>Menghadirkan cita rasa alami dengan kualitas terbaik.</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="about-section about-benefits-section">
        <h2>Kenapa Belanja di Sejucara?</h2>
        <p class="about-benefits-copy">
            Belanja melalui website Sejucara membuat konsumen dapat melihat katalog produk, membaca informasi
            terkait cascara, berdiskusi melalui forum, melakukan pemesanan, serta memantau status pesanan
            dalam satu sistem yang saling terhubung.
        </p>
    </section>
@endsection
