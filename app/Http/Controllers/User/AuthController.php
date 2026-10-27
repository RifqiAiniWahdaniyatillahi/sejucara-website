<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('user.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
        ]);

        Auth::login($user);
        $this->mergeSessionCartToUser($request, $user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil. Selamat datang!');
    }

    public function showLogin(): View
    {
        return view('user.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt([...$credentials, 'is_admin' => false], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->mergeSessionCartToUser($request, $request->user());

            return redirect()->route('home')->with('success', 'Login berhasil.');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda berhasil logout.');
    }

    private function mergeSessionCartToUser(Request $request, User $user): void
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        foreach ($cart as $productId => $item) {
            $quantity = (int) ($item['quantity'] ?? 0);
            $product = $products->get((int) $productId);

            if ($quantity <= 0 || ! $product || $product->stock <= 0) {
                continue;
            }

            $cartItem = CartItem::firstOrNew([
                'user_id' => $user->id,
                'product_id' => (int) $productId,
            ]);

            $cartItem->quantity = min(($cartItem->quantity ?? 0) + $quantity, $product->stock);
            $cartItem->save();
        }

        $request->session()->forget('cart');
    }
}
