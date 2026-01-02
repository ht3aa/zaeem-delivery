<?php

namespace Ht3aa\ZaeemDelivery\Models;

use Illuminate\Database\Eloquent\Model;

class ZaeemGovernorate extends Model
{
    protected $fillable = [
        'code',
        'global_name',
        'arabic_name',
        'description',
    ];
}
