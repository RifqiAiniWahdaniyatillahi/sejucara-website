<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ChatConversation;
use App\Models\ForumThread;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const REVENUE_STATUSES = ['diproses', 'dikirim', 'selesai'];

    public function index(): View
    {
        $unansweredThreads = ForumThread::whereDoesntHave('replies.user', fn ($query) => $query->where('is_admin', true))->count();
        $chatWaitingResponse = ChatConversation::whereHas('messages', fn ($query) => $query
            ->whereHas('sender', fn ($sender) => $sender->where('is_admin', false))
            ->whereNull('read_at'))
            ->count();
        $completedOrders = Order::where('status', 'selesai')->count();
        $topOrderedProducts = OrderItem::query()
            ->selectRaw('product_id, SUM(quantity) as total_quantity')
            ->with('product:id,name,deleted_at')
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();
        $forumTopicInsights = $this->forumTopicInsights();

        return view('admin.dashboard', [
            'totalUsers' => User::where('is_admin', false)->count(),
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'menunggu_verifikasi')->count(),
            'processingOrders' => Order::whereIn('status', ['diproses', 'dikirim'])->count(),
            'totalRevenue' => $this->productRevenue(),
            'totalArticles' => Article::count(),
            'totalThreads' => ForumThread::count(),
            'latestProducts' => Product::latest()->take(5)->get(),
            'lowStockProducts' => Product::where('stock', '<=', 10)->orderBy('stock')->take(5)->get(),
            'latestOrders' => Order::latest()->take(5)->get(),
            'latestThreads' => ForumThread::with('user')->withCount('replies')->latest()->take(5)->get(),
            'unansweredThreads' => $unansweredThreads,
            'chatWaitingResponse' => $chatWaitingResponse,
            'completedOrders' => $completedOrders,
            'topOrderedProducts' => $topOrderedProducts,
            'forumTopicInsights' => $forumTopicInsights,
        ]);
    }

    private function productRevenue(): int
    {
        return (int) OrderItem::query()
            ->whereHas('order', fn ($query) => $query->whereIn('status', self::REVENUE_STATUSES))
            ->sum('subtotal');
    }

    private function forumTopicInsights(): Collection
    {
        $topics = [
            'Produk & Varian' => ['produk', 'varian', 'rasa', 'stok', 'kering', 'siap minum'],
            'Cara Seduh' => ['seduh', 'takaran', 'dingin', 'panas', 'resep'],
            'Pemesanan' => ['pesan', 'checkout', 'qris', 'bayar', 'resi', 'kirim', 'ongkir'],
            'Saran Pengguna' => ['saran', 'masukan', 'kritik', 'fitur', 'website'],
        ];

        return collect($topics)
            ->map(fn (array $keywords, string $label) => [
                'label' => $label,
                'count' => ForumThread::where(function ($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $query->orWhere('title', 'like', "%{$keyword}%")
                            ->orWhere('body', 'like', "%{$keyword}%");
                    }
                })->count(),
            ])
            ->sortByDesc('count')
            ->values();
    }
}
