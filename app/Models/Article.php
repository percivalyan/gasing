<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'slug',
        'title',
        'summary',
        'content',
        'image_path',
        'status',
        'category_id',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }

            if (empty($model->slug) && !empty($model->title)) {
                $model->slug = Str::slug($model->title) . '-' . Str::random(5);
            }
        });
    }

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relasi ke user (penulis)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper untuk ambil artikel yang sudah dipublish
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
