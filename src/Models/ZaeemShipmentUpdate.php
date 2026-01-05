<?php

namespace Ht3aa\ZaeemDelivery\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZaeemShipmentUpdate extends Model
{
    protected $fillable = [
        'updates',
        'zaeem_shipment_id',
    ];

    protected function casts(): array
    {
        return [
            'updates' => 'array',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(ZaeemShipment::class);
    }
}
