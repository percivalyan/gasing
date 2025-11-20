<?php

namespace App\Http\Controllers;

use App\Models\AttendanceCourse;
use App\Models\User;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityLogger;
use Carbon\Carbon;

class AttendanceCourseController extends Controller
{
    /**
     * Menampilkan daftar absensi.
     * - Administrator / role dengan permission dapat melihat semua dan mengelola
     * - Guru Les Gasing hanya melihat riwayat absensinya sendiri
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // cek permission umum (lihat attendance)
        $PermissionRole = PermissionRole::getPermission('Attendance Course', $user->role_id);
        if (empty($PermissionRole)) abort(404);

        $teacherId = $request->input('teacher_id');
        $dateFrom  = $request->input('date_from');
        $dateTo    = $request->input('date_to');

        $q = AttendanceCourse::with('teacher')
            ->when($teacherId, fn($qq) => $qq->where('teacher_id', $teacherId))
            ->when($dateFrom, fn($qq) => $qq->where('attendance_date', '>=', $dateFrom))
            ->when($dateTo, fn($qq) => $qq->where('attendance_date', '<=', $dateTo))
            ->orderBy('attendance_date', 'desc');

        // jika bukan admin, batasi ke diri sendiri
        if (!($user->role && strtolower($user->role->name) === 'administrator')) {
            $q->where('teacher_id', $user->id);
        }

        $data['records'] = $q->paginate(25)->appends($request->query());
        $data['teachers'] = User::whereHas('role', fn($r) => $r->where('name', 'Guru Les Gasing'))->orderBy('name')->get();
        $data['selected'] = compact('teacherId', 'dateFrom', 'dateTo');

        ActivityLogger::log('READ', 'Melihat daftar absensi');

        return view('panel.attendance.index', $data);
    }

    /**
     * Auto checkin — dipanggil saat guru login atau lewat endpoint AJAX dari mobile/web.
     * Jika sudah ada record hari ini: update checkin_at jika kosong.
     * Jika belum ada: buat baru.
     * Input opsional: checkin_lat, checkin_lng, checkin_accuracy, photo (path setelah upload), method
     */
    public function autoCheckin(Request $request)
    {
        $user = Auth::user();

        // hanya Guru Les Gasing dan Administrator (admin bisa juga) dibolehkan
        $roleName = $user->role ? strtolower($user->role->name) : '';
        $isTeacherGasing = $roleName === strtolower('Guru Les Gasing');
        $isAdmin = $roleName === 'administrator';

        if (!($isTeacherGasing || $isAdmin)) {
            abort(403, 'Role Anda tidak diizinkan melakukan checkin.');
        }

        $today = Carbon::now()->toDateString();

        $payload = $request->only(['checkin_lat', 'checkin_lng', 'checkin_accuracy', 'photo', 'method']);
        $payload['checkin_ip'] = $request->ip();

        $attendance = AttendanceCourse::firstOrNew([
            'teacher_id' => $user->id,
            'attendance_date' => $today,
        ]);

        // jika belum punya checkin_at -> set
        if (empty($attendance->checkin_at)) {
            $attendance->checkin_at = Carbon::now();
        }

        // set/overwrite optional fields
        foreach ($payload as $k => $v) {
            if (!is_null($v)) $attendance->{$k} = $v;
        }

        // default status logic: jika checkin setelah jam 08:15 -> late
        try {
            DB::transaction(function () use ($attendance, $user) {
                // default status hanya jika belum diisi
                if (empty($attendance->status)) {
                    $cutoff = Carbon::today()->setTime(8, 15, 0);
                    $attendance->status = (Carbon::now()->gt($cutoff)) ? 'late' : 'present';
                }

                $attendance->teacher_id = $user->id;
                $attendance->attendance_date = $attendance->attendance_date ?? Carbon::now()->toDateString();
                $attendance->method = $attendance->method ?? 'auto';

                $attendance->save();

                ActivityLogger::log('CREATE', 'Auto checkin oleh: ' . $user->id . ' (attendance: ' . $attendance->id . ')');
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal melakukan checkin', 'message' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    /**
     * Checkout untuk menandai pulang.
     * Hanya bisa dilakukan oleh pemilik record (guru) atau admin.
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $date = $request->input('date', Carbon::now()->toDateString());

        $attendance = AttendanceCourse::where('teacher_id', $user->id)
            ->where('attendance_date', $date)
            ->first();

        if (!$attendance) {
            return response()->json(['error' => 'Absensi untuk hari ini tidak ditemukan.'], 404);
        }

        // only owner or admin
        $roleName = $user->role ? strtolower($user->role->name) : '';
        $isAdmin = $roleName === 'administrator';
        if ($attendance->teacher_id !== $user->id && !$isAdmin) {
            abort(403, 'Anda tidak diizinkan melakukan checkout pada record ini.');
        }

        $attendance->checkout_at = Carbon::now();
        $attendance->save();

        ActivityLogger::log('UPDATE', 'Checkout absensi: ' . $attendance->id . ' oleh ' . $user->id);

        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    /**
     * ADMIN: Buat / Edit manual (administrator dapat mengelola semua data)
     */
    public function storeManual(Request $request)
    {
        $user = Auth::user();
        $PermissionRole = PermissionRole::getPermission('Manage Attendance Course', $user->role_id);
        if (empty($PermissionRole)) abort(404);

        $validated = $request->validate([
            'teacher_id'       => ['required', 'exists:users,id'],
            'attendance_date'  => ['required', 'date'],
            'checkin_at'       => ['nullable', 'date'],
            'checkout_at'      => ['nullable', 'date'],
            'status'           => ['nullable', 'in:present,late,permission,absent'],
            'permission_type'  => ['nullable', 'string'],
            'note'             => ['nullable', 'string'],
            'checkin_ip'       => ['nullable', 'string'],
            'photo'            => ['nullable', 'string'],
            'method'           => ['nullable', 'in:auto,manual'],
        ]);

        try {
            DB::transaction(function () use ($validated, $user) {
                $attendance = AttendanceCourse::updateOrCreate(
                    ['teacher_id' => $validated['teacher_id'], 'attendance_date' => $validated['attendance_date']],
                    [
                        'checkin_at' => $validated['checkin_at'] ?? null,
                        'checkout_at' => $validated['checkout_at'] ?? null,
                        'status' => $validated['status'] ?? 'present',
                        'permission_type' => $validated['permission_type'] ?? null,
                        'note' => $validated['note'] ?? null,
                        'checkin_ip' => $validated['checkin_ip'] ?? null,
                        'photo' => $validated['photo'] ?? null,
                        'method' => $validated['method'] ?? 'manual',
                    ]
                );

                ActivityLogger::log('UPDATE', 'Manual save absensi oleh admin: ' . $user->id . ' (attendance: ' . $attendance->id . ')');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan absensi: ' . $e->getMessage()])->withInput();
        }

        return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
    }

    /**
     * ADMIN: Edit form
     */
    public function edit($id)
    {
        $user = Auth::user();
        $PermissionRole = PermissionRole::getPermission('Manage Attendance Course', $user->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = AttendanceCourse::with('teacher')->findOrFail($id);

        return view('panel.attendance.edit', ['record' => $record, 'teachers' => User::whereHas('role', fn($r) => $r->where('name', 'Guru Les Gasing'))->get()]);
    }

    public function update($id, Request $request)
    {
        $user = Auth::user();
        $PermissionRole = PermissionRole::getPermission('Manage Attendance Course', $user->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = AttendanceCourse::findOrFail($id);

        $validated = $request->validate([
            'teacher_id'       => ['required', 'exists:users,id'],
            'attendance_date'  => ['required', 'date'],
            'checkin_at'       => ['nullable', 'date'],
            'checkout_at'      => ['nullable', 'date'],
            'status'           => ['nullable', 'in:present,late,permission,absent'],
            'permission_type'  => ['nullable', 'string'],
            'note'             => ['nullable', 'string'],
            'checkin_ip'       => ['nullable', 'string'],
            'photo'            => ['nullable', 'string'],
            'method'           => ['nullable', 'in:auto,manual'],
        ]);

        $record->fill([
            'teacher_id' => $validated['teacher_id'],
            'attendance_date' => $validated['attendance_date'],
            'checkin_at' => $validated['checkin_at'] ?? null,
            'checkout_at' => $validated['checkout_at'] ?? null,
            'status' => $validated['status'] ?? $record->status,
            'permission_type' => $validated['permission_type'] ?? $record->permission_type,
            'note' => $validated['note'] ?? $record->note,
            'checkin_ip' => $validated['checkin_ip'] ?? $record->checkin_ip,
            'photo' => $validated['photo'] ?? $record->photo,
            'method' => $validated['method'] ?? $record->method,
        ]);

        $record->save();

        ActivityLogger::log('UPDATE', 'Admin update absensi: ' . $record->id . ' oleh ' . $user->id);

        return redirect()->route('attendance.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $PermissionRole = PermissionRole::getPermission('Manage Attendance Course', $user->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = AttendanceCourse::findOrFail($id);
        $record->delete();

        ActivityLogger::log('DELETE', 'Admin hapus absensi: ' . $id . ' oleh ' . $user->id);

        return back()->with('success', 'Absensi berhasil dihapus.');
    }
}
