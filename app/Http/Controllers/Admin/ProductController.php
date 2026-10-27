<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $validated['image_url'] = $this->storeProductImage($request);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product->id);

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($product->image_url);
            $validated['image_url'] = $this->storeProductImage($request);
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function validateProduct(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function storeProductImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $path = $request->file('image')->store('products', 'public');
        $this->copyToPublicStorage($path);

        return $path;
    }

    private function deleteUploadedImage(?string $imagePath): void
    {
        if (
            ! $imagePath
            || str_starts_with($imagePath, 'http://')
            || str_starts_with($imagePath, 'https://')
            || str_starts_with($imagePath, 'image/')
        ) {
            return;
        }

        Storage::disk('public')->delete($imagePath);
        $this->deleteFromPublicStorage($imagePath);
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

    private function deleteFromPublicStorage(string $path): void
    {
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

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'produk';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
