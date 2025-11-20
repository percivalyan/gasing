<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentEvent extends Model
{
    use HasFactory;

    protected $table = 'student_events';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'name',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'origin_district',
        'school_level',
        'whatsapp_number',
        'dream',
        'school_origin',
        'photo',
        'letter_of_assignment',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke assignment teacher_student_events
    public function teacherStudentEvents(): HasMany
    {
        return $this->hasMany(TeacherStudentEvent::class, 'student_event_id', 'id');
    }
}
