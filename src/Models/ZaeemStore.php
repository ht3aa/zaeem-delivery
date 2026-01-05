<?php

namespace Ht3aa\ZaeemDelivery\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZaeemStore extends Model
{
    protected $fillable = [
        'store_name',
        'store_phone',
        'governorate_id',
        'address',
        'latitude',
        'longitude',
        'zd_store_id',
        'zd_generated_password',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(ZaeemGovernorate::class, 'governorate_id', 'code');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
