<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::with('user')->latest()->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('admin.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateArticle($request);
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);
        $validated['user_id'] = $request->user()->id;

        // handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeArticleImage($request);
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $this->validateArticle($request);

        if ($article->title !== $validated['title']) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title'], $article->id);
        }

        // handle image upload (replace existing)
        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }
            $this->deleteFromPublicStorage($article->image);

            $validated['image'] = $this->storeArticleImage($request);
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    private function validateArticle(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);

        if ($baseSlug === '') {
            $baseSlug = 'artikel';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            Article::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function storeArticleImage(Request $request): string
    {
        $path = $request->file('image')->store('articles', 'public');
        $this->copyToPublicStorage($path);

        return $path;
    }

    private function copyToPublicStorage(string $path): void
    {
        $source = storage_path('app/public/'.$path);

        if (! File::exists($source)) {
            return;
        }

        foreach ($this->publicStorageRoots() as $root) {
            $destination = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path);
            File::ensureDirectoryExists(dirname($destination));
            File::copy($source, $destination);
        }
    }

    private function deleteFromPublicStorage(?string $path): void
    {
        if (! $path) {
            return;
        }

        foreach ($this->publicStorageRoots() as $root) {
            $target = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path);

            if (File::exists($target)) {
                File::delete($target);
            }
        }
    }

    private function publicStorageRoots(): array
    {
        $roots = [public_path('storage')];

        if (app()->environment('production')) {
            $roots[] = base_path('sejucara.my.id/storage');
            $roots[] = base_path('../sejucara.my.id/storage');
        }

        return array_unique($roots);
    }
}
