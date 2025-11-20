<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class StudentCourse extends Model
{
    use HasFactory;

    protected $table = 'student_courses';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'user_id',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'origin_district',
        'school_level',
        'whatsapp_number',
        'dream',
        'fee_note',
        'note',
        'school_origin',
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

    /**
     * Relasi ke User (owner student)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke teacher_student_courses (assignments)
     */
    public function teacherRelations()
    {
        return $this->hasMany(TeacherStudentCourse::class, 'student_course_id', 'id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'student_course_id', 'id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'course_id', 'id');
    }

    /**
     * Age attribute helper (hitung umur dari birth_date)
     */
    public function getAgeAttribute()
    {
        if (empty($this->birth_date)) {
            return null;
        }

        return \Carbon\Carbon::parse($this->birth_date)->age;
    }
}
