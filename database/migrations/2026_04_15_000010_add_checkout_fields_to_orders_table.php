<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_zone', 50)->nullable()->after('shipping_address');
            $table->string('courier', 30)->nullable()->after('shipping_zone');
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('courier');
            $table->decimal('subtotal_amount', 12, 2)->default(0)->after('shipping_cost');
            $table->string('payment_method', 30)->default('qris')->after('subtotal_amount');
            $table->string('payment_proof_path')->nullable()->after('payment_method');
            $table->string('tracking_number', 100)->nullable()->after('payment_proof_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_zone',
                'courier',
                'shipping_cost',
                'subtotal_amount',
                'payment_method',
                'payment_proof_path',
                'tracking_number',
            ]);
        });
    }
};
