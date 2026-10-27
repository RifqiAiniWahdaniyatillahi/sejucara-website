<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const READY_DRINK_SLUGS = [
        'cascara-original',
        'cascara-original-100gr',
        'cascara-lemon',
        'cascara-lemon-100gr',
        'cascara-mint',
        'cascara-mint-100gr',
        'cascara-jahe',
        'cascara-jahe-100gr',
        'cascara-madu',
        'cascara-madu-100gr',
    ];

    private const FEATURED_SLUGS = [
        'cascara-original',
        'cascara-lemon',
        'cascara-mint',
        'cascara-jahe',
        'cascara-madu',
        'cascara-pouch',
        'cascara-pouch-rempah',
        'cascara-botol',
        'cascara-botol-rempah',
    ];

    public function index(Request $request): View
    {
        $featuredProducts = Product::query()
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'rating')
            ->withSum(['orderItems as sold_quantity' => fn ($query) => $this->processedOrderItems($query)], 'quantity')
            ->whereIn('slug', self::FEATURED_SLUGS)
            ->get()
            ->sortBy(function (Product $product) {
            return array_search($product->slug, self::FEATURED_SLUGS);
        })->values();

        $search = trim((string) $request->query('q', ''));
        $categorySearch = $this->categorySearchType($search);

        $products = Product::query()
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'rating')
            ->withSum(['orderItems as sold_quantity' => fn ($query) => $this->processedOrderItems($query)], 'quantity')
            ->when($categorySearch === 'dry', function ($query): void {
                $query->whereNotIn('slug', self::READY_DRINK_SLUGS);
            })
            ->when($categorySearch === 'ready', function ($query): void {
                $query->whereIn('slug', self::READY_DRINK_SLUGS);
            })
            ->when($search !== '' && $categorySearch === null, function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderByRaw("CASE WHEN slug = 'cascara-original' THEN 0 WHEN slug = 'cascara-lemon' THEN 1 WHEN slug = 'cascara-mint' THEN 2 WHEN slug = 'cascara-jahe' THEN 3 WHEN slug = 'cascara-madu' THEN 4 WHEN slug = 'cascara-pouch' THEN 5 WHEN slug = 'cascara-pouch-rempah' THEN 6 WHEN slug = 'cascara-botol' THEN 7 WHEN slug = 'cascara-botol-rempah' THEN 8 ELSE 9 END")
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('user.products.index', compact('products', 'featuredProducts', 'search'));
    }

    public function show(Product $product): View
    {
        $product->load([
            'reviews.user',
        ])
            ->loadCount('reviews')
            ->loadAvg('reviews as average_rating', 'rating')
            ->loadSum(['orderItems as sold_quantity' => fn ($query) => $this->processedOrderItems($query)], 'quantity');

        $userReview = null;
        $canReview = false;

        if (auth()->check() && ! auth()->user()->is_admin) {
            $userReview = $product->reviews->firstWhere('user_id', auth()->id());
            $canReview = $this->userCanReview($product);
        }

        return view('user.products.show', compact('product', 'userReview', 'canReview'));
    }

    public function storeReview(Request $request, Product $product): RedirectResponse
    {
        abort_if(! auth()->check() || auth()->user()->is_admin, 403);

        if (! $this->userCanReview($product)) {
            return back()->withErrors([
                'review' => 'Review hanya bisa diberikan setelah pesanan produk diproses oleh admin.',
            ]);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $product->reviews()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ],
        );

        return back()->with('success', 'Review produk berhasil disimpan.');
    }

    private function userCanReview(Product $product): bool
    {
        return OrderItem::query()
            ->where('product_id', $product->id)
            ->whereHas('order', function ($query): void {
                $query->where('user_id', auth()->id())
                    ->whereIn('status', ['diproses', 'dikirim', 'selesai']);
            })
            ->exists();
    }

    private function processedOrderItems($query): void
    {
        $query->whereHas('order', function ($query): void {
            $query->whereIn('status', ['diproses', 'dikirim', 'selesai']);
        });
    }

    private function categorySearchType(string $search): ?string
    {
        $normalized = str($search)->lower()->squish()->toString();

        if ($normalized === '') {
            return null;
        }

        if (str_contains($normalized, 'kering') || str_contains($normalized, 'pouch') || str_contains($normalized, 'seduh')) {
            return 'dry';
        }

        if (
            str_contains($normalized, 'siap minum')
            || str_contains($normalized, 'siap')
            || str_contains($normalized, 'minum')
            || str_contains($normalized, 'botol')
            || str_contains($normalized, 'drink')
        ) {
            return 'ready';
        }

        return null;
    }
}
