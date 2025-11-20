<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLoan extends Model
{
    use HasFactory;

    protected $table = 'inventory_loans';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inventory_id',
        'borrower_name',
        'borrower_contact',
        'loan_date',
        'return_date',
        'loan_status',
        'notes',
    ];

    /**
     * Barang yang dipinjam
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
}
