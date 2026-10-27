<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $slugMap = [
        'cascara-original-100gr' => 'cascara-original',
        'cascara-lemon-100gr' => 'cascara-lemon',
        'cascara-jahe-100gr' => 'cascara-jahe',
        'cascara-madu-100gr' => 'cascara-madu',
        'cascara-mint-100gr' => 'cascara-mint',
        'cascara-berry-100gr' => 'cascara-berry',
        'cascara-orange-100gr' => 'cascara-orange',
        'cascara-pouch-250gr' => 'cascara-pouch',
        'cascara-pouchrempah3-250gr' => 'cascara-pouch-rempah',
        'cascara-botol-250ml' => 'cascara-botol',
        'cascara-scrempah-250gr' => 'cascara-botol-rempah',
    ];

    public function up(): void
    {
        foreach ($this->slugMap as $oldSlug => $newSlug) {
            DB::table('products')
                ->where('slug', $oldSlug)
                ->update(['slug' => $newSlug]);
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->slugMap) as $oldSlug => $newSlug) {
            DB::table('products')
                ->where('slug', $newSlug)
                ->update(['slug' => $oldSlug]);
        }
    }
};
