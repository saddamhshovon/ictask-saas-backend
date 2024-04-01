<?php

use App\Http\Controllers\Api\V1\Tenant\Inventory\InventoryController;

Route::get('inventories', [InventoryController::class, 'show']);
Route::post('inventories', [InventoryController::class, 'store']);
Route::put('inventories', [InventoryController::class, 'update']);
Route::delete('inventories', [InventoryController::class, 'destroy']);
