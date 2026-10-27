@extends('layouts.app')

@section('content')
    <style>
        .product-image-wrapper { display: block; padding: 12px; width: 100%; min-height: 520px; overflow: hidden; border-radius: 8px; }
        .product-image { width: 100%; height: 100%; object-fit: cover; display: block; }
        .product-gallery { display: grid; gap: 10px; }
        .product-gallery-main {
            position: relative;
            min-height: 520px;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
        }
        .product-gallery-main .product-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            padding: 12px;
            object-fit: contain;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.18s ease;
        }
        .product-gallery-main .product-image.is-active {
            opacity: 1;
            pointer-events: auto;
        }
        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: rgba(255, 250, 242, 0.94);
            color: var(--primary-dark);
            font-size: 24px;
            font-weight: 900;
            line-height: 1;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .gallery-nav.prev { left: 12px; }
        .gallery-nav.next { right: 12px; }
        .gallery-nav:hover { background: #fff2de; }
        .product-gallery-thumbs { display: flex; gap: 8px; flex-wrap: wrap; }
        .gallery-thumb {
            width: 72px;
            height: 72px;
            padding: 5px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
        }
        .gallery-thumb.is-active {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(201, 106, 49, 0.16);
        }
        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        .product-category-label {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            padding: 7px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 10px;
            background: #fff0d8;
            color: #6a3616;
        }
        .product-category-label.is-ready {
            background: #e6f7e8;
            color: #245b2d;
        }
        .product-rating-summary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin: 0 0 10px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #fff4e3;
            color: #7a421d;
            font-weight: 900;
        }
        .rating-stars {
            display: inline-flex;
            gap: 1px;
            color: #f59e0b;
            letter-spacing: 0;
            white-space: nowrap;
        }
        .rating-count {
            color: #7f624c;
            font-size: 13px;
            font-weight: 800;
        }
        .review-section {
            margin-top: 18px;
            display: grid;
            grid-template-columns: minmax(0, 380px) 1fr;
            gap: 16px;
            align-items: start;
        }
        .review-form,
        .review-list {
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fffaf2;
        }
        .rating-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin: 8px 0 12px;
        }
        .rating-options label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 40px;
            padding: 0 10px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            font-weight: 900;
            color: #b45309;
            transition: border-color 0.15s ease, background 0.15s ease, transform 0.15s ease;
        }
        .rating-options label:hover {
            border-color: #f59e0b;
            background: #fff8e8;
            transform: translateY(-1px);
        }
        .rating-options input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        .rating-options label:has(input:checked) {
            border-color: #f59e0b;
            background: #fff3d6;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.14);
        }
        .review-form textarea {
            width: 100%;
            min-height: 110px;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 11px 12px;
            background: #fff;
            resize: vertical;
        }
        .review-item {
            padding: 14px 0;
            border-bottom: 1px solid #efd8b8;
        }
        .review-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }
        .review-meta {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 6px;
            font-weight: 900;
        }
        .review-stars {
            color: #d97722;
            letter-spacing: 1px;
            white-space: nowrap;
        }
        @media (max-width: 1200px) {
            .product-image-wrapper,
            .product-gallery-main { min-height: 440px; }
        }
        @media (max-width: 800px) {
            .product-image-wrapper,
            .product-gallery-main { min-height: 320px; }
        }

        /* Mobile / responsive adjustments */
        @media (max-width: 900px) {
            .product-detail-grid { grid-template-columns: 1fr !important; gap: 12px !important; }
            .review-section { grid-template-columns: 1fr; }
            .product-image-wrapper,
            .product-gallery-main { min-height: 280px !important; }
            .product-image-wrapper { padding: 8px !important; }
            .product-info { padding: 8px 6px; }
            .product-info h1 { font-size: 20px; margin-bottom: 6px; }
            .product-info .muted, .product-info p { font-size: 14px; line-height: 1.4; }
            .product-info .actions { display:flex; flex-direction:column; gap:8px; }
            .product-info .actions .btn-primary { width:100%; box-sizing:border-box; }
        }
    </style>
    @php
        $variantMap = [
            'cascara-original' => [
                'name' => 'Just Me Original',
                'description' => 'Cascara original dalam kemasan botol — isi 250ml. Rasa natural dengan sentuhan manis ringan dan aroma buah kering; cocok dinikmati hangat atau dingin sebagai minuman penyegar sehari-hari.',
                'image' => asset('image/botolori2.jpeg.png'),
            ],
            'cascara-lemon' => [
                'name' => 'Lemon Breeze',
                'description' => 'Cascara lemon dalam kemasan botol — isi 250ml. Perpaduan cascara dan lemon menghasilkan aroma citrus yang cerah dan rasa segar; sangat nikmat disajikan dingin dengan es.',
                'image' => asset('image/botollemon2.png'),
            ],
            'cascara-jahe' => [
                'name' => 'Ginger Spice',
                'description' => 'Cascara ginger dalam kemasan botol — isi 250ml. Kombinasi cascara dan jahe memberikan rasa hangat dengan sentuhan pedas yang lembut; ideal diminum hangat untuk menghangatkan badan.',
                'image' => asset('image/botolginger2.jpeg.png'),
            ],
            'cascara-madu' => [
                'name' => 'Madu Glow',
                'description' => 'Cascara dengan sentuhan madu alami — isi 250ml. Manis lembut dari madu menambah body dan aroma floral yang menenangkan; cocok sebagai minuman santai atau pemulihan setelah aktivitas.',
                'image' => asset('image/botolmadu2.jpeg.png'),
            ],
            'cascara-mint' => [
                'name' => 'Mint Fresh',
                'description' => 'Varian dengan sensasi daun mint yang segar — isi 250ml. Rasa mint memberikan aftertaste dingin dan menyegarkan, pas dinikmati dingin pada hari panas.',
                'image' => asset('image/botolmint2.png'),
            ],
            'cascara-pouch' => [
                'name' => 'Pouch Cascara Original 25gr',
                'description' => 'Pouch cascara original — isi 25gr. Pouch praktis ini pas untuk percobaan atau stok kecil di rumah; setiap pouch menghasilkan beberapa cangkir seduhan dengan karakter rasa natural cascara.',
                'image' => asset('image/pouch.png'),
            ],
            'cascara-pouch-rempah' => [
                'name' => 'Pouch Cascara Rempah 25gr',
                'description' => 'Pouch cascara + rempah — isi 25gr. Campuran cascara dengan rempah hangat (seperti kayu manis dan jahe) memberikan rasa kompleks dan aromatik; paling pas disajikan hangat untuk nuansa cozy.',
                'image' => asset('image/pouchrempah3.png'),
            ],
            'cascara-botol' => [
                'name' => 'Botol Cascara Original 20gr',
                'description' => 'Botol cascara original — isi 20gr. Kemasan kecil praktis untuk dicoba atau sebagai hadiah; mudah diseduh dan cocok untuk konsumsi satu orang.',
                'image' => asset('image/botol.png'),
            ],
            'cascara-botol-rempah' => [
                'name' => 'Botol Cascara Rempah 20gr',
                'description' => 'Botol cascara + rempah — isi 20gr. Perpaduan cascara dan rempah menghasilkan aroma hangat dan rasa penuh karakter; nikmati hangat untuk pengalaman terbaik.',
                'image' => asset('image/scrempah.png'),
            ],
        ];

        $variant = $variantMap[$product->slug] ?? [
            'name' => $product->name,
            'description' => $product->description,
            'image' => $product->imageUrl(),
        ];
        $readyDrinkGalleries = [
            'cascara-original' => [
                asset('image/botolori2.jpeg.png'),
                asset('image/oristick.png'),
            ],
            'cascara-lemon' => [
                asset('image/botollemon2.png'),
                asset('image/lemonstick.png'),
            ],
            'cascara-jahe' => [
                asset('image/botolginger2.jpeg.png'),
                asset('image/gingerstick.png'),
            ],
            'cascara-madu' => [
                asset('image/botolmadu2.jpeg.png'),
                asset('image/madustick.png'),
            ],
            'cascara-mint' => [
                asset('image/botolmint2.png'),
                asset('image/mintstick.png'),
            ],
        ];
        $galleryImages = $readyDrinkGalleries[$product->slug] ?? [$variant['image']];
        $categoryLabel = $product->categoryLabel();
        $categoryClass = $categoryLabel === 'Cascara Siap Minum' ? 'is-ready' : '';
        $averageRating = (float) ($product->average_rating ?? 0);
        $roundedRating = (int) round($averageRating);
        $reviewCount = (int) ($product->reviews_count ?? 0);
    @endphp

    <div class="card product-detail-grid" style="display:grid; gap:16px; grid-template-columns: minmax(360px, 1fr) 1fr; align-items: start;">
        <div class="product-gallery" data-product-gallery>
            <div class="product-gallery-main">
                @foreach($galleryImages as $index => $image)
                    <img
                        class="product-image {{ $index === 0 ? 'is-active' : '' }}"
                        src="{{ $image }}"
                        alt="{{ $variant['name'] }} foto {{ $index + 1 }}"
                        data-gallery-image="{{ $index }}"
                    />
                @endforeach

                @if(count($galleryImages) > 1)
                    <button class="gallery-nav prev" type="button" aria-label="Foto sebelumnya" data-gallery-prev>&lsaquo;</button>
                    <button class="gallery-nav next" type="button" aria-label="Foto berikutnya" data-gallery-next>&rsaquo;</button>
                @endif
            </div>

            @if(count($galleryImages) > 1)
                <div class="product-gallery-thumbs" aria-label="Pilihan foto produk">
                    @foreach($galleryImages as $index => $image)
                        <button class="gallery-thumb {{ $index === 0 ? 'is-active' : '' }}" type="button" data-gallery-thumb="{{ $index }}" aria-label="Tampilkan foto {{ $index + 1 }}">
                            <img src="{{ $image }}" alt="">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="product-info">
            <div class="product-category-label {{ $categoryClass }}">{{ $categoryLabel }}</div>
            <h1 style="margin-top:0;">{{ $variant['name'] }}</h1>
            <div class="product-rating-summary">
                <span class="rating-stars">
                    @for($star = 1; $star <= 5; $star++)
                        {{ $star <= $roundedRating ? '★' : '☆' }}
                    @endfor
                </span>
                @if($reviewCount > 0)
                    <span>{{ number_format($averageRating, 1, ',', '.') }}</span>
                    <span class="rating-count">{{ $reviewCount }} ulasan</span>
                @else
                    <span class="rating-count">Belum ada ulasan</span>
                @endif
            </div>
            <p class="muted">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <p class="muted">Stok: {{ $product->stock }}</p>
            <p class="muted">Terjual: {{ (int) ($product->sold_quantity ?? 0) }}</p>
            <p>{{ $variant['description'] }}</p>
            <div class="actions">
                @auth
                    @if(!auth()->user()->is_admin)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="actions">
                            @csrf
                            <input type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1" style="width: 90px;">
                            <button class="btn-primary" type="submit">Tambah ke Keranjang</button>
                        </form>
                    @endif
                @else
                    <a class="btn-primary" href="{{ route('login') }}">Login untuk Belanja</a>
                @endauth

                <a class="btn-primary" href="{{ route('products.index') }}">Kembali ke Katalog</a>
            </div>
        </div>
    </div>

    <section class="review-section">
        <div class="review-form">
            <h2 style="margin-top:0;">Beri Review Produk</h2>
            @auth
                @if(auth()->user()->is_admin)
                    <p class="muted">Admin tidak dapat memberi review produk.</p>
                @elseif($canReview)
                    <form action="{{ route('products.reviews.store', $product) }}" method="POST">
                        @csrf
                        <label style="font-weight:900;">Rating</label>
                        <div class="rating-options">
                            @for($rating = 5; $rating >= 1; $rating--)
                                <label>
                                    <input type="radio" name="rating" value="{{ $rating }}" @checked((int) old('rating', $userReview?->rating ?? 5) === $rating)>
                                    {{ str_repeat('★', $rating) }}
                                </label>
                            @endfor
                        </div>
                        <label for="comment" style="display:block; font-weight:900; margin-bottom:6px;">Ulasan</label>
                        <textarea id="comment" name="comment" maxlength="500" placeholder="Ceritakan pengalaman Anda mencoba produk ini...">{{ old('comment', $userReview?->comment) }}</textarea>
                        <button class="btn-primary" type="submit" style="margin-top:10px;">Simpan Review</button>
                    </form>
                @else
                    <p class="muted">Review dapat diberikan setelah pesanan produk ini diproses oleh admin.</p>
                @endif
            @else
                <p class="muted">Silakan login dan lakukan pembelian untuk memberi review produk.</p>
                <a class="btn-primary" href="{{ route('login') }}">Login</a>
            @endauth
        </div>

        <div class="review-list">
            <h2 style="margin-top:0;">Ulasan Pembeli</h2>
            @forelse($product->reviews->sortByDesc('created_at') as $review)
                <article class="review-item">
                    <div class="review-meta">
                        <span>{{ $review->user?->name ?? 'Pembeli Sejucara' }}</span>
                        <span class="review-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    </div>
                    @if($review->comment)
                        <p style="margin:0;">{{ $review->comment }}</p>
                    @else
                        <p class="muted" style="margin:0;">Pembeli memberikan rating tanpa komentar.</p>
                    @endif
                    <p class="muted" style="margin:6px 0 0;">{{ $review->created_at->format('d M Y') }}</p>
                </article>
            @empty
                <p class="muted" style="margin:0;">Belum ada ulasan untuk produk ini.</p>
            @endforelse
        </div>
    </section>

    <script>
        document.querySelectorAll('[data-product-gallery]').forEach((gallery) => {
            const images = Array.from(gallery.querySelectorAll('[data-gallery-image]'));
            const thumbs = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));
            const prev = gallery.querySelector('[data-gallery-prev]');
            const next = gallery.querySelector('[data-gallery-next]');
            let activeIndex = 0;

            const showImage = (index) => {
                if (!images.length) {
                    return;
                }

                activeIndex = (index + images.length) % images.length;
                images.forEach((image, imageIndex) => image.classList.toggle('is-active', imageIndex === activeIndex));
                thumbs.forEach((thumb, thumbIndex) => thumb.classList.toggle('is-active', thumbIndex === activeIndex));
            };

            thumbs.forEach((thumb, index) => {
                thumb.addEventListener('click', () => showImage(index));
            });

            prev?.addEventListener('click', () => showImage(activeIndex - 1));
            next?.addEventListener('click', () => showImage(activeIndex + 1));
        });
    </script>
@endsection
