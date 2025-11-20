<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventBatch extends Model
{
    use HasFactory;

    protected $table = 'event_batchs'; // tetap mengikuti nama migrasi
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false; // karena tidak ada created_at dan updated_at

    protected $fillable = [
        'event_year',
        'event_phase',
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

    /**
     * Relasi ke jadwal event
     */
    public function eventSchedules(): HasMany
    {
        return $this->hasMany(EventSchedule::class, 'event_batch_id');
    }

    /**
     * Contoh accessor untuk menampilkan nama batch lengkap
     * (misal "2025 - Tahap 1")
     */
    public function getFullBatchNameAttribute(): string
    {
        return "{$this->event_year} - {$this->event_phase}";
    }
}
