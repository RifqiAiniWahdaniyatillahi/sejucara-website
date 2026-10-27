<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\RajaOngkirService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private const SHIPPING_ZONES = [
        'jawa_barat' => [
            'label' => 'Jawa Barat',
            'cost' => 10000,
            'estimate' => '1-2 hari kerja',
        ],
        'pulau_jawa' => [
            'label' => 'Pulau Jawa (di luar Jawa Barat)',
            'cost' => 15000,
            'estimate' => '2-4 hari kerja',
        ],
        'luar_jawa' => [
            'label' => 'Luar Pulau Jawa',
            'cost' => 25000,
            'estimate' => '4-7 hari kerja',
        ],
    ];

    private const COURIERS = [
        'jne' => 'JNE',
        'jnt' => 'J&T',
        'pos' => 'POS Indonesia',
    ];

    public function __construct(private readonly RajaOngkirService $rajaOngkir)
    {
    }

    public function create(Request $request): View|RedirectResponse
    {
        [$items, $total] = $this->cartSummary($request);

        if (count($items) === 0) {
            return redirect()->route('products.index')->with('success', 'Keranjang masih kosong.');
        }

        $shippingZones = self::SHIPPING_ZONES;
        $couriers = self::COURIERS;
        $rajaOngkirReady = $this->rajaOngkir->isConfigured();
        $totalWeight = $this->cartWeight($items);
        $lastOrder = $request->user()
            ->orders()
            ->latest()
            ->first(['customer_name', 'customer_phone', 'shipping_address']);

        return view('user.checkout.create', compact('items', 'total', 'shippingZones', 'couriers', 'rajaOngkirReady', 'totalWeight', 'lastOrder'));
    }

    public function destinations(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keyword' => ['required', 'string', 'min:3', 'max:80'],
        ]);

        return response()->json([
            'data' => $this->rajaOngkir->searchDestinations($validated['keyword']),
        ]);
    }

    public function shippingRates(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'destination_id' => ['required', 'string', 'max:50'],
            'courier' => ['required', 'in:'.implode(',', array_keys(self::COURIERS))],
        ]);

        [$items, $total] = $this->cartSummary($request);

        if (count($items) === 0) {
            return response()->json([
                'message' => 'Keranjang masih kosong.',
                'data' => [],
            ], 422);
        }

        return response()->json([
            'data' => $this->rajaOngkir->rates(
                $validated['destination_id'],
                $validated['courier'],
                $this->cartWeight($items),
                (int) $total
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_zone' => ['required', 'string', 'max:50'],
            'shipping_destination_label' => ['nullable', 'string', 'max:255'],
            'shipping_service' => ['nullable', 'string', 'max:50'],
            'shipping_estimate' => ['nullable', 'string', 'max:50'],
            'shipping_cost' => ['nullable', 'integer', 'min:0'],
            'courier' => ['required', 'in:'.implode(',', array_keys(self::COURIERS))],
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $cart = $this->cartForRequest($request);

        if (count($cart) === 0) {
            return redirect()->route('products.index')->with('success', 'Keranjang masih kosong.');
        }

        [$summaryItems, $summaryTotal] = $this->cartSummary($request);
        $shipping = $this->resolveShipping($validated, $summaryItems, (int) $summaryTotal);
        $paymentProof = $request->file('payment_proof');

        $order = null;

        DB::transaction(function () use ($request, $validated, $cart, $shipping, $paymentProof, &$order): void {
            [$items, $total] = $this->lockedCartSummary($cart);

            if (count($items) === 0) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang kosong atau stok produk tidak tersedia.',
                ]);
            }

            $grandTotal = $total + $shipping['cost'];
            $paymentProofPath = $paymentProof->store('payment-proofs', 'local');

            $order = Order::create([
                'user_id' => $request->user()->id,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_zone' => $shipping['zone'],
                'shipping_destination_label' => $shipping['destination_label'],
                'courier' => $shipping['courier'],
                'shipping_service' => $shipping['service'],
                'shipping_estimate' => $shipping['estimate'],
                'shipping_cost' => $shipping['cost'],
                'subtotal_amount' => $total,
                'payment_method' => 'qris',
                'payment_proof_path' => $paymentProofPath,
                'status' => 'menunggu_verifikasi',
                'total_amount' => $grandTotal,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['product']->price,
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }
        });

        CartItem::where('user_id', $request->user()->id)->delete();
        $request->session()->forget('cart');

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Checkout berhasil. Pesanan Anda menunggu verifikasi pembayaran QRIS.');
    }

    private function cartSummary(Request $request): array
    {
        $cart = $this->cartForRequest($request);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = $products->get((int) $productId);

            if (! $product || $product->stock <= 0) {
                continue;
            }

            $quantity = min($item['quantity'], $product->stock);
            $subtotal = $quantity * $product->price;
            $total += $subtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        return [$items, $total];
    }

    private function resolveShipping(array $validated, array $items, int $total): array
    {
        if (str_starts_with($validated['shipping_zone'], 'ro:')) {
            $destinationId = substr($validated['shipping_zone'], 3);
            $rates = $this->rajaOngkir->rates(
                $destinationId,
                $validated['courier'],
                $this->cartWeight($items),
                $total
            );

            $selectedRate = collect($rates)->first(function (array $rate) use ($validated) {
                return ($rate['service'] ?? '') === ($validated['shipping_service'] ?? '');
            });

            if (! $selectedRate) {
                throw ValidationException::withMessages([
                    'shipping_zone' => 'Ongkir RajaOngkir belum berhasil diverifikasi. Silakan cek ongkir ulang.',
                ]);
            }

            return [
                'zone' => 'ro:'.$destinationId,
                'destination_label' => $validated['shipping_destination_label'] ?? null,
                'courier' => $validated['courier'],
                'service' => $selectedRate['service'],
                'estimate' => $selectedRate['estimate'],
                'cost' => (int) $selectedRate['cost'],
            ];
        }

        if (! isset(self::SHIPPING_ZONES[$validated['shipping_zone']])) {
            throw ValidationException::withMessages([
                'shipping_zone' => 'Pilih tujuan pengiriman terlebih dahulu.',
            ]);
        }

        return [
            'zone' => $validated['shipping_zone'],
            'destination_label' => $validated['shipping_destination_label'] ?? null,
            'courier' => $validated['courier'],
            'service' => null,
            'estimate' => self::SHIPPING_ZONES[$validated['shipping_zone']]['estimate'],
            'cost' => self::SHIPPING_ZONES[$validated['shipping_zone']]['cost'],
        ];
    }

    private function cartWeight(array $items): int
    {
        $defaultWeight = max(1, (int) config('services.rajaongkir.default_weight', 250));

        return max(1, collect($items)->sum(fn (array $item) => $item['quantity'] * $defaultWeight));
    }

    private function cartForRequest(Request $request): array
    {
        if (! $request->user()) {
            return $request->session()->get('cart', []);
        }

        return CartItem::where('user_id', $request->user()->id)
            ->pluck('quantity', 'product_id')
            ->map(fn ($quantity) => ['quantity' => (int) $quantity])
            ->all();
    }

    private function lockedCartSummary(array $cart): array
    {
        $products = Product::whereIn('id', array_keys($cart))
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = $products->get((int) $productId);

            if (! $product || $product->stock <= 0) {
                continue;
            }

            $requestedQuantity = (int) ($item['quantity'] ?? 0);
            $quantity = $requestedQuantity;

            if ($quantity <= 0) {
                continue;
            }

            if ($requestedQuantity > $product->stock) {
                throw ValidationException::withMessages([
                    'cart' => 'Stok '.$product->name.' berubah saat checkout. Stok tersedia sekarang '.$product->stock.' item. Silakan perbarui keranjang terlebih dahulu.',
                ]);
            }

            $subtotal = $quantity * $product->price;
            $total += $subtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        return [$items, $total];
    }
}
