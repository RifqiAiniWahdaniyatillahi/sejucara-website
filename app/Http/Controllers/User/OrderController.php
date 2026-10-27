<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    private const CANCELLABLE_STATUSES = [
        'menunggu_pembayaran',
        'menunggu_verifikasi',
    ];

    public function index(Request $request): View
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View|RedirectResponse
    {
        if ((int) $order->user_id !== (int) $request->user()->id) {
            return redirect()->route('orders.index');
        }

        $order->load('items.product');

        return view('user.orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        if ((int) $order->user_id !== (int) $request->user()->id) {
            return redirect()->route('orders.index');
        }

        $validated = $request->validate([
            'cancel_reason' => ['required', 'string', 'max:255'],
        ], [
            'cancel_reason.required' => 'Pilih atau tulis alasan pembatalan pesanan terlebih dahulu.',
        ]);

        if (! in_array($order->status, self::CANCELLABLE_STATUSES, true)) {
            return back()->withErrors([
                'order' => 'Pesanan tidak dapat dibatalkan karena sudah diproses admin.',
            ]);
        }

        $cancelled = DB::transaction(function () use ($order, $validated) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if (! in_array($lockedOrder->status, self::CANCELLABLE_STATUSES, true)) {
                return false;
            }

            $lockedOrder->load('items');

            foreach ($lockedOrder->items as $item) {
                Product::withTrashed()
                    ->whereKey($item->product_id)
                    ->increment('stock', $item->quantity);
            }

            $lockedOrder->update([
                'status' => 'dibatalkan',
                'cancel_reason' => $validated['cancel_reason'],
                'canceled_at' => now(),
            ]);

            return true;
        });

        if (! $cancelled) {
            return back()->withErrors([
                'order' => 'Pesanan tidak dapat dibatalkan karena sudah diproses admin.',
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
