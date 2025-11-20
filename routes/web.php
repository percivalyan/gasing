<?php

use Illuminate\Support\Facades\Route;
// Import Ctrl
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReferenceNumberController;
use App\Http\Controllers\LetterTypeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SessionLogController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\OrganizationStructureController;
use App\Http\Controllers\OrganizationMemberController;
use App\Http\Controllers\TeacherEventController;
use App\Http\Controllers\StudentEventController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\LessonScheduleController;
use App\Http\Controllers\TeacherStudentCourseController;
use App\Http\Controllers\TeacherPickerController;
use App\Http\Controllers\StudentCoursePickerController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AttendanceCourseController;
use App\Http\Controllers\TeacherStudentEventController;
use App\Http\Controllers\TeacherEventPickerController;
use App\Http\Controllers\StudentEventPickerController;
use App\Http\Controllers\EventScheduleController;
use App\Http\Controllers\LandingPageController;

// Landing Page
Route::get('/ar', [LandingPageController::class, 'all']);
Route::get('/ar/{slug}', [LandingPageController::class, 'detail']);
Route::get('/doc', [LandingPageController::class, 'documents'])->name('public.documents');

// =============================
// PUBLIC ROUTES (NO MIDDLEWARE)
// =============================

// Landing page -> welcome.blade.php
Route::get('/', [LandingPageController::class, 'index'])
    ->name('landing');

// Login form
Route::get('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login');

// Proses login
Route::post('/login', [AuthController::class, 'auth_login'])
    ->middleware('guest')
    ->name('login.process');

// Logout (sebaiknya via POST, tapi disesuaikan dengan project Anda)
// Jika di view masih pake <a href="{{ url('logout') }}">, biarkan GET dulu:
Route::get('logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =============================
// PROTECTED ROUTES (useradmin)
// =============================

Route::group(['middleware' => 'useradmin'], function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('session-logs', [SessionLogController::class, 'index'])->name('session.logs');
    Route::delete('session-logs/delete/{id}', [SessionLogController::class, 'destroy'])->name('session.logs.delete');

    Route::get('role', [RoleController::class, 'list']);
    Route::get('role/add', [RoleController::class, 'add']);
    Route::post('role/store', [RoleController::class, 'insert']);
    Route::get('role/edit/{id}', [RoleController::class, 'edit']);
    Route::post('role/edit/{id}', [RoleController::class, 'update']);
    Route::get('role/delete/{id}', [RoleController::class, 'delete']);

    Route::get('user', [UserController::class, 'list']);
    Route::get('user/add', [UserController::class, 'add']);
    Route::post('user/store', [UserController::class, 'insert']);
    Route::get('user/edit/{id}', [UserController::class, 'edit']);
    Route::post('user/edit/{id}', [UserController::class, 'update']);
    Route::get('user/delete/{id}', [UserController::class, 'delete']);
    Route::get('user/edit-profile/{id}', [UserController::class, 'editProfile'])->name('user.edit-profile');
    Route::post('user/edit-profile/{id}', [UserController::class, 'updateProfile']);

    Route::get('/guru-les-gasing', [UserController::class, 'listGuruLesGasing'])->name('user.list-guru-les-gasing');
    Route::get('/guru-les-gasing/create', [UserController::class, 'createGuruLesGasing'])->name('user.guru-les-gasing.create');
    Route::post('/guru-les-gasing/store', [UserController::class, 'storeGuruLesGasing'])->name('user.guru-les-gasing.store');
    Route::get('/guru-les-gasing/{id}/edit', [UserController::class, 'editGuruLesGasing'])->name('user.guru-les-gasing.edit');
    Route::post('/guru-les-gasing/{id}/update', [UserController::class, 'updateGuruLesGasing'])->name('user.guru-les-gasing.update');
    Route::delete('/guru-les-gasing/{id}/delete', [UserController::class, 'deleteGuruLesGasing'])
        ->name('user.guru-les-gasing.delete');

    Route::get('category', [CategoryController::class, 'list']);
    Route::get('category/add', [CategoryController::class, 'add']);
    Route::post('category/store', [CategoryController::class, 'insert']);
    Route::get('category/edit/{id}', [CategoryController::class, 'edit']);
    Route::post('category/edit/{id}', [CategoryController::class, 'update']);
    Route::get('category/delete/{id}', [CategoryController::class, 'delete']);

    Route::get('article', [ArticleController::class, 'list']);
    Route::get('article/add', [ArticleController::class, 'add']);
    Route::post('article/store', [ArticleController::class, 'insert']);
    Route::get('article/edit/{id}', [ArticleController::class, 'edit']);
    Route::post('article/edit/{id}', [ArticleController::class, 'update']);
    Route::get('article/delete/{id}', [ArticleController::class, 'delete']);

    Route::get('document', [DocumentController::class, 'list']);
    Route::get('document/add', [DocumentController::class, 'add']);
    Route::post('document/store', [DocumentController::class, 'insert']);
    Route::get('document/edit/{id}', [DocumentController::class, 'edit']);
    Route::post('document/edit/{id}', [DocumentController::class, 'update']);
    Route::get('document/delete/{id}', [DocumentController::class, 'delete']);

    Route::get('referencenumber', [ReferenceNumberController::class, 'list'])->name('referencenumber.list');
    Route::get('referencenumber/add', [ReferenceNumberController::class, 'add'])->name('referencenumber.add');
    Route::post('referencenumber/insert', [ReferenceNumberController::class, 'insert'])->name('referencenumber.insert');
    Route::post('referencenumber/reset', [ReferenceNumberController::class, 'reset'])->name('referencenumber.reset');
    Route::get('referencenumber/delete/{id}', [ReferenceNumberController::class, 'delete'])
        ->name('referencenumber.delete');

    Route::get('lettertype', [LetterTypeController::class, 'list']);
    Route::get('lettertype/add', [LetterTypeController::class, 'add']);
    Route::post('lettertype/store', [LetterTypeController::class, 'insert']);
    Route::get('lettertype/edit/{id}', [LetterTypeController::class, 'edit']);
    Route::post('lettertype/edit/{id}', [LetterTypeController::class, 'update']);
    Route::get('lettertype/delete/{id}', [LetterTypeController::class, 'delete']);

    Route::get('gallery', [GalleryController::class, 'list'])->name('gallery.list');
    Route::get('gallery/add', [GalleryController::class, 'add'])->name('gallery.add');
    Route::post('gallery/insert', [GalleryController::class, 'insert'])->name('gallery.insert');
    Route::get('gallery/edit/{id}', [GalleryController::class, 'edit'])->name('gallery.edit');
    Route::post('gallery/update/{id}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::get('gallery/delete/{id}', [GalleryController::class, 'delete'])->name('gallery.delete');

    Route::get('about', [AboutController::class, 'list'])->name('about.list');
    Route::get('about/edit/{id}', [AboutController::class, 'edit'])->name('about.edit');
    Route::post('about/edit/{id}', [AboutController::class, 'update'])->name('about.update');
    Route::get('about/delete/{id}', [AboutController::class, 'delete'])->name('about.delete');

    Route::get('organization/structure', [OrganizationStructureController::class, 'list'])->name('organization.structure.list');
    Route::get('organization/structure/add', [OrganizationStructureController::class, 'add'])->name('organization.structure.add');
    Route::post('organization/structure/add', [OrganizationStructureController::class, 'insert'])->name('organization.structure.insert');
    Route::get('organization/structure/edit/{id}', [OrganizationStructureController::class, 'edit'])->name('organization.structure.edit');
    Route::post('organization/structure/edit/{id}', [OrganizationStructureController::class, 'update'])->name('organization.structure.update');
    Route::get('organization/structure/delete/{id}', [OrganizationStructureController::class, 'delete'])->name('organization.structure.delete');

    Route::get('organization/member', [OrganizationMemberController::class, 'list'])->name('organization.member.list');
    Route::get('organization/member/add', [OrganizationMemberController::class, 'add'])->name('organization.member.add');
    Route::post('organization/member/add', [OrganizationMemberController::class, 'insert'])->name('organization.member.insert');
    Route::get('organization/member/edit/{id}', [OrganizationMemberController::class, 'edit'])->name('organization.member.edit');
    Route::post('organization/member/edit/{id}', [OrganizationMemberController::class, 'update'])->name('organization.member.update');
    Route::get('organization/member/delete/{id}', [OrganizationMemberController::class, 'delete'])->name('organization.member.delete');

    Route::get('organization', function () {
        return redirect()->back();
    });

    Route::get('teacher-event', [TeacherEventController::class, 'list'])->name('teacher_event.list');
    Route::get('teacher-event/add', [TeacherEventController::class, 'add'])->name('teacher_event.add');
    Route::post('teacher-event/insert', [TeacherEventController::class, 'insert'])->name('teacher_event.insert');
    Route::get('teacher-event/edit/{id}', [TeacherEventController::class, 'edit'])->name('teacher_event.edit');
    Route::post('teacher-event/update/{id}', [TeacherEventController::class, 'update'])->name('teacher_event.update');
    Route::get('teacher-event/delete/{id}', [TeacherEventController::class, 'delete'])->name('teacher_event.delete');
    Route::get('teacher-event/formregistration', [TeacherEventController::class, 'formregistration'])->name('teacher_event.formregistration');
    Route::post('teacher-event/registration', [TeacherEventController::class, 'registration'])->name('teacher_event.registration');

    Route::get('student-event', [StudentEventController::class, 'list'])->name('student_event.list');
    Route::get('student-event/add', [StudentEventController::class, 'add'])->name('student_event.add');
    Route::post('student-event/insert', [StudentEventController::class, 'insert'])->name('student_event.insert');
    Route::get('student-event/edit/{id}', [StudentEventController::class, 'edit'])->name('student_event.edit');
    Route::post('student-event/update/{id}', [StudentEventController::class, 'update'])->name('student_event.update');
    Route::get('student-event/delete/{id}', [StudentEventController::class, 'delete'])->name('student_event.delete');
    Route::get('student-event/formregistration', [StudentEventController::class, 'formregistration'])->name('student_event.formregistration');
    Route::post('student-event/registration', [StudentEventController::class, 'registration'])->name('student_event.registration');

    Route::get('student-course', [StudentCourseController::class, 'list'])->name('student_course.list');
    Route::get('student-course/add', [StudentCourseController::class, 'add'])->name('student_course.add');
    Route::post('student-course/add', [StudentCourseController::class, 'insert'])->name('student_course.insert');
    Route::get('student-course/edit/{id}', [StudentCourseController::class, 'edit'])->name('student_course.edit');
    Route::post('student-course/edit/{id}', [StudentCourseController::class, 'update'])->name('student_course.update');
    Route::get('student-course/delete/{id}', [StudentCourseController::class, 'delete'])->name('student_course.delete');

    // Lesson Schedule
    Route::get('lesson-schedule', [LessonScheduleController::class, 'list'])->name('lesson_schedule.list');
    Route::get('lesson-schedule/add', [LessonScheduleController::class, 'add'])->name('lesson_schedule.add');
    Route::post('lesson-schedule', [LessonScheduleController::class, 'insert'])->name('lesson_schedule.insert');

    Route::get('lesson-schedule/{id}/edit', [LessonScheduleController::class, 'edit'])->name('lesson_schedule.edit');
    Route::put('lesson-schedule/{id}', [LessonScheduleController::class, 'update'])->name('lesson_schedule.update');

    Route::delete('lesson-schedule/{id}', [LessonScheduleController::class, 'delete'])->name('lesson_schedule.delete');

    // Subject (panel)
    Route::resource('subjects', SubjectController::class)->except(['show']);

    // Endpoint AJAX / inline create untuk subject (dipakai pada form Lesson)
    Route::post('subjects/create-inline', [SubjectController::class, 'storeInline'])
        ->name('subjects.store_inline');

    // === Assign (tanpa prefix & tanpa group) ===
    Route::get('assign',                [TeacherStudentCourseController::class, 'index'])->name('assign.index');
    Route::get('assign/create',         [TeacherStudentCourseController::class, 'create'])->name('assign.create');
    Route::post('assign/store',         [TeacherStudentCourseController::class, 'store'])->name('assign.store');

    Route::get('assign/{id}/edit',      [TeacherStudentCourseController::class, 'edit'])->name('assign.edit');
    Route::post('assign/{id}/update',   [TeacherStudentCourseController::class, 'update'])->name('assign.update');
    Route::post('assign/{id}/toggle',   [TeacherStudentCourseController::class, 'toggleStatus'])->name('assign.toggle');
    Route::delete('assign/{id}',        [TeacherStudentCourseController::class, 'delete'])->name('assign.delete');

    // === Picker (tanpa prefix & tanpa group) ===
    // Guru
    Route::get('picker/teachers',        [TeacherPickerController::class, 'index'])->name('picker.teachers');
    Route::get('picker/teachers/search', [TeacherPickerController::class, 'search'])->name('picker.teachers.search');
    // Siswa
    // Route::get('picker/students',        [StudentCoursePickerController::class, 'index'])->name('picker.students');
    // Route::get('picker/students/search', [StudentCoursePickerController::class, 'search'])->name('picker.students.search');

    // Assessment routes
    Route::get('assessments',                 [AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('assessments/create',          [AssessmentController::class, 'create'])->name('assessments.create');
    Route::post('assessments/store',          [AssessmentController::class, 'store'])->name('assessments.store');

    Route::get('assessments/{id}',            [AssessmentController::class, 'show'])->name('assessments.show');
    Route::get('assessments/{id}/edit',       [AssessmentController::class, 'edit'])->name('assessments.edit');
    Route::post('assessments/{id}/update',    [AssessmentController::class, 'update'])->name('assessments.update');
    Route::delete('assessments/{id}',         [AssessmentController::class, 'delete'])->name('assessments.delete');

    // LIST untuk admin & guru
    Route::get('attendance', [AttendanceCourseController::class, 'index'])
        ->name('attendance.index');

    // AUTO CHECKIN (dipanggil saat login)
    Route::post('attendance/checkin', [AttendanceCourseController::class, 'autoCheckin'])
        ->name('attendance.auto_checkin');

    // CHECKOUT
    Route::post('attendance/checkout', [AttendanceCourseController::class, 'checkout'])
        ->name('attendance.checkout');

    // ADMIN: Simpan manual (create/update)
    Route::post('attendance/manual/store', [AttendanceCourseController::class, 'storeManual'])
        ->name('attendance.store_manual');

    // ADMIN: Edit form
    Route::get('attendance/{id}/edit', [AttendanceCourseController::class, 'edit'])
        ->name('attendance.edit');

    // ADMIN: Update
    Route::post('attendance/{id}/update', [AttendanceCourseController::class, 'update'])
        ->name('attendance.update');

    // ADMIN: Delete
    Route::delete('attendance/{id}', [AttendanceCourseController::class, 'destroy'])
        ->name('attendance.destroy');

    // === Assign teacher_event ↔ student_event ===
    Route::get('event-assign',                [TeacherStudentEventController::class, 'index'])->name('event-assign.index');
    Route::get('event-assign/create',         [TeacherStudentEventController::class, 'create'])->name('event-assign.create');
    Route::post('event-assign/store',         [TeacherStudentEventController::class, 'store'])->name('event-assign.store');

    Route::get('event-assign/{id}/edit',      [TeacherStudentEventController::class, 'edit'])->name('event-assign.edit');
    Route::post('event-assign/{id}/update',   [TeacherStudentEventController::class, 'update'])->name('event-assign.update');
    Route::post('event-assign/{id}/toggle',   [TeacherStudentEventController::class, 'toggleStatus'])->name('event-assign.toggle');
    Route::delete('event-assign/{id}',        [TeacherStudentEventController::class, 'delete'])->name('event-assign.delete');

    // === Picker ===
    // TeacherEvent
    Route::get('picker/teacher-events',        [TeacherEventPickerController::class, 'index'])->name('picker.teacher_events');
    Route::get('picker/teacher-events/search', [TeacherEventPickerController::class, 'search'])->name('picker.teacher_events.search');
    // StudentEvent
    Route::get('picker/student-events',        [StudentEventPickerController::class, 'index'])->name('picker.student_events');
    Route::get('picker/student-events/search', [StudentEventPickerController::class, 'search'])->name('picker.student_events.search');

    // Event Schedule
    Route::get('event-schedule', [EventScheduleController::class, 'list'])->name('event_schedule.list');
    Route::get('event-schedule/add', [EventScheduleController::class, 'add'])->name('event_schedule.add');
    Route::post('event-schedule', [EventScheduleController::class, 'insert'])->name('event_schedule.insert');
    Route::get('event-schedule/{id}/edit', [EventScheduleController::class, 'edit'])->name('event_schedule.edit');
    Route::put('event-schedule/{id}', [EventScheduleController::class, 'update'])->name('event_schedule.update');
    Route::delete('event-schedule/{id}', [EventScheduleController::class, 'delete'])->name('event_schedule.delete');

    // Event Batch
    Route::get('event-batch', [App\Http\Controllers\EventBatchController::class, 'list'])->name('event_batch.list');
    Route::get('event-batch/add', [App\Http\Controllers\EventBatchController::class, 'add'])->name('event_batch.add');
    Route::post('event-batch/insert', [App\Http\Controllers\EventBatchController::class, 'insert'])->name('event_batch.insert');
    Route::get('event-batch/edit/{id}', [App\Http\Controllers\EventBatchController::class, 'edit'])->name('event_batch.edit');
    Route::post('event-batch/update/{id}', [App\Http\Controllers\EventBatchController::class, 'update'])->name('event_batch.update');
    Route::get('event-batch/delete/{id}', [App\Http\Controllers\EventBatchController::class, 'delete'])->name('event_batch.delete');
});
