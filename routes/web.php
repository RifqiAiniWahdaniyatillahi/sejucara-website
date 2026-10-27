<?php

use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'user.about', ['title' => 'Tentang Kami - Sejucara'])->name('about');
Route::view('/contact', 'user.contact', ['title' => 'Kontak - Sejucara'])->name('contact');

require __DIR__.'/user.php';
require __DIR__.'/admin.php';
