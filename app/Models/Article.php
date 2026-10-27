<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'location',
        'image',
        'content',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function imageUrl(): string
    {
        $mappedImages = [
            'sejucara-perkenalkan-cascara-nusantara' => 'artikel1.jpg',
            'manfaat-cascara-untuk-aktivitas-sehari-hari' => 'artikel2.jpg',
            'apa-itu-cascara-panduan-dasar-untuk-pemula' => 'artikel3.jpg',
            'tips-menyimpan-cascara-agar-tetap-segar' => 'artikel4.jpg',
            'cara-menyeduh-cascara-yang-benar-di-rumah' => 'artikel5.jpg',
            'cascara-dingin-cocok-untuk-cuaca-panas' => 'artikel6.jpg',
            'mengapa-cascara-jadi-tren-minuman-sehat' => 'artikel7.jpg',
            'mengenal-varian-rasa-cascara-dan-cara-memilihnya' => 'allvarian.png',
            'cascara-dan-keberlanjutan-dari-limbah-kopi-menjadi-minuman-bernilai' => 'cascaram.jpg',
        ];

        if (isset($mappedImages[$this->slug])) {
            return asset('image/'.$mappedImages[$this->slug]);
        }

        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif'];

        foreach ($extensions as $extension) {
            $imagePath = 'image/'.$this->slug.'.'.$extension;

            if (file_exists(public_path($imagePath))) {
                return asset($imagePath);
            }
        }

        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/'.$this->image);
        }

        if ($this->image && str_contains($this->image, '/')) {
            return asset('storage/'.$this->image);
        }

        return asset('image/article-default.svg');
    }

    protected static function booted()
    {
        static::deleting(function (self $article) {
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }

            $article->deletePublicImageCopy();
        });
    }

    private function deletePublicImageCopy(): void
    {
        if (! $this->image) {
            return;
        }

        $roots = [public_path('storage')];

        if (app()->environment('production')) {
            $roots[] = base_path('sejucara.my.id/storage');
            $roots[] = base_path('../sejucara.my.id/storage');
        }

        foreach (array_unique($roots) as $root) {
            $target = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $this->image);

            if (File::exists($target)) {
                File::delete($target);
            }
        }
    }
}
