<?php

use Ht3aa\ZaeemDelivery\Controllers\ZaeemDeliveryWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('v2')->group(function () {
    Route::post('/push/update-status', [ZaeemDeliveryWebhookController::class, 'store']);
});
