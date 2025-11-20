<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryLocation extends Model
{
    use HasFactory;

    protected $table = 'inventory_locations';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    /**
     * Relasi ke inventaris
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class, 'location_id');
    }
}
