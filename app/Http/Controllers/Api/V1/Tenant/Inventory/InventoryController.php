<?php

namespace App\Http\Controllers\Api\V1\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\InventoryStoreRequest;
use App\Http\Requests\Inventory\InventoryUpdateRequest;
use App\Http\Resources\Inventory\InventoryResource;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Response;

class InventoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryStoreRequest $request)
    {
        try {
            Inventory::create([...$request->validated(), 'user_id' => auth()->id()]);

            return response()->json(
                [
                    'message' => 'Inventory created successfully.',
                ],
                Response::HTTP_CREATED
            );
        } catch (UniqueConstraintViolationException) {
            return response()->json(
                [
                    'message' => 'You already have an inventory.',
                ],
                Response::HTTP_CONFLICT
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        try {
            $inventory = Inventory::currentTenant()->firstOrFail();

            return new InventoryResource($inventory);
        } catch (ModelNotFoundException) {
            return response()->json(
                [
                    'message' => "You don't have an inventory.",
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventoryUpdateRequest $request)
    {
        try {
            $inventory = Inventory::currentTenant()->firstOrFail();

            $inventory->update($request->validated());

            return response()->json(
                [
                    'message' => 'Inventory information updated.',
                ],
                Response::HTTP_OK
            );
        } catch (ModelNotFoundException) {
            return response()->json(
                [
                    'message' => "You don't have an inventory.",
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        try {
            $inventory = Inventory::currentTenant()->firstOrFail();

            $inventory->delete();

            return response()->noContent();
        } catch (ModelNotFoundException) {
            return response()->json(
                [
                    'message' => "You don't have an inventory.",
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
