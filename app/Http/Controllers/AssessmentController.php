<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\TeacherStudentCourse;
use App\Models\Subject;
use App\Models\User;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogger;
use Carbon\Carbon;

class AssessmentController extends Controller
{
    /**
     * LIST
     */
    /**
     * Daftar penilaian (index)
     */
    public function index(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assessment', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Assessment', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Assessment', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Assessment', Auth::user()->role_id);

        $teacherId       = $request->input('teacher_id');
        $studentCourseId = $request->input('student_course_id');
        $subjectId       = $request->input('subject_id');
        $dateFrom        = $request->input('date_from');
        $dateTo          = $request->input('date_to');

        $q = Assessment::with([
            'teacherStudentCourse.teacher.role',
            'teacherStudentCourse.studentCourse',
            'subject',
            'assessor'
        ])
            ->when($teacherId, fn($qq) => $qq->whereHas('teacherStudentCourse', fn($t) => $t->where('teacher_id', $teacherId)))
            ->when($studentCourseId, fn($qq) => $qq->whereHas('teacherStudentCourse', fn($t) => $t->where('student_course_id', $studentCourseId)))
            ->when($subjectId, fn($qq) => $qq->where('subject_id', $subjectId))
            ->when($dateFrom, fn($qq) => $qq->where('assessment_date', '>=', $dateFrom))
            ->when($dateTo, fn($qq) => $qq->where('assessment_date', '<=', $dateTo))
            ->orderBy('assessment_date', 'desc');

        $data['records']  = $q->paginate(20)->appends($request->query());
        $data['teachers'] = User::whereHas('role', fn($r) => $r->where('name', 'Guru Les Gasing'))->orderBy('name')->get();
        $data['students'] = TeacherStudentCourse::with('studentCourse')->get();
        $data['subjects'] = Subject::orderBy('name')->get();
        $data['selected'] = compact('teacherId', 'studentCourseId', 'subjectId', 'dateFrom', 'dateTo');

        ActivityLogger::log('READ', 'Melihat daftar penilaian');

        return view('panel.assessments.index', $data);
    }

    /**
     * FORM CREATE
     */
    public function create(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Assessment', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['teacherStudentCourses'] = TeacherStudentCourse::with('studentCourse', 'teacher')
            ->where('status', 'Active')
            ->orderBy('created_at', 'desc')
            ->get();

        $data['subjects'] = Subject::orderBy('name')->get();

        ActivityLogger::log('READ', 'Membuka form penilaian baru');

        return view('panel.assessments.create', $data);
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Assessment', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $validated = $request->validate([
            'teacher_student_course_id' => ['required', 'exists:teacher_student_courses,id'],
            'subject_id'                => ['nullable', 'exists:subjects,id'],
            'score'                     => ['nullable', 'integer', 'between:0,100'],
            'notes'                     => ['nullable', 'string'],
            'assessment_date'           => ['required', 'date'],
        ]);

        $assessor = Auth::user();
        $tsc = TeacherStudentCourse::findOrFail($validated['teacher_student_course_id']);

        // jika assessor adalah Guru Les Gasing: pastikan dia pemilik assignment
        if ($assessor->role && strtolower($assessor->role->name) !== 'administrator') {
            // cek role name Guru Les Gasing
            $isTeacherGasing = $assessor->role && strtolower($assessor->role->name) === strtolower('Guru Les Gasing');
            if ($isTeacherGasing && $tsc->teacher_id !== $assessor->id) {
                return back()->withErrors(['teacher_student_course_id' => 'Anda tidak berwenang menilai pasangan guru-siswa ini.'])->withInput();
            }

            // jika bukan admin dan bukan guru gasing -> forbidden
            if (!$isTeacherGasing) {
                abort(403, 'Role Anda tidak diizinkan melakukan penilaian.');
            }
        }

        DB::transaction(function () use ($validated, $assessor, $tsc) {
            // Update jika sudah ada penilaian untuk kombinasi yang sama pada assessment_date, atau buat baru
            $attrs = [
                'teacher_student_course_id' => $validated['teacher_student_course_id'],
                'subject_id'                => $validated['subject_id'] ?? null,
                'assessment_date'           => $validated['assessment_date'],
            ];

            $values = [
                'assessor_id' => $assessor->id,
                'score'       => $validated['score'] ?? null,
                'notes'       => $validated['notes'] ?? null,
            ];

            $assessment = Assessment::updateOrCreate($attrs, $values);

            ActivityLogger::log('CREATE', 'Membuat/Update penilaian: ' . $assessment->id);
        });

        return redirect()->route('assessments.index')->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * SHOW
     */
    public function show($id)
    {
        $PermissionRole = PermissionRole::getPermission('Assessment', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = Assessment::with(['teacherStudentCourse.teacher', 'teacherStudentCourse.studentCourse.user', 'subject', 'assessor'])
            ->findOrFail($id);

        ActivityLogger::log('READ', 'Melihat detail penilaian: ' . $id);

        return view('panel.assessments.show', ['record' => $record]);
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Assessment', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = Assessment::findOrFail($id);
        $assessor = Auth::user();

        // jika bukan admin, hanya assessor sendiri atau teacher yang terkait boleh edit
        if (!($assessor->role && strtolower($assessor->role->name) === 'administrator')) {
            // jika bukan assessor dan juga bukan teacher yang ter-assign -> forbidden
            $isAssessor = $record->assessor_id === $assessor->id;
            $isAssignedTeacher = ($record->teacherStudentCourse && $record->teacherStudentCourse->teacher_id === $assessor->id);
            if (!($isAssessor || $isAssignedTeacher)) {
                abort(403, 'Anda tidak diizinkan mengedit penilaian ini.');
            }
        }

        $data['record'] = $record;
        $data['subjects'] = Subject::orderBy('name')->get();

        ActivityLogger::log('READ', 'Membuka edit penilaian: ' . $id);

        return view('panel.assessments.edit', $data);
    }

    /**
     * UPDATE
     */
    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Assessment', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = Assessment::findOrFail($id);
        $assessor = Auth::user();

        // permission check same as edit
        if (!($assessor->role && strtolower($assessor->role->name) === 'administrator')) {
            $isAssessor = $record->assessor_id === $assessor->id;
            $isAssignedTeacher = ($record->teacherStudentCourse && $record->teacherStudentCourse->teacher_id === $assessor->id);
            if (!($isAssessor || $isAssignedTeacher)) {
                abort(403, 'Anda tidak diizinkan mengubah penilaian ini.');
            }
        }

        $validated = $request->validate([
            'subject_id'      => ['nullable', 'exists:subjects,id'],
            'score'           => ['nullable', 'integer', 'between:0,100'],
            'notes'           => ['nullable', 'string'],
            'assessment_date' => ['required', 'date'],
        ]);

        $record->fill([
            'subject_id'      => $validated['subject_id'] ?? null,
            'score'           => $validated['score'] ?? null,
            'notes'           => $validated['notes'] ?? null,
            'assessment_date' => $validated['assessment_date'],
        ]);
        $record->assessor_id = $assessor->id; // update who edited last
        $record->save();

        ActivityLogger::log('UPDATE', 'Update penilaian: ' . $record->id);

        return redirect()->route('assessments.index')->with('success', 'Penilaian berhasil diperbarui.');
    }

    /**
     * DELETE
     */
    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Assessment  ', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = Assessment::findOrFail($id);
        $assessor = Auth::user();

        // hanya admin atau assessor atau teacher terkait yang bisa menghapus
        if (!($assessor->role && strtolower($assessor->role->name) === 'administrator')) {
            $isAssessor = $record->assessor_id === $assessor->id;
            $isAssignedTeacher = ($record->teacherStudentCourse && $record->teacherStudentCourse->teacher_id === $assessor->id);
            if (!($isAssessor || $isAssignedTeacher)) {
                abort(403, 'Anda tidak diizinkan menghapus penilaian ini.');
            }
        }

        $record->delete();

        ActivityLogger::log('DELETE', 'Hapus penilaian: ' . $id);

        return back()->with('success', 'Penilaian berhasil dihapus.');
    }
}
