<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'galleries';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Auto-generate UUID saat create
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $fillable = [
        'id',
        'title',
        'slug',
        'description',
        'user_id',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke gambar
    public function images()
    {
        return $this->hasMany(GalleryImage::class, 'gallery_id', 'id');
    }
}
