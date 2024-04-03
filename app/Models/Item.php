<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'quantity', 'inventory_id'];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function scopeBelongsToCurrentTenant(Builder $query): void
    {
        $query->where('inventory_id', Inventory::belongsToCurrentTenant()->firstOrFail()->id);
    }
}
