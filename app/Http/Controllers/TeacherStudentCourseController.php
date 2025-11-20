<?php

namespace App\Http\Controllers;

use App\Models\TeacherStudentCourse;
use App\Models\StudentCourse;
use App\Models\User;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogger;
use Carbon\Carbon;

class TeacherStudentCourseController extends Controller
{
    /** =========================
     *  LIST & FILTER
     *  ========================= */
    public function index(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $teacherId = $request->input('teacher_id');
        $studentId = $request->input('student_course_id');
        $status    = $request->input('status');

        $q = TeacherStudentCourse::with(['teacher.role', 'studentCourse'])
            ->when($teacherId, fn($qq) => $qq->where('teacher_id', $teacherId))
            ->when($studentId, fn($qq) => $qq->where('student_course_id', $studentId))
            ->when($status, fn($qq) => $qq->where('status', $status))
            ->orderBy('created_at', 'desc');

        $data['records']  = $q->paginate(25)->appends($request->query());
        $data['teachers'] = User::whereHas('role', fn($r) => $r->where('name', 'Guru Les Gasing'))
            ->orderBy('name')->get();
        $data['students'] = StudentCourse::orderBy('name')->get();
        $data['selected'] = compact('teacherId', 'studentId', 'status');

        ActivityLogger::log('READ', 'Melihat daftar penugasan guru-siswa');

        return view('panel.teacher_assign.index', $data);
    }

    /** =========================
     *  FORM: PILIH GURU → CHECKLIST SISWA
     *  ========================= */
    public function create(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $teacherId = $request->input('teacher_id');
        $teacher   = null;
        if ($teacherId) {
            $teacher = User::whereHas('role', fn($r) => $r->where('name', 'Guru Les Gasing'))
                ->findOrFail($teacherId);
        }

        $data['teachers'] = User::whereHas('role', fn($r) => $r->where('name', 'Guru Les Gasing'))
            ->orderBy('name')->get();

        // siswa untuk checklist; bisa difilter level/sekolah nanti
        $data['students'] = StudentCourse::orderBy('name')->get();
        $data['teacher']  = $teacher;

        ActivityLogger::log('READ', 'Membuka form penugasan guru ke siswa');

        return view('panel.teacher_assign.create', $data);
    }

    /** =========================
     *  SIMPAN BULK PENUGASAN
     *  ========================= */
    public function store(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $validated = $request->validate([
            'teacher_id'             => ['required', 'exists:users,id'],
            'student_course_ids'     => ['required', 'array', 'min:1'],
            'student_course_ids.*'   => ['required', 'exists:student_courses,id'],
            'start_date'             => ['nullable', 'date'],
            'end_date'               => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'                 => ['required', Rule::in(['Active', 'Inactive'])],
        ]);

        // Pastikan guru benar-benar "Guru Les Gasing"
        $isTeacherGasing = User::where('id', $validated['teacher_id'])
            ->whereHas('role', fn($q) => $q->where('name', 'Guru Les Gasing'))
            ->exists();
        if (!$isTeacherGasing) {
            return back()->withErrors(['teacher_id' => 'User terpilih bukan "Guru Les Gasing".'])->withInput();
        }

        $start = $validated['start_date'] ?? Carbon::today()->toDateString();
        $end   = $validated['end_date'] ?? null;

        DB::transaction(function () use ($validated, $start, $end) {
            foreach ($validated['student_course_ids'] as $sid) {

                // (Opsional kuat) Cegah ada >1 penugasan ACTIVE untuk siswa yang sama
                $alreadyActive = TeacherStudentCourse::where('student_course_id', $sid)
                    ->where('status', 'Active')
                    ->exists();

                if ($validated['status'] === 'Active' && $alreadyActive) {
                    // lewati atau Anda bisa ubah jadi update existing → Inactive
                    continue;
                }

                TeacherStudentCourse::updateOrCreate(
                    [
                        'teacher_id'        => $validated['teacher_id'],
                        'student_course_id' => $sid,
                    ],
                    [
                        'start_date' => $start,
                        'end_date'   => $end,
                        'status'     => $validated['status'],
                    ]
                );
            }
        });

        ActivityLogger::log('CREATE', 'Bulk assign guru ' . $validated['teacher_id'] . ' ke sejumlah siswa');

        return redirect()->route('assign.index')->with('success', 'Penugasan berhasil disimpan.');
    }

    /** =========================
     *  EDIT SATUAN
     *  ========================= */
    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = TeacherStudentCourse::with(['teacher.role', 'studentCourse'])->findOrFail($id);

        $data['record']   = $record;
        $data['teachers'] = User::whereHas('role', fn($r) => $r->where('name', 'Guru Les Gasing'))
            ->orderBy('name')->get();
        $data['students'] = StudentCourse::orderBy('name')->get();

        ActivityLogger::log('READ', 'Membuka edit penugasan: ' . $id);

        return view('panel.teacher_assign.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = TeacherStudentCourse::findOrFail($id);

        $validated = $request->validate([
            'teacher_id'        => ['required', 'exists:users,id'],
            'student_course_id' => ['required', 'exists:student_courses,id'],
            'start_date'        => ['nullable', 'date'],
            'end_date'          => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'            => ['required', Rule::in(['Active', 'Inactive'])],
        ]);

        // validasi guru gasing
        $isTeacherGasing = User::where('id', $validated['teacher_id'])
            ->whereHas('role', fn($q) => $q->where('name', 'Guru Les Gasing'))
            ->exists();
        if (!$isTeacherGasing) {
            return back()->withErrors(['teacher_id' => 'User terpilih bukan "Guru Les Gasing".'])->withInput();
        }

        // (Opsional kuat) larang >1 penugasan Active untuk siswa yang sama
        if ($validated['status'] === 'Active') {
            $existsOtherActive = TeacherStudentCourse::where('student_course_id', $validated['student_course_id'])
                ->where('status', 'Active')
                ->where('id', '<>', $record->id)
                ->exists();
            if ($existsOtherActive) {
                return back()->withErrors(['status' => 'Siswa ini sudah memiliki penugasan Active dengan guru lain.'])->withInput();
            }
        }

        // Cegah duplikasi kombinasi teacher_id + student_course_id (selain diri sendiri)
        $dup = TeacherStudentCourse::where('teacher_id', $validated['teacher_id'])
            ->where('student_course_id', $validated['student_course_id'])
            ->where('id', '<>', $record->id)
            ->exists();
        if ($dup) {
            return back()->withErrors(['student_course_id' => 'Penugasan guru ↔ siswa sudah ada.'])->withInput();
        }

        $record->fill($validated);
        $record->save();

        ActivityLogger::log('UPDATE', 'Update penugasan: ' . $record->id);

        return redirect()->route('assign.index')->with('success', 'Penugasan berhasil diperbarui.');
    }

    /** =========================
     *  TOGGLE STATUS (Active/Inactive)
     *  ========================= */
    public function toggleStatus($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = TeacherStudentCourse::findOrFail($id);
        $record->status = $record->status === 'Active' ? 'Inactive' : 'Active';

        // Opsional kuat: jika mengaktifkan, pastikan tidak ada Active lain pada siswa ini
        if ($record->status === 'Active') {
            $existsOtherActive = TeacherStudentCourse::where('student_course_id', $record->student_course_id)
                ->where('status', 'Active')
                ->where('id', '<>', $record->id)
                ->exists();
            if ($existsOtherActive) {
                return back()->withErrors(['status' => 'Siswa ini sudah memiliki penugasan Active dengan guru lain.'])->withInput();
            }
        }

        $record->save();

        ActivityLogger::log('UPDATE', 'Toggle status penugasan: ' . $record->id);

        return back()->with('success', 'Status penugasan diperbarui.');
    }

    /** =========================
     *  HAPUS
     *  ========================= */
    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = TeacherStudentCourse::findOrFail($id);
        $record->delete();

        ActivityLogger::log('DELETE', 'Hapus penugasan: ' . $id);

        return back()->with('success', 'Penugasan berhasil dihapus.');
    }
}
