<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::whereIn('slug', [
            'cascara-original',
            'cascara-lemon',
            'cascara-jahe',
        ])->get()->sortBy(function (Product $product) {
            return array_search($product->slug, ['cascara-original', 'cascara-lemon', 'cascara-jahe']);
        })->values();

        $newVariants = Product::whereIn('slug', [
            'cascara-madu',
            'cascara-mint',
        ])->get()->sortBy(function (Product $product) {
            return array_search($product->slug, ['cascara-madu', 'cascara-mint']);
        })->values();
        $articles = Article::latest()->take(3)->get();

        return view('user.home', compact('featuredProducts', 'newVariants', 'articles'));
    }
}
