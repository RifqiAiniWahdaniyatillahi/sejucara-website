@extends('layouts.app')

@section('content')
    <h1>Katalog Produk Teh Cascara</h1>
    <p class="muted">Daftar produk yang tersedia untuk dibeli.</p>

    <style>
        .catalog-header {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 12px;
            margin: 18px 0 12px;
            flex-wrap: wrap;
        }

        .catalog-header h2 {
            margin: 0;
        }

        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 280px));
            justify-content: center;
            gap: 18px;
            margin: 0;
            padding: 20px;
        }

        .catalog-card {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            min-height: 360px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 14px 16px 16px;
            color: #fff;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            background-color: #f7efe4;
            box-shadow: 0 16px 36px rgba(70, 36, 13, 0.12);
        }

        .catalog-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(33, 20, 12, 0.06) 0%, rgba(33, 20, 12, 0.62) 100%);
        }

        .catalog-card > * {
            position: relative;
            z-index: 1;
        }

        .catalog-tag {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.24);
            backdrop-filter: blur(8px);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .catalog-tag.is-ready {
            background: rgba(219, 247, 221, 0.9);
            color: #245b2d;
        }

        .catalog-tag.is-dry {
            background: rgba(255, 238, 207, 0.92);
            color: #6a3616;
        }

        .catalog-card h3 {
            color: #fff;
            margin: 0 0 8px;
            font-family: "Nunito", sans-serif;
            font-size: 18px;
            line-height: 1.2;
            font-weight: 900;
            letter-spacing: 0;
        }

        .catalog-price {
            display: inline-flex;
            width: fit-content;
            margin-bottom: 8px;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.94);
            color: #5c341d;
            font-weight: 900;
        }

        .catalog-rating {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            gap: 5px;
            margin-bottom: 14px;
            padding: 5px 9px;
            border-radius: 999px;
            background: rgba(255, 250, 242, 0.92);
            color: #6a3616;
            font-size: 11px;
            font-weight: 900;
        }

        .rating-mini {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #7a421d;
            font-size: 12px;
            font-weight: 900;
            line-height: 1;
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
            font-weight: 800;
        }

        .catalog-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .catalog-cart-form {
            margin: 0;
        }

        .catalog-cart-button {
            width: 44px;
            height: 44px;
            border: 1px solid rgba(255, 255, 255, 0.86);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 250, 242, 0.94);
            color: #5c341d;
            cursor: pointer;
            text-decoration: none;
        }

        .catalog-cart-button:hover {
            background: #fff2de;
        }

        .catalog-cart-button svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .recommendation-card {
            margin: 24px 0 0;
            padding: 0;
            overflow: hidden;
        }

        .recommendation-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 22px;
            border-bottom: 1px solid var(--border);
            background: #fffaf3;
        }

        .recommendation-heading h2 {
            margin: 0 0 6px;
        }

        .recommendation-steps {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            padding: 18px 22px 8px;
        }

        .recommendation-step {
            padding: 0;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fff;
            overflow: hidden;
        }

        .recommendation-step strong {
            display: block;
            margin: 0;
            padding: 12px 14px;
            border-bottom: 1px solid #f0d8bf;
            background: #fff7ec;
            color: var(--heading);
        }

        .recommendation-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding: 12px;
        }

        .recommendation-option {
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 8px 11px;
            background: #fff;
            color: var(--text);
            cursor: pointer;
            font-weight: 800;
        }

        .recommendation-option.is-active {
            border-color: var(--primary);
            background: #fff0df;
            color: var(--heading);
        }

        .recommendation-result {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            padding: 0 22px 22px;
        }

        .recommendation-product {
            display: none;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fff;
            text-decoration: none;
            color: inherit;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .recommendation-product:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(90, 44, 20, 0.08);
        }

        .recommendation-product.is-visible {
            display: flex;
        }

        .recommendation-product img {
            width: 68px;
            height: 68px;
            border-radius: 12px;
            object-fit: cover;
            background: #f7efe4;
        }

        .recommendation-product strong {
            display: block;
            margin-bottom: 4px;
            color: var(--heading);
        }

        .recommendation-product span {
            display: block;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.35;
        }

        .recommendation-empty {
            margin: 8px 22px 14px;
            padding: 11px 13px;
            border-radius: 12px;
            background: #fffaf3;
            color: var(--muted);
        }

        @media (max-width: 760px) {
            .recommendation-heading {
                display: block;
            }

            .recommendation-steps,
            .recommendation-result {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @php
        $productNames = [
            'cascara-original' => 'Just Me Original',
            'cascara-lemon' => 'Lemon Breeze',
            'cascara-jahe' => 'Ginger Spice',
            'cascara-madu' => 'Madu Glow',
            'cascara-mint' => 'Mint Fresh',
            'cascara-pouch' => 'Pouch Cascara Original',
            'cascara-pouch-rempah' => 'Pouch Cascara Rempah',
            'cascara-botol' => 'Botol Cascara Original',
            'cascara-botol-rempah' => 'Botol Cascara Rempah',
        ];

        $recommendationMeta = [
            'cascara-original' => ['taste' => 'original', 'type' => 'ready', 'need' => 'daily', 'note' => 'Rasa dasar cascara yang ringan untuk mencoba pertama kali.'],
            'cascara-lemon' => ['taste' => 'fresh', 'type' => 'ready', 'need' => 'daily', 'note' => 'Segar dan cocok untuk yang suka rasa asam ringan.'],
            'cascara-mint' => ['taste' => 'fresh', 'type' => 'ready', 'need' => 'daily', 'note' => 'Sensasi sejuk untuk minuman yang terasa ringan.'],
            'cascara-jahe' => ['taste' => 'warm', 'type' => 'ready', 'need' => 'daily', 'note' => 'Pilihan hangat dengan sentuhan jahe.'],
            'cascara-madu' => ['taste' => 'sweet', 'type' => 'ready', 'need' => 'daily', 'note' => 'Lebih manis dan mudah diterima pembeli baru.'],
            'cascara-pouch' => ['taste' => 'original', 'type' => 'dry', 'need' => 'brew', 'note' => 'Cocok untuk yang ingin menyeduh cascara sendiri.'],
            'cascara-pouch-rempah' => ['taste' => 'warm', 'type' => 'dry', 'need' => 'brew', 'note' => 'Cascara seduh dengan rasa rempah yang lebih kuat.'],
            'cascara-botol' => ['taste' => 'original', 'type' => 'dry', 'need' => 'gift', 'note' => 'Kemasan botol untuk cascara kering yang terlihat rapi.'],
            'cascara-botol-rempah' => ['taste' => 'warm', 'type' => 'dry', 'need' => 'gift', 'note' => 'Kemasan botol dengan karakter rempah.'],
        ];
    @endphp

    <div class="catalog-header">
        <div>
            <h2>Semua Produk</h2>
            @if($search !== '')
                <p class="muted" style="margin:4px 0 0;">Hasil pencarian untuk "{{ $search }}".</p>
            @endif
        </div>

        @if($search !== '')
            <a class="btn-primary" href="{{ route('products.index') }}">Reset Pencarian</a>
        @endif
    </div>

    @if($products->count() > 0)
        <section class="card" style="padding:0;">
            <div class="catalog-grid">
                @foreach($products as $product)
                    @php
                        $displayName = $productNames[$product->slug] ?? $product->name;
                        $categoryLabel = $product->categoryLabel();
                        $categoryClass = $categoryLabel === 'Cascara Siap Minum' ? 'is-ready' : 'is-dry';
                        $averageRating = (float) ($product->average_rating ?? 0);
                        $roundedRating = (int) round($averageRating);
                        $reviewCount = (int) ($product->reviews_count ?? 0);
                    @endphp

                    <article class="catalog-card" style="background-image: url('{{ $product->imageUrl() }}');">
                        <div class="catalog-tag {{ $categoryClass }}">{{ $categoryLabel }}</div>
                        <h3>{{ $displayName }}</h3>
                        <div class="catalog-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @if($reviewCount > 0)
                            <div class="catalog-rating">
                                <span class="rating-stars">
                                    @for($star = 1; $star <= 5; $star++)
                                        {{ $star <= $roundedRating ? '★' : '☆' }}
                                    @endfor
                                </span>
                                <span>{{ number_format($averageRating, 1, ',', '.') }}</span>
                                <span class="rating-count">({{ $reviewCount }})</span>
                            </div>
                        @endif
                        <div class="catalog-actions">
                            <a class="btn-primary" href="{{ route('products.show', $product) }}">Lihat Detail</a>
                            @auth
                                @if(!auth()->user()->is_admin)
                                    <form class="catalog-cart-form" action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button class="catalog-cart-button" type="submit" aria-label="Tambah {{ $displayName }} ke keranjang">
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <circle cx="9" cy="20" r="1"></circle>
                                                <circle cx="18" cy="20" r="1"></circle>
                                                <path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h9.7a1 1 0 0 0 1-.8L21 7H7"></path>
                                                <path d="M12 9v4"></path>
                                                <path d="M10 11h4"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a class="catalog-cart-button" href="{{ route('login') }}" aria-label="Login untuk menambahkan {{ $displayName }} ke keranjang">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <circle cx="9" cy="20" r="1"></circle>
                                        <circle cx="18" cy="20" r="1"></circle>
                                        <path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h9.7a1 1 0 0 0 1-.8L21 7H7"></path>
                                        <path d="M12 9v4"></path>
                                        <path d="M10 11h4"></path>
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="padding: 0 20px 20px;">
                {{ $products->links() }}
            </div>
        </section>
    @else
        <div class="card">
            <p class="muted" style="margin:0;">Produk tidak ditemukan.</p>
        </div>
    @endif

    <section class="card recommendation-card" id="product-recommendation">
        <div class="recommendation-heading">
            <div>
                <h2>Bingung Pilih Varian?</h2>
                <p class="muted" style="margin:0;">Pilih preferensi sederhana untuk melihat rekomendasi produk cascara yang paling sesuai.</p>
            </div>
            <button class="btn-primary" type="button" id="recommendation-reset">Reset Pilihan</button>
        </div>

        <div class="recommendation-steps">
            <div class="recommendation-step">
                <strong>Jenis produk</strong>
                <div class="recommendation-options" data-group="type">
                    <button class="recommendation-option" type="button" data-value="ready">Siap minum</button>
                    <button class="recommendation-option" type="button" data-value="dry">Seduh sendiri</button>
                </div>
            </div>
            <div class="recommendation-step">
                <strong>Rasa yang disukai</strong>
                <div class="recommendation-options" data-group="taste">
                    <button class="recommendation-option" type="button" data-value="fresh">Segar</button>
                    <button class="recommendation-option" type="button" data-value="warm">Hangat/rempah</button>
                    <button class="recommendation-option" type="button" data-value="sweet">Manis</button>
                    <button class="recommendation-option" type="button" data-value="original">Original</button>
                </div>
            </div>
            <div class="recommendation-step">
                <strong>Kebutuhan</strong>
                <div class="recommendation-options" data-group="need">
                    <button class="recommendation-option" type="button" data-value="daily">Minum harian</button>
                    <button class="recommendation-option" type="button" data-value="brew">Racik sendiri</button>
                    <button class="recommendation-option" type="button" data-value="gift">Kemasan rapi</button>
                </div>
            </div>
        </div>

        <p class="recommendation-empty" id="recommendation-empty">Pilih minimal satu preferensi untuk menampilkan rekomendasi.</p>
        <div class="recommendation-result" id="recommendation-result">
            @foreach($featuredProducts as $product)
                @php
                    $meta = $recommendationMeta[$product->slug] ?? null;
                @endphp
                @if($meta)
                    <a class="recommendation-product"
                       href="{{ route('products.show', $product) }}"
                       data-type="{{ $meta['type'] }}"
                       data-taste="{{ $meta['taste'] }}"
                       data-need="{{ $meta['need'] }}"
                       data-sold="{{ (int) ($product->sold_quantity ?? 0) }}"
                       data-rating="{{ (float) ($product->average_rating ?? 0) }}">
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                        <div>
                            <strong>{{ $productNames[$product->slug] ?? $product->name }}</strong>
                            <span>{{ $meta['note'] }}</span>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </section>

    <script>
        (() => {
            const wrapper = document.getElementById('product-recommendation');
            if (!wrapper) {
                return;
            }

            const selected = {};
            const empty = document.getElementById('recommendation-empty');
            const products = [...document.querySelectorAll('.recommendation-product')];

            const updateRecommendations = () => {
                const hasSelection = Object.keys(selected).length > 0;

                products.forEach((product) => product.classList.remove('is-visible'));

                if (!hasSelection) {
                    empty.textContent = 'Pilih minimal satu preferensi untuk menampilkan rekomendasi.';
                    return;
                }

                const ranked = products
                    .filter((product) => {
                        return Object.entries(selected).every(([group, value]) => product.dataset[group] === value);
                    })
                    .map((product) => ({
                        product,
                        score: Math.min(Number(product.dataset.sold || 0), 5) * 0.2
                            + Number(product.dataset.rating || 0) * 0.2,
                    }))
                    .sort((a, b) => b.score - a.score)
                    .slice(0, 3);

                if (ranked.length === 0) {
                    empty.textContent = 'Belum ada produk yang cocok dengan kombinasi pilihan ini. Coba pilih preferensi lain.';
                    return;
                }

                empty.textContent = 'Rekomendasi produk berdasarkan pilihan kamu:';
                ranked.forEach((item) => item.product.classList.add('is-visible'));
            };

            wrapper.querySelectorAll('.recommendation-option').forEach((button) => {
                button.addEventListener('click', () => {
                    const group = button.closest('[data-group]').dataset.group;
                    const value = button.dataset.value;

                    button.closest('[data-group]').querySelectorAll('.recommendation-option')
                        .forEach((option) => option.classList.remove('is-active'));

                    selected[group] = value;
                    button.classList.add('is-active');
                    updateRecommendations();
                });
            });

            document.getElementById('recommendation-reset').addEventListener('click', () => {
                Object.keys(selected).forEach((key) => delete selected[key]);
                wrapper.querySelectorAll('.recommendation-option').forEach((button) => button.classList.remove('is-active'));
                updateRecommendations();
            });
        })();
    </script>
@endsection
