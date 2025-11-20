<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TeacherStudentCourse extends Model
{
    use HasFactory;

    protected $table = 'teacher_student_courses';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'teacher_id',
        'student_course_id',
        'start_date',
        'end_date',
        'status',
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
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function studentCourse()
    {
        return $this->belongsTo(StudentCourse::class, 'student_course_id', 'id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'teacher_student_course_id', 'id');
    }
}
