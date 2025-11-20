<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherStudentEvent extends Model
{
    use HasFactory;

    protected $table = 'teacher_student_events';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'teacher_event_id',
        'student_event_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi ke TeacherEvent
     */
    public function teacherEvent(): BelongsTo
    {
        return $this->belongsTo(TeacherEvent::class, 'teacher_event_id');
    }

    /**
     * Relasi ke StudentEvent
     */
    public function studentEvent(): BelongsTo
    {
        return $this->belongsTo(StudentEvent::class, 'student_event_id');
    }
}
