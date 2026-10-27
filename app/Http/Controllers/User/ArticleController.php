<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::latest()->paginate(6);

        return view('user.articles.index', compact('articles'));
    }

    public function show(Article $article): View
    {
        return view('user.articles.show', compact('article'));
    }
}
