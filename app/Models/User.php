<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'nip',
        'expertise_field',
        'last_education',
        'whatsapp_number',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'birth_date'        => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->{$user->getKeyName()})) {
                $user->{$user->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // RELATIONS
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * StudentCourse: seorang user (student) bisa punya banyak student_courses
     */
    public function studentCourses(): HasMany
    {
        return $this->hasMany(StudentCourse::class, 'user_id', 'id');
    }

    public function studentEvents(): HasMany
    {
        return $this->hasMany(StudentEvent::class, 'user_id', 'id');
    }

    public function teacherEvents(): HasMany
    {
        return $this->hasMany(TeacherEvent::class, 'user_id', 'id');
    }

    public function teacherStudentCourses(): HasMany
    {
        return $this->hasMany(TeacherStudentCourse::class, 'teacher_id', 'id');
    }

    public function lessonSchedules(): HasMany
    {
        return $this->hasMany(LessonSchedule::class, 'teacher_id', 'id');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'teacher_id', 'id');
    }

    public function assessmentsAuthored(): HasMany
    {
        return $this->hasMany(Assessment::class, 'assessor_id', 'id');
    }

    /**
     * assessmentsReceived:
     * karena struktur data : assessments -> teacher_student_course -> student_course -> user,
     * kita kembalikan query builder sehingga pemanggilan tetap fleksibel.
     */
    public function assessmentsReceived()
    {
        $studentCourseIds = $this->studentCourses()->pluck('id')->toArray();

        if (empty($studentCourseIds)) {
            return Assessment::query()->whereRaw('0 = 1');
        }

        $teacherStudentCourseIds = TeacherStudentCourse::whereIn('student_course_id', $studentCourseIds)
            ->pluck('id')
            ->toArray();

        if (empty($teacherStudentCourseIds)) {
            return Assessment::query()->whereRaw('0 = 1');
        }

        return Assessment::whereIn('teacher_student_course_id', $teacherStudentCourseIds);
    }

    // Optional: helper untuk mengetahui assign teacher <-> student_event (jika dibutuhkan)
    public function teacherStudentEventsAsTeacher()
    {
        // return teacher_student_events records for teacher via teacher_event -> user relation
        // biasanya kamu akan mengakses lewat TeacherEvent model; ini helper tambahan bila perlu.
        return TeacherStudentEvent::whereIn('teacher_event_id', TeacherEvent::where('user_id', $this->id)->pluck('id')->toArray());
    }

    // STATIC HELPERS
    public static function getSingle($id)
    {
        return self::find($id);
    }

    public static function getRecord()
    {
        return self::select('users.*', 'roles.name as role_name')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->orderBy('users.id', 'desc')
            ->get();
    }

    public function canAssessStudent(string $studentUserId): bool
    {
        if ($this->role && strtolower($this->role->name) === 'administrator') {
            return true;
        }

        $studentCourseIds = StudentCourse::where('user_id', $studentUserId)->pluck('id')->toArray();
        if (empty($studentCourseIds)) {
            return false;
        }

        return TeacherStudentCourse::where('teacher_id', $this->id)
            ->whereIn('student_course_id', $studentCourseIds)
            ->exists();
    }
}
