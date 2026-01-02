<?php

namespace Ht3aa\ZaeemDelivery\Models;

use Illuminate\Database\Eloquent\Model;

class ZaeemCity extends Model
{
    protected $fillable = [
        'city_id',
        'city_name',
        'governorate_code',
    ];

    protected function casts(): array
    {
        return [
            'city_id' => 'integer',
        ];
    }
}
