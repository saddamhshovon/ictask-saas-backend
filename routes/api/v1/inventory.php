<?php

use App\Http\Controllers\Api\V1\Tenant\Inventory\InventoryController;
use App\Http\Middleware\EnsureUserIsTenant;

Route::group(
    [
        'prefix' => 'inventories',
        'middleware' => ['auth:sanctum', EnsureUserIsTenant::class],
        'controller' => InventoryController::class,
    ], function () {
        Route::get('/', 'show');
        Route::post('/', 'store');
        Route::put('/', 'update');
        Route::delete('/', 'destroy');
    }
);
