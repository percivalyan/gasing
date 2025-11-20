<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrganizationStructure extends Model
{
    use HasFactory;

    protected $table = 'organization_structures';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'position',
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

    // Relasi: satu posisi punya banyak anggota
    public function members()
    {
        return $this->hasMany(OrganizationMember::class, 'structure_id', 'id')->orderBy('order', 'asc');
    }
}
