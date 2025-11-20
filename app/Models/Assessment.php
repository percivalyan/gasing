<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Assessment extends Model
{
    use HasFactory;

    protected $table = 'assessments';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'teacher_student_course_id',
        'subject_id',
        'assessor_id',
        'score',
        'notes',
        'assessment_date',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    /** RELATIONS **/

    // relasi ke teacher_student_course (pairing teacher <-> student_course)
    public function teacherStudentCourse()
    {
        return $this->belongsTo(TeacherStudentCourse::class, 'teacher_student_course_id', 'id');
    }

    // relasi ke subject
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    // siapa yang melakukan penilaian (bisa guru atau admin)
    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id', 'id');
    }

    // convenience: get teacher (via teacherStudentCourse)
    public function teacher()
    {
        return $this->teacherStudentCourse ? $this->teacherStudentCourse->teacher() : null;
    }

    // convenience: get studentCourse
    public function studentCourse()
    {
        return $this->teacherStudentCourse ? $this->teacherStudentCourse->studentCourse() : null;
    }

    // convenience: get student User (via studentCourse)
    public function student()
    {
        if ($this->teacherStudentCourse && $this->teacherStudentCourse->studentCourse) {
            return $this->teacherStudentCourse->studentCourse->user ?? null;
        }
        return null;
    }
}
