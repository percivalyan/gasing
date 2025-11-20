<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReferenceNumber extends Model
{
    use HasFactory;
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

    protected $table = 'reference_numbers';
    protected $fillable = [
        'letter_type_id',
        'serial_number',
        'institution',
        'month',
        'year',
        'ref',
        'user_id',
    ];

    public function letterType()
    {
        return $this->belongsTo(LetterType::class, 'letter_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
