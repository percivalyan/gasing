<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\EventBatch;

class EventSchedule extends Model
{
    use HasFactory;

    protected $table = 'event_schedules';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'event_batch_id',
        'date',
        'day_of_week',
        'start_time',
        'end_time',
        'place',
        'agenda',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
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

    /** Relasi ke User (guru / pelatih / admin atau siapa saja) */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Relasi ke EventBatch */
    public function eventBatch(): BelongsTo
    {
        return $this->belongsTo(EventBatch::class, 'event_batch_id');
    }

    /** Contoh Accessor: format waktu lengkap */
    public function getFormattedTimeAttribute(): string
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
        }
        return '-';
    }
}
