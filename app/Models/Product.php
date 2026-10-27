<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image_url',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function ratingSummary(): string
    {
        $average = $this->average_rating ?? $this->reviews()->avg('rating');
        $count = $this->reviews_count ?? $this->reviews()->count();

        if (! $average || $count < 1) {
            return 'Belum ada ulasan';
        }

        return number_format((float) $average, 1, ',', '.').' dari 5 ('.$count.' ulasan)';
    }

    public function categoryLabel(): string
    {
        return in_array($this->slug, [
            'cascara-original',
            'cascara-original-100gr',
            'cascara-lemon',
            'cascara-lemon-100gr',
            'cascara-jahe',
            'cascara-jahe-100gr',
            'cascara-madu',
            'cascara-madu-100gr',
            'cascara-mint',
            'cascara-mint-100gr',
        ], true) ? 'Cascara Siap Minum' : 'Cascara Kering';
    }

    public function imageUrl(): string
    {
        $mappedImages = [
            'cascara-original' => 'image/botolori2.jpeg.png',
            'cascara-original-100gr' => 'image/botolori2.jpeg.png',
            'cascara-lemon' => 'image/botollemon2.png',
            'cascara-lemon-100gr' => 'image/botollemon2.png',
            'cascara-jahe' => 'image/botolginger2.jpeg.png',
            'cascara-jahe-100gr' => 'image/botolginger2.jpeg.png',
            'cascara-madu' => 'image/botolmadu2.jpeg.png',
            'cascara-madu-100gr' => 'image/botolmadu2.jpeg.png',
            'cascara-mint' => 'image/botolmint2.png',
            'cascara-mint-100gr' => 'image/botolmint2.png',
            'cascara-pouch' => 'image/pouch.png',
            'cascara-pouch-250gr' => 'image/pouch.png',
            'cascara-pouch-rempah' => 'image/pouchrempah3.png',
            'cascara-pouchrempah3-250gr' => 'image/pouchrempah3.png',
            'cascara-botol' => 'image/botol.png',
            'cascara-botol-250ml' => 'image/botol.png',
            'cascara-botol-rempah' => 'image/scrempah.png',
            'cascara-scrempah-250gr' => 'image/scrempah.png',
        ];

        if (isset($mappedImages[$this->slug])) {
            return asset($mappedImages[$this->slug]);
        }

        if (! $this->image_url) {
            return asset('image/cascara1.jpg');
        }

        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
            return $this->image_url;
        }

        if (str_starts_with($this->image_url, 'image/')) {
            return asset($this->image_url);
        }

        if (Storage::disk('public')->exists($this->image_url)) {
            return asset('storage/'.$this->image_url);
        }

        if (str_contains($this->image_url, '/')) {
            return asset('storage/'.$this->image_url);
        }

        return asset($this->image_url);
    }
}
