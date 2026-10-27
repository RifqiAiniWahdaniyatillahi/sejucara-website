<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ForumThread;
use App\Models\Order;
use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = UserNotification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('user.notifications.index', compact('notifications'));
    }

    public function open(Request $request, UserNotification $notification): RedirectResponse
    {
        abort_if((int) $notification->user_id !== (int) $request->user()->id, 403);

        if (! $notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return redirect($this->safeNotificationUrl($notification));
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        UserNotification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    private function safeNotificationUrl(UserNotification $notification): string
    {
        if (! $notification->url) {
            return route('notifications.index');
        }

        $path = parse_url($notification->url, PHP_URL_PATH);
        $query = parse_url($notification->url, PHP_URL_QUERY);

        if (! $path) {
            return route('notifications.index');
        }

        if ($query) {
            $path .= '?'.$query;
        }

        if (preg_match('#^/orders/(\d+)#', $path, $matches)) {
            $orderExists = Order::where('id', (int) $matches[1])
                ->where('user_id', $notification->user_id)
                ->exists();

            return $orderExists ? url($path) : route('orders.index');
        }

        if (preg_match('#^/forum/(\d+)#', $path, $matches)) {
            return ForumThread::where('id', (int) $matches[1])->exists()
                ? url($path)
                : route('forum.index');
        }

        return url($path);
    }
}
