<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    private const SHIPPING_ESTIMATES = [
        'jawa_barat' => '1-2 hari kerja',
        'pulau_jawa' => '2-4 hari kerja',
        'luar_jawa' => '4-7 hari kerja',
    ];

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'shipping_address',
        'shipping_zone',
        'shipping_destination_label',
        'courier',
        'shipping_service',
        'shipping_estimate',
        'shipping_cost',
        'subtotal_amount',
        'payment_method',
        'payment_proof_path',
        'tracking_number',
        'status',
        'cancel_reason',
        'canceled_at',
        'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'canceled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingEstimateLabel(): string
    {
        if ($this->shipping_estimate) {
            return $this->shipping_estimate;
        }

        return self::SHIPPING_ESTIMATES[$this->shipping_zone] ?? 'Menyesuaikan alamat pengiriman';
    }
}
