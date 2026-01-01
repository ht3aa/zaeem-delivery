<?php

namespace Ht3aa\ZaeemDelivery\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ht3aa\ZaeemDelivery\ZaeemDelivery
 */
class ZaeemDelivery extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ht3aa\ZaeemDelivery\ZaeemDelivery::class;
    }
}
