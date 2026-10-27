<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ForumController as AdminForumController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SalesReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/payment-proof', [AdminOrderController::class, 'paymentProof'])->name('orders.payment-proof');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status.update');
    Route::get('/reports/sales', [SalesReportController::class, 'index'])->name('reports.sales');
    Route::get('/reports/sales/download', [SalesReportController::class, 'download'])->name('reports.sales.download');

    Route::resource('articles', AdminArticleController::class)->except(['show']);

    Route::get('/forum', [AdminForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/{thread}', [AdminForumController::class, 'show'])->name('forum.show');
    Route::delete('/forum/{thread}', [AdminForumController::class, 'destroyThread'])->name('forum.destroy');
    Route::post('/forum/{thread}/reply', [AdminForumController::class, 'storeReply'])->name('forum.reply.store');
    Route::delete('/forum/{thread}/reply/{reply}', [AdminForumController::class, 'destroyReply'])->name('forum.reply.destroy');

    Route::get('/chat', [AdminChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [AdminChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}', [AdminChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{conversation}/messages', [AdminChatController::class, 'messages'])->name('chat.messages');
});
