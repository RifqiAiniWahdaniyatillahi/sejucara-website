<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $this->cartForRequest($request);
        $productIds = array_keys($cart);

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0;
        $unavailableProductIds = [];

        foreach ($cart as $productId => $item) {
            $product = $products->get((int) $productId);

            if (! $product || $product->stock <= 0) {
                $unavailableProductIds[] = (int) $productId;
                continue;
            }

            $quantity = min((int) $item['quantity'], $product->stock);
            $subtotal = $product->price * $quantity;
            $total += $subtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        $this->removeUnavailableItems($request, $unavailableProductIds);

        return view('user.cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $qty = $validated['quantity'];

        if ($product->stock <= 0) {
            return redirect()->route('products.index')->withErrors([
                'cart' => 'Stok produk sedang habis.',
            ]);
        }

        if ($request->user()) {
            $cartItem = CartItem::firstOrNew([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
            ]);

            $cartItem->quantity = min(($cartItem->quantity ?? 0) + $qty, $product->stock);
            $cartItem->save();

            return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
        }

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $qty += $cart[$product->id]['quantity'];
        }

        $cart[$product->id] = [
            'quantity' => min($qty, $product->stock),
        ];

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->user()) {
            $cartItem = CartItem::where('user_id', $request->user()->id)
                ->where('product_id', $product->id)
                ->first();

            if (! $cartItem) {
                return redirect()->route('cart.index')->with('success', 'Item tidak ditemukan di keranjang.');
            }

            if ($product->stock <= 0) {
                $cartItem->delete();

                return redirect()->route('cart.index')->withErrors([
                    'cart' => 'Produk sudah habis dan dihapus dari keranjang.',
                ]);
            }

            $cartItem->update([
                'quantity' => min($validated['quantity'], $product->stock),
            ]);

            return redirect()->route('cart.index')->with('success', 'Jumlah item diperbarui.');
        }

        $cart = $request->session()->get('cart', []);

        if (! isset($cart[$product->id])) {
            return redirect()->route('cart.index')->with('success', 'Item tidak ditemukan di keranjang.');
        }

        if ($product->stock <= 0) {
            unset($cart[$product->id]);
            $request->session()->put('cart', $cart);

            return redirect()->route('cart.index')->withErrors([
                'cart' => 'Produk sudah habis dan dihapus dari keranjang.',
            ]);
        }

        $cart[$product->id]['quantity'] = min($validated['quantity'], $product->stock);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Jumlah item diperbarui.');
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        if ($request->user()) {
            CartItem::where('user_id', $request->user()->id)
                ->where('product_id', $product->id)
                ->delete();

            return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
        }

        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
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

    private function removeUnavailableItems(Request $request, array $productIds): void
    {
        if ($productIds === []) {
            return;
        }

        if ($request->user()) {
            CartItem::where('user_id', $request->user()->id)
                ->whereIn('product_id', $productIds)
                ->delete();

            return;
        }

        $cart = $request->session()->get('cart', []);

        foreach ($productIds as $productId) {
            unset($cart[$productId]);
        }

        $request->session()->put('cart', $cart);
    }
}
