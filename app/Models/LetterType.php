<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterType extends Model
{
    use HasFactory;

    protected $table = 'letter_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'subject',
        'code',
    ];

    // Relasi ke ReferenceNumber
    public function referenceNumbers()
    {
        return $this->hasMany(ReferenceNumber::class, 'letter_type_id');
    }
}
