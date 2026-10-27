<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\UserNotification;
use App\Notifications\UserActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['user', 'items.product'])->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu_pembayaran,menunggu_verifikasi,diproses,dikirim,selesai,dibatalkan'],
            'tracking_number' => [
                Rule::requiredIf($request->input('status') === 'dikirim'),
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $payload = [
            'status' => $validated['status'],
        ];

        if (array_key_exists('tracking_number', $validated)) {
            $payload['tracking_number'] = $validated['tracking_number'];
        }

        $oldStatus = $order->status;
        $oldTrackingNumber = $order->tracking_number;

        $order->update($payload);

        if ($order->user_id && ($oldStatus !== $order->status || $oldTrackingNumber !== $order->tracking_number)) {
            $notification = UserNotification::create([
                'user_id' => $order->user_id,
                'type' => 'order',
                'title' => 'Status pesanan diperbarui',
                'message' => 'Pesanan #'.$order->id.' sekarang berstatus '.str_replace('_', ' ', $order->status).'.',
                'url' => route('orders.show', $order),
            ]);

            $this->sendEmailNotification($order->user, $notification);
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function paymentProof(Order $order): BinaryFileResponse
    {
        abort_if(! $order->payment_proof_path, 404);

        $disk = Storage::disk('local');

        abort_if(! $disk->exists($order->payment_proof_path), 404);

        return response()->file($disk->path($order->payment_proof_path));
    }

    private function sendEmailNotification($user, UserNotification $notification): void
    {
        if (! $user || blank($user->email)) {
            return;
        }

        try {
            $user->notify(new UserActivityNotification(
                $notification->title,
                $notification->message,
                $notification->url
            ));
        } catch (Throwable $exception) {
            logger()->warning('Email notifikasi pesanan belum bisa dikirim.', [
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
