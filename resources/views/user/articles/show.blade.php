@extends('layouts.app')

@section('content')
    <article class="card">
        <span class="badge">Artikel Cascara</span>
        <h1>{{ $article->title }}</h1>
        <p class="muted" style="margin-top:-4px;">Tanggal rilis: {{ $article->created_at->translatedFormat('d F Y') }}</p>
        {{-- hero image will be selected after possible content overrides below --}}
        @if($article->excerpt)
            <p class="muted">{{ $article->excerpt }}</p>
        @endif

        <style>
            /* Normalisasi gaya sub-judul dan strong di isi artikel */
            .article-content h2,
            .article-content h3,
            .article-content h4 {
                color: inherit !important;
                font-weight: 400 !important;
                font-family: inherit !important;
                text-transform: none !important;
            }

            .article-content strong,
            .article-content b {
                color: inherit !important;
                font-weight: 400 !important;
                font-family: inherit !important;
            }

            /* Jika ada gaya warna inline, override agar mengikuti teks biasa */
            .article-content *[style*="color"] {
                color: inherit !important;
            }

            /* Sembunyikan catatan / badge / kontak yang mungkin disisipkan dalam konten */
            .article-content .info-badge,
            .article-content .contact-item,
            .article-content .btn-whatsapp,
            .article-content a[href*="wa.me"],
            .article-content a[href*="whatsapp"],
            .article-content a[href*="instagram.com"],
            .article-content .contact,
            .article-content .social-links {
                display: none !important;
            }

            .article-content {
                line-height: 1.7;
                color: inherit;
                font-family: inherit;
            }

            .article-hero-image {
                width: 100%;
                max-height: 520px;
                object-fit: cover;
                border-radius: 12px;
                display: block;
                background: #fff;
            }

            .article-hero-image.is-contain {
                object-fit: contain;
            }
        </style>

        @php
            $content = $article->content;
            $containImageSlugs = [
                'mengenal-varian-rasa-cascara-dan-cara-memilihnya',
            ];
            $articleHeroClass = in_array($article->slug, $containImageSlugs, true) ? 'is-contain' : '';
            // Jika artikel tertentu perlu override ketersediaan, ganti hanya untuk slug ini
                if(isset($article->slug) && $article->slug === 'sejucara-perkenalkan-cascara-nusantara') {
                    // Ganti seluruh isi artikel untuk slug ini dengan narasi berkelanjutan tanpa poin
                    $content = <<<'HTML'
                <p>Cascara adalah bagian dari buah ceri kopi yang selama bertahun-tahun kurang dimanfaatkan setelah biji kopi dipanen. Baru-baru ini, cascara mendapat perhatian sebagai bahan minuman alternatif yang kaya rasa dan mengandung sejumlah senyawa bioaktif seperti polifenol dan antioksidan. Berbeda dengan kopi seduh, cascara umumnya memiliki kandungan kafein yang lebih rendah, sehingga menawarkan pengalaman minum yang lebih ringan dan cocok untuk penggunaan harian bagi mereka yang ingin mengurangi asupan kafein.</p>

                <p>Untuk aktivitas sehari-hari, cascara menawarkan beberapa manfaat pragmatis. Pertama, sebagai minuman nikmat yang mudah disajikan, cascara membantu menjaga kecukupan asupan cairan dengan rasa yang lebih menarik dibandingkan air putih, sehingga dapat mendukung kebiasaan hidrasi. Kedua, kandungan kafein yang relatif rendah memberi stimulasi ringan yang berguna untuk meningkatkan fokus dan kewaspadaan tanpa efek jittery yang sering dikaitkan dengan kopi kental.</p>

                <p>Selain itu, beberapa studi laboratorium menunjukkan bahwa ekstrak cascara mengandung polifenol yang berpotensi berperan sebagai antioksidan. Meskipun temuan ini menjanjikan, penting dicatat bahwa penelitian klinis pada manusia masih terbatas; oleh karena itu, cascara lebih tepat diposisikan sebagai minuman fungsional pendukung gaya hidup sehat, bukan sebagai obat atau pengganti terapi medis.</p>

                <p>Praktik penggunaan yang umum di kegiatan harian antara lain: menyajikan cascara hangat pada pagi hari sebagai alternatif kopi untuk memulai hari dengan stimulasi ringan; menyajikan cascara dingin berperasan jeruk atau dengan sedikit madu sebagai minuman penyegar di siang hari; atau mengombinasikan cascara dengan susu nabati untuk membuat minuman yang lebih mengenyangkan saat bekerja di rumah. Untuk aktivitas fisik ringan (jalan pagi, yoga, bersepeda santai), cascara bisa menjadi pilihan minuman ringan untuk dinikmati sebelum atau sesudah kegiatan, tetapi untuk olahraga intens yang membutuhkan rehidrasi dan elektrolit, gunakan minuman yang dirancang khusus untuk kebutuhan tersebut.</p>

                <p>Dalam hal keamanan pangan, kualitas bahan baku dan pengolahan sangat menentukan. Cascara harus berasal dari cherry kopi yang dibersihkan, dikeringkan, dan diproses dengan protokol higienis untuk mengurangi risiko kontaminasi mikroba. Produsen yang tepercaya biasanya menerapkan kontrol mutu dan pelabelan yang jelas tentang rekomendasi penyajian dan penyimpanan.</p>

                <p>Resep sederhana untuk penggunaan sehari-hari: seduh 5–8 gram cascara kering per 250 ml air panas (85–95°C) selama 4–6 menit. Untuk versi dingin, seduh seperti biasa lalu dinginkan dan sajikan dengan es serta irisan buah sitrus. Untuk variasi, tambahkan rempah ringan (seperti kayu manis atau jahe), sedikit madu, atau kombinasikan dengan soda sebagai 'cascara tonic' yang menyegarkan.</p>

                <p>Dari sisi keberlanjutan, pemanfaatan cascara meningkatkan nilai ekonomi rantai pasokan kopi dengan mengubah limbah pasca-panen menjadi produk bernilai tambah yang memberi pendapatan tambahan bagi petani. Ini juga mendorong praktik pengelolaan limbah yang lebih ramah lingkungan. Secara keseluruhan, cascara dapat menjadi bagian berguna dari pola konsumsi harian — asalkan digunakan dengan bijak, memperhatikan toleransi pribadi terhadap kafein, serta memilih produk yang diolah dengan standar mutu yang baik.</p>
                HTML;
            }
        @endphp

        <div class="article-hero" style="margin:16px 0 20px;">
            <img class="article-hero-image {{ $articleHeroClass }}" src="{{ $article->imageUrl() }}" alt="{{ $article->title }}">
        </div>

        <div class="article-content">{!! $content !!}</div>
    </article>
@endsection
