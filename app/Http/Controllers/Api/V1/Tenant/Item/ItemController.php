<?php

namespace App\Http\Controllers\Api\V1\Tenant\Item;

use App\Http\Controllers\Controller;
use App\Http\Requests\Item\ItemStoreRequest;
use App\Http\Requests\Item\ItemUpdateRequest;
use App\Http\Resources\Item\ItemResource;
use App\Models\Inventory;
use App\Models\Item;
use App\Traits\Image\HasImage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    use HasImage;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $pageNumber = $request->query('page_number', null);
            $search = $request->query('search', null);

            $items = Item::belongsToCurrentTenant()
                ->when($search !== null, fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
                ->latest()
                ->paginate(perPage: $perPage, page: $pageNumber);

            return ItemResource::collection($items);
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
     * Store a newly created resource in storage.
     */
    public function store(ItemStoreRequest $request)
    {
        try {
            $inventoryId = Inventory::belongsToCurrentTenant()->firstOrFail()->id;

            $image = $this->saveImage(
                prefix: 'item',
                name: $request->validated('name'),
                image: $request->validated('image'),
                other: time(),
                directory: 'items',
            );

            Item::create(
                [
                    ...$request->validated(),
                    'image' => $image,
                    'inventory_id' => $inventoryId,
                ]
            );

            return response()->json(
                [
                    'message' => 'Item added successfully.',
                ],
                Response::HTTP_CREATED
            );
        } catch (UniqueConstraintViolationException) {
            ! (isset($image) && file_exists($image))
            ?: unlink(public_path($image));

            return response()->json(
                [
                    'message' => 'Item with similar name already exists.',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (ModelNotFoundException) {

            return response()->json(
                [
                    'message' => "You don't have an inventory.",
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $th) {
            ! (isset($image) && file_exists($image))
            ?: unlink(public_path($image));

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
    public function show(string $id)
    {
        try {
            $item = Item::belongsToCurrentTenant()->findOrFail($id);

            return new ItemResource($item);
        } catch (ModelNotFoundException) {
            return response()->json(
                [
                    'message' => 'Item not Found.',
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
    public function update(ItemUpdateRequest $request, string $id)
    {
        try {
            $item = Item::belongsToCurrentTenant()->findOrFail($id);

            $validated = $request->validated();

            if (isset($validated['image'])) {
                $oldImage = $item->image;
                $image = $this->saveImage(
                    prefix: 'item',
                    name: $validated['name'],
                    image: $validated['image'],
                    other: time(),
                    directory: 'items',
                );

                $validated = [...$validated, 'image' => $image];
            }

            $item->update($validated);

            ! (isset($image) && file_exists($oldImage))
            ?: unlink(public_path($oldImage));

            return response()->json(
                [
                    'message' => 'Item information updated.',
                ],
                Response::HTTP_OK
            );
        } catch (ModelNotFoundException) {
            return response()->json(
                [
                    'message' => 'Item not Found.',
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (UniqueConstraintViolationException) {
            ! (isset($image) && file_exists($image))
            ?: unlink(public_path($image));

            return response()->json(
                [
                    'message' => 'Item with similar name already exists.',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (\Throwable $th) {
            ! (isset($image) && file_exists($image))
            ?: unlink(public_path($image));

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
    public function destroy(string $id)
    {
        try {
            $item = Item::belongsToCurrentTenant()->findOrFail($id);
            $image = $item->image;
            $item->delete();

            ! file_exists($image) ?: unlink(public_path($image));

            return response()->noContent();
        } catch (ModelNotFoundException) {
            return response()->json(
                [
                    'message' => 'Item not Found.',
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
