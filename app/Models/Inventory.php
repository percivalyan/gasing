<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventories';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inventory_number',
        'item_name',
        'description',
        'location_id',
        'status',
        'acquired_date',
        'value',
        'responsible_person',
        'notes',
    ];

    /**
     * Lokasi inventaris
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class, 'location_id');
    }

    /**
     * Riwayat peminjaman
     */
    public function loans(): HasMany
    {
        return $this->hasMany(InventoryLoan::class, 'inventory_id');
    }

    /**
     * Riwayat perawatan
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(InventoryMaintenance::class, 'inventory_id');
    }
}
