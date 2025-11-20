<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttendanceCourse extends Model
{
    protected $table = 'attendance_courses';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'teacher_id',
        'attendance_date',
        'checkin_at',
        'checkout_at',
        'checkin_lat',
        'checkin_lng',
        'checkin_accuracy',
        'status',
        'permission_type',
        'note',
        'checkin_ip',
        'photo',
        'method',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Relasi sederhana
    public function teacher()
    {
        return $this->belongsTo(\App\Models\User::class, 'teacher_id');
    }
}
