<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

// MODELS
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use App\Models\Document;
use App\Models\ReferenceNumber;
use App\Models\LetterType;
use App\Models\Gallery;
use App\Models\About;
use App\Models\OrganizationStructure;
use App\Models\OrganizationMember;
use App\Models\TeacherEvent;
use App\Models\StudentEvent;
use App\Models\StudentCourse;
use App\Models\LessonSchedule;
use App\Models\TeacherStudentCourse;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        // === COUNTS (gunakan try-catch agar aman bila tabel belum ada) ===
        $counts = [
            'roles'          => $this->safeCount(Role::class),
            'users'          => $this->safeCount(User::class),
            'categories'     => $this->safeCount(Category::class),
            'articles'       => $this->safeCount(Article::class),
            'documents'      => $this->safeCount(Document::class),
            'ref_numbers'    => $this->safeCount(ReferenceNumber::class),
            'letter_types'   => $this->safeCount(LetterType::class),
            'galleries'      => $this->safeCount(Gallery::class),
            'about'          => $this->safeCount(About::class),
            'org_structs'    => $this->safeCount(OrganizationStructure::class),
            'org_members'    => $this->safeCount(OrganizationMember::class),
            'teacher_events' => $this->safeCount(TeacherEvent::class),
            'student_events' => $this->safeCount(StudentEvent::class),
            'student_courses'=> $this->safeCount(StudentCourse::class),
            'lesson_scheds'  => $this->safeCount(LessonSchedule::class),
            'assigns'        => $this->safeCount(TeacherStudentCourse::class),
        ];

        // === RECENTS ===
        $recentArticles = $this->safeRecent(Article::class);
        $recentDocuments = $this->safeRecent(Document::class);
        $recentUsers = $this->safeRecent(User::class);

        // === EVENTS RINGKAS ===
        $upcomingTeacherEvents = $this->safeUpcoming(TeacherEvent::class, 'event_date', $today);
        $upcomingStudentEvents = $this->safeUpcoming(StudentEvent::class, 'event_date', $today);

        // === Jadwal Mengajar Hari Ini (jika ada kolom date_at) ===
        $todaySchedules = $this->safeWhereDate(LessonSchedule::class, 'date_at', $today);

        // === Izinkan menu berdasarkan permission ===
        $roleId = optional(Auth::user())->role_id;
        $can = [
            'User'        => $this->can('User', $roleId),
            'Article'     => $this->can('Article', $roleId),
            'Document'    => $this->can('Document', $roleId),
            'Gallery'     => $this->can('Gallery', $roleId),
            'Organization'=> $this->can('Organization', $roleId),
            'Student'     => $this->can('Student', $roleId),
            'Teacher'     => $this->can('Teacher', $roleId),
            'Assign'      => $this->can('Assign', $roleId),
            'Lesson'      => $this->can('Lesson', $roleId),
        ];

        return view('panel.dashboard', compact(
            'counts',
            'recentArticles', 'recentDocuments', 'recentUsers',
            'upcomingTeacherEvents', 'upcomingStudentEvents',
            'todaySchedules', 'can'
        ));
    }

    // === Helpers aman ===
    private function safeCount($model)
    {
        try { return $model::count(); } catch (\Throwable $e) { return 0; }
    }

    private function safeRecent($model, $limit = 5)
    {
        try { return $model::orderByDesc('created_at')->limit($limit)->get(); }
        catch (\Throwable $e) { return collect(); }
    }

    private function safeUpcoming($model, $dateColumn, $fromDate, $limit = 5)
    {
        try {
            return $model::whereDate($dateColumn, '>=', $fromDate)
                ->orderBy($dateColumn)
                ->limit($limit)
                ->get();
        } catch (\Throwable $e) { return collect(); }
    }

    private function safeWhereDate($model, $dateColumn, $date)
    {
        try {
            return $model::whereDate($dateColumn, $date)->orderBy('created_at', 'desc')->get();
        } catch (\Throwable $e) { return collect(); }
    }

    private function can($slug, $roleId)
    {
        try { return PermissionRole::getPermission($slug, $roleId) > 0; }
        catch (\Throwable $e) { return false; }
    }
}