<?php

use App\Http\Controllers\Api\V1\Tenant\Item\ItemController;
use App\Http\Middleware\EnsureUserIsTenant;

Route::group(
    [
        'prefix' => 'items',
        'middleware' => ['auth:sanctum', EnsureUserIsTenant::class],
        'controller' => ItemController::class,
    ], function () {
        Route::get('/', 'index');
        Route::get('{id}', 'show');
        Route::post('/', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    }
);
