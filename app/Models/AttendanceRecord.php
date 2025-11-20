<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $table = 'attendance_records';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'schedule_id',
        'student_course_id',
        'teacher_id',
        'date',
        'status',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /** RELASI **/
    public function schedule()
    {
        return $this->belongsTo(LessonSchedule::class, 'schedule_id', 'id');
    }

    public function studentCourse()
    {
        return $this->belongsTo(StudentCourse::class, 'student_course_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }
}
