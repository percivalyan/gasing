<?php

namespace App\Http\Controllers;

use App\Models\TeacherStudentEvent;
use App\Models\TeacherEvent;
use App\Models\StudentEvent;
use App\Models\User;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogger;
use Carbon\Carbon;

class TeacherStudentEventController extends Controller
{
    /** LIST & FILTER */
    public function index(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $teacherId = $request->input('teacher_event_id');
        $studentId = $request->input('student_event_id');
        $status    = $request->input('status');
        $keyword   = $request->input('keyword');

        $query = TeacherStudentEvent::with(['teacherEvent', 'studentEvent']);

        // SEARCH: nama teacher event / nama student / level
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('teacherEvent', function ($sub) use ($keyword) {
                    $sub->where('name', 'like', '%' . $keyword . '%');
                })->orWhereHas('studentEvent', function ($sub) use ($keyword) {
                    $sub->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('school_level', 'like', '%' . $keyword . '%');
                });
            });
        }

        // FILTER
        if (!empty($teacherId)) {
            $query->where('teacher_event_id', $teacherId);
        }
        if (!empty($studentId)) {
            $query->where('student_event_id', $studentId);
        }
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // SORTING (whitelist)
        $allowedSortBy = ['created_at', 'start_date', 'end_date', 'status'];
        $sortBy        = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $sortDirection);

        // PAGINATION
        $data['records']  = $query->paginate(25)->appends($request->query());
        $data['teachers'] = TeacherEvent::orderBy('name')->get();
        $data['students'] = StudentEvent::orderBy('name')->get();

        // simpan nilai filter & sort ke view
        $data['filter_keyword']    = $keyword;
        $data['selected']          = compact('teacherId', 'studentId', 'status');
        $data['sort_by']           = $sortBy;
        $data['sort_direction']    = $sortDirection;

        ActivityLogger::log('READ', 'Melihat daftar penugasan teacher_event ↔ student_event');

        return view('panel.teacher_event_assign.index', $data);
    }

    /** FORM: pilih teacher_event → checklist student_event */
    public function create(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $teacherEventId = $request->input('teacher_event_id');
        $teacherEvent = null;
        if ($teacherEventId) {
            $teacherEvent = TeacherEvent::findOrFail($teacherEventId);
        }

        $data['teachers'] = TeacherEvent::orderBy('name')->get();
        $data['students'] = StudentEvent::orderBy('name')->get();
        $data['teacherEvent'] = $teacherEvent;

        ActivityLogger::log('READ', 'Membuka form penugasan teacher_event ke student_event');

        return view('panel.teacher_event_assign.create', $data);
    }

    /** BULK STORE */
    public function store(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $validated = $request->validate([
            'teacher_event_id'         => ['required', 'exists:teacher_events,id'],
            'student_event_ids'        => ['required', 'array', 'min:1'],
            'student_event_ids.*'      => ['required', 'exists:student_events,id'],
            'start_date'               => ['nullable', 'date'],
            'end_date'                 => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'                   => ['required', Rule::in(['Active', 'Inactive'])],
        ]);

        $start = $validated['start_date'] ?? Carbon::today()->toDateString();
        $end   = $validated['end_date'] ?? null;

        DB::transaction(function () use ($validated, $start, $end) {
            foreach ($validated['student_event_ids'] as $sid) {

                // Cegah >1 penugasan ACTIVE untuk satu student_event jika diinginkan
                $alreadyActive = TeacherStudentEvent::where('student_event_id', $sid)
                    ->where('status', 'Active')
                    ->exists();

                if ($validated['status'] === 'Active' && $alreadyActive) {
                    // lewati; bisa diubah menjadi nonaktifkan existing jika diperlukan
                    continue;
                }

                TeacherStudentEvent::updateOrCreate(
                    [
                        'teacher_event_id' => $validated['teacher_event_id'],
                        'student_event_id' => $sid,
                    ],
                    [
                        'start_date' => $start,
                        'end_date'   => $end,
                        'status'     => $validated['status'],
                    ]
                );
            }
        });

        ActivityLogger::log('CREATE', 'Bulk assign teacher_event ' . $validated['teacher_event_id'] . ' ke sejumlah student_event');

        return redirect()->route('event-assign.index')->with('success', 'Penugasan berhasil disimpan.');
    }

    /** EDIT SATUAN */
    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = TeacherStudentEvent::with(['teacherEvent', 'studentEvent'])->findOrFail($id);

        $data['record']   = $record;
        $data['teachers'] = TeacherEvent::orderBy('name')->get();
        $data['students'] = StudentEvent::orderBy('name')->get();

        ActivityLogger::log('READ', 'Membuka edit penugasan event: ' . $id);

        return view('panel.teacher_event_assign.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = TeacherStudentEvent::findOrFail($id);

        $validated = $request->validate([
            'teacher_event_id'   => ['required', 'exists:teacher_events,id'],
            'student_event_id'   => ['required', 'exists:student_events,id'],
            'start_date'         => ['nullable', 'date'],
            'end_date'           => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'             => ['required', Rule::in(['Active', 'Inactive'])],
        ]);

        // jika ingin mencegah >1 Active
        if ($validated['status'] === 'Active') {
            $existsOtherActive = TeacherStudentEvent::where('student_event_id', $validated['student_event_id'])
                ->where('status', 'Active')
                ->where('id', '<>', $record->id)
                ->exists();
            if ($existsOtherActive) {
                return back()->withErrors(['status' => 'Student event ini sudah memiliki penugasan Active dengan teacher event lain.'])->withInput();
            }
        }

        // cegah duplikat kombinasi (selain diri sendiri)
        $dup = TeacherStudentEvent::where('teacher_event_id', $validated['teacher_event_id'])
            ->where('student_event_id', $validated['student_event_id'])
            ->where('id', '<>', $record->id)
            ->exists();
        if ($dup) {
            return back()->withErrors(['student_event_id' => 'Penugasan teacher_event ↔ student_event sudah ada.'])->withInput();
        }

        $record->fill($validated);
        $record->save();

        ActivityLogger::log('UPDATE', 'Update penugasan event: ' . $record->id);

        return redirect()->route('event-assign.index')->with('success', 'Penugasan berhasil diperbarui.');
    }

    /** TOGGLE STATUS */
    public function toggleStatus($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = TeacherStudentEvent::findOrFail($id);
        $record->status = $record->status === 'Active' ? 'Inactive' : 'Active';

        if ($record->status === 'Active') {
            $existsOtherActive = TeacherStudentEvent::where('student_event_id', $record->student_event_id)
                ->where('status', 'Active')
                ->where('id', '<>', $record->id)
                ->exists();
            if ($existsOtherActive) {
                return back()->withErrors(['status' => 'Student event ini sudah memiliki penugasan Active dengan teacher event lain.'])->withInput();
            }
        }

        $record->save();

        ActivityLogger::log('UPDATE', 'Toggle status penugasan event: ' . $record->id);

        return back()->with('success', 'Status penugasan diperbarui.');
    }

    /** DELETE */
    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = TeacherStudentEvent::findOrFail($id);
        $record->delete();

        ActivityLogger::log('DELETE', 'Hapus penugasan event: ' . $id);

        return back()->with('success', 'Penugasan berhasil dihapus.');
    }
}
