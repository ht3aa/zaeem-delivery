<?php

namespace Ht3aa\ZaeemDelivery\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ZaeemShipment extends Model
{
    protected $fillable = [
        'order_id',
        'order_type',
        'shipment_number',
        'external_shipment_id',
        'airway_bill_number',
        'merchant_id',
        'store_id',
        'receiver_name',
        'receiver_phone_1',
        'receiver_phone_2',
        'governorate_code',
        'city',
        'address',
        'amount_iqd',
        'amount_usd',
        'quantity',
        'is_proof_of_delivery',
        'is_fragile',
        'is_special_case',
        'product_info',
        'note',
        'receiver_latitude',
        'receiver_longitude',
        'zd_shipment_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount_iqd' => 'decimal:2',
            'amount_usd' => 'decimal:2',
            'quantity' => 'integer',
            'is_proof_of_delivery' => 'boolean',
            'is_fragile' => 'boolean',
            'is_special_case' => 'boolean',
            'receiver_latitude' => 'decimal:7',
            'receiver_longitude' => 'decimal:7',
        ];
    }

    public function order(): MorphTo
    {
        return $this->morphTo();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(ZaeemStore::class);
    }

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(ZaeemGovernorate::class, 'governorate_code', 'code');
    }
}
