<?php

use App\Http\Controllers\User\ArticleController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\ChatController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\ForumController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/{thread}', [ForumController::class, 'show'])->name('forum.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::get('/checkout/destinations', [CheckoutController::class, 'destinations'])->name('checkout.destinations');
    Route::post('/checkout/shipping-rates', [CheckoutController::class, 'shippingRates'])->name('checkout.shipping-rates');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/notifications/{notification}', [NotificationController::class, 'open'])->name('notifications.open');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/products/{product:slug}/reviews', [ProductController::class, 'storeReview'])->name('products.reviews.store');

    Route::post('/forum', [ForumController::class, 'storeThread'])->name('forum.store');
    Route::post('/forum/{thread}/reply', [ForumController::class, 'storeReply'])->name('forum.reply.store');
    Route::post('/forum/replies/{reply}/like', [ForumController::class, 'toggleReplyLike'])->name('forum.reply.like');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
