<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LessonSchedule extends Model
{
    use HasFactory;

    protected $table = 'lesson_schedules';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'teacher_id',
        'school_level',
        'subject_id',    // uuid ke tabel subjects (nullable)
        'subject_name',  // jika ingin input langsung tanpa memilih subject
        'day_of_week',
        'start_time',
        'end_time',
        'room',
    ];

    protected $casts = [
        // Simpan sebagai string, jika kamu mau bisa cast ke datetime dengan format tertentu
        'start_time' => 'string',
        'end_time' => 'string',
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

    /** RELASI **/
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'schedule_id', 'id');
    }

    /**
     * Helper: tampilkan nama subject (jika dipilih dari tabel subjects pakai relasi,
     * kalau tidak pakai subject_name yang diisi manual)
     */
    public function getSubjectDisplayAttribute()
    {
        if ($this->subject) {
            return $this->subject->name;
        }

        return $this->subject_name ?: null;
    }
}
