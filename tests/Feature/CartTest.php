<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_out_of_stock_product_cannot_be_added_to_cart(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $product = Product::create([
            'name' => 'Cascara Habis',
            'slug' => 'cascara-habis',
            'description' => 'Produk untuk pengujian stok kosong.',
            'price' => 30000,
            'stock' => 0,
        ]);

        $this->actingAs($user)
            ->post(route('cart.add', $product), ['quantity' => 1])
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_out_of_stock_item_is_removed_when_cart_is_opened(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $product = Product::create([
            'name' => 'Cascara Kosong',
            'slug' => 'cascara-kosong',
            'description' => 'Produk untuk pengujian pembersihan keranjang.',
            'price' => 30000,
            'stock' => 0,
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->get(route('cart.index'))
            ->assertOk();

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_session_cart_respects_product_stock_after_login(): void
    {
        $user = User::factory()->create([
            'email' => 'pembeli@example.test',
            'is_admin' => false,
        ]);
        $product = Product::create([
            'name' => 'Cascara Terbatas',
            'slug' => 'cascara-terbatas',
            'description' => 'Produk untuk pengujian sinkronisasi stok login.',
            'price' => 30000,
            'stock' => 2,
        ]);

        $this->withSession([
            'cart' => [
                $product->id => ['quantity' => 8],
            ],
        ])->post(route('login.submit'), [
            'email' => 'pembeli@example.test',
            'password' => 'password',
        ])->assertRedirect(route('home'));

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_checkout_rejects_cart_when_stock_changes_before_transaction(): void
    {
        Storage::fake('local');

        $user = User::factory()->create(['is_admin' => false]);
        $product = Product::create([
            'name' => 'Cascara Sisa Satu',
            'slug' => 'cascara-sisa-satu',
            'description' => 'Produk untuk pengujian stok berubah saat checkout.',
            'price' => 30000,
            'stock' => 1,
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user)
            ->post(route('checkout.store'), [
                'customer_name' => 'Pembeli Uji',
                'customer_phone' => '081234567890',
                'shipping_address' => 'Alamat lengkap pembeli uji',
                'shipping_zone' => 'jawa_barat',
                'shipping_destination_label' => 'Jawa Barat',
                'shipping_cost' => 10000,
                'courier' => 'jne',
                'payment_proof' => UploadedFile::fake()->image('bukti.jpg'),
            ])
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id,
            'customer_name' => 'Pembeli Uji',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 1,
        ]);
    }
}
