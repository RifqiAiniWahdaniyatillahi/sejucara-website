<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_destination_label')->nullable()->after('shipping_zone');
            $table->string('shipping_service', 50)->nullable()->after('courier');
            $table->string('shipping_estimate', 50)->nullable()->after('shipping_service');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_destination_label',
                'shipping_service',
                'shipping_estimate',
            ]);
        });
    }
};
