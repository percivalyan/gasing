<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReferenceNumberTracker extends Model
{
    use HasFactory;

    protected $table = 'reference_number_trackers';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'letter_type_id', 'current_number'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function letterType()
    {
        return $this->belongsTo(LetterType::class);
    }
}
