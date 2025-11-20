<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
    ];

    // Relasi ke artikel
    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}
