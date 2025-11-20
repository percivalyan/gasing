<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrganizationMember extends Model
{
    use HasFactory;

    protected $table = 'organization_members';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'structure_id',
        'name',
        'order',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Relasi ke struktur organisasi
    public function structure()
    {
        return $this->belongsTo(OrganizationStructure::class, 'structure_id', 'id');
    }
}
