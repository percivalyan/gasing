<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LessonSchedule;
use App\Models\Subject;
use App\Models\PermissionRole;
use App\Models\User;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LessonScheduleController extends Controller
{
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Lesson Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Lesson Schedule', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Lesson Schedule', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Lesson Schedule', Auth::user()->role_id);

        // Query dasar
        $baseQuery = LessonSchedule::with(['teacher', 'subject'])
            ->whereHas('teacher.role', function ($qq) {
                $qq->where('name', 'Guru Les Gasing');
            });

        // FILTER
        $day   = $request->get('day');
        $level = $request->get('level');
        $search = $request->get('q');

        if (!empty($day)) {
            $baseQuery->where('day_of_week', $day);
        }

        if (!empty($level)) {
            $baseQuery->where('school_level', $level);
        }

        if (!empty($search)) {
            $baseQuery->where(function ($w) use ($search) {
                $w->where('subject_name', 'like', "%{$search}%")
                    ->orWhereHas('subject', fn($sq) => $sq->where('name', 'like', "%{$search}%"))
                    ->orWhere('room', 'like', "%{$search}%")
                    ->orWhereHas('teacher', fn($tq) => $tq->where('name', 'like', "%{$search}%"));
            });
        }

        // DATA UNTUK TIMETABLE (tanpa paginasi, urut per hari & jam)
        $timetableQuery = clone $baseQuery;

        $getRecordForMatrix = $timetableQuery
            ->orderByRaw("FIELD(day_of_week,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->get();

        // SIAPKAN timeSlots & matrix seperti sebelumnya
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $timeSlots = [];
        $start = strtotime('07:00');
        $end   = strtotime('17:00');
        $step  = 60 * 60; // 1 jam

        for ($t = $start; $t < $end; $t += $step) {
            $timeSlots[] = [
                'start' => date('H:i', $t),
                'end'   => date('H:i', $t + $step),
            ];
        }
        $data['timeSlots'] = $timeSlots;

        // Inisialisasi matrix kosong
        $matrix = [];
        foreach ($days as $d) {
            foreach ($timeSlots as $slot) {
                $matrix[$d][$slot['start'] . '-' . $slot['end']] = [];
            }
        }

        // Isi matrix berdasarkan jadwal
        foreach ($getRecordForMatrix as $sch) {
            foreach ($timeSlots as $slot) {
                $s1 = $slot['start'];
                $e1 = $slot['end'];
                if ($sch->start_time < $e1 && $sch->end_time > $s1) {
                    $matrix[$sch->day_of_week][$s1 . '-' . $e1][] = $sch;
                }
            }
        }
        $data['matrix'] = $matrix;

        // SORTING & PAGINATION UNTUK TABEL BAWAH
        $allowedSortBy = ['created_at', 'day_of_week', 'start_time', 'school_level', 'room'];
        $sortBy        = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'created_at';
        }

        $tableQuery = clone $baseQuery;
        $tableQuery->orderBy($sortBy, $sortDirection);

        $data['getRecord'] = $tableQuery->paginate(10)->withQueryString();

        // simpan nilai filter & sort untuk view
        $data['filter_day']   = $day;
        $data['filter_level'] = $level;
        $data['filter_q']     = $search;
        $data['sort_by']      = $sortBy;
        $data['sort_direction'] = $sortDirection;

        ActivityLogger::log('READ', 'Melihat daftar Lesson Schedule');

        return view('panel.lesson_schedule.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Lesson Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['teachers'] = User::whereHas('role', fn($q) => $q->where('name', 'Guru Les Gasing'))
            ->orderBy('name', 'asc')->get();

        // kirim daftar subjects untuk dropdown (opsional)
        $data['subjects'] = Subject::orderBy('name')->get();

        ActivityLogger::log('READ', 'Membuka form tambah Lesson Schedule');

        return view('panel.lesson_schedule.add', $data);
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Lesson Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        // normalisasi waktu
        $request->merge([
            'start_time' => $this->normalizeTime($request->input('start_time')),
            'end_time'   => $this->normalizeTime($request->input('end_time')),
        ]);

        $request->validate([
            'teacher_id'   => ['required', 'exists:users,id'],
            'school_level' => ['required', 'in:SD,SMP,SMA'],
            'subject_id'   => ['nullable', 'exists:subjects,id'],
            'subject_name' => ['nullable', 'string', 'max:100'],
            'day_of_week'  => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'start_time'   => ['required', 'date_format:H:i'],
            'end_time'     => ['required', 'date_format:H:i', 'after:start_time'],
            'room'         => ['nullable', 'string', 'max:100'],
        ]);

        if (!$this->isTeacherGasing($request->teacher_id)) {
            return back()->withErrors(['teacher_id' => 'Teacher harus memiliki role "Guru Les Gasing".'])->withInput();
        }

        // pastikan ada subject_id atau subject_name
        $subjectId = $request->input('subject_id');
        $subjectName = trim($request->input('subject_name') ?? '');

        if (empty($subjectId) && empty($subjectName)) {
            return back()->withErrors(['subject' => 'Anda harus memilih subject atau mengisi nama subject.'])->withInput();
        }

        // jika subject kosong tapi ada nama -> cari atau buat (case-insensitive)
        if (empty($subjectId) && !empty($subjectName)) {
            // gunakan transaction kecil untuk safety
            DB::beginTransaction();
            try {
                $subject = Subject::whereRaw('LOWER(name) = ?', [mb_strtolower($subjectName)])->first();
                if (!$subject) {
                    $subject = Subject::create([
                        'id' => (string) Str::uuid(),
                        'name' => $subjectName,
                        'description' => null,
                    ]);
                    ActivityLogger::log('CREATE', 'Menambahkan Subject: ' . $subject->name);
                }
                $subjectId = $subject->id;
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                return back()->withErrors(['subject' => 'Gagal membuat subject: ' . $e->getMessage()])->withInput();
            }
        }

        $schedule = new LessonSchedule();
        $schedule->id = (string) Str::uuid();
        $schedule->teacher_id = $request->teacher_id;
        $schedule->school_level = $request->school_level;
        $schedule->subject_id = $subjectId;
        // jika kamu tetap ingin menyimpan subject_name juga (kolom nullable), simpan:
        if ($schedule->getConnection()->getSchemaBuilder()->hasColumn($schedule->getTable(), 'subject_name')) {
            $schedule->subject_name = $subjectName ?: Subject::find($subjectId)->name ?? null;
        }
        $schedule->day_of_week = $request->day_of_week;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->room = $request->room;
        $schedule->save();

        ActivityLogger::log('CREATE', 'Menambahkan Lesson Schedule: ' . ($subjectName ?: (Subject::find($subjectId)->name ?? '')));

        return redirect()->route('lesson_schedule.list')->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Lesson Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = LessonSchedule::with('subject')->findOrFail($id);
        // format untuk form
        $record->start_time_hm = $this->normalizeTime($record->start_time);
        $record->end_time_hm   = $this->normalizeTime($record->end_time);

        $data['getRecord'] = $record;
        $data['teachers']  = User::whereHas('role', fn($q) => $q->where('name', 'Guru Les Gasing'))
            ->orderBy('name', 'asc')->get();

        // kirim daftar subjects untuk dropdown
        $data['subjects'] = Subject::orderBy('name')->get();

        ActivityLogger::log('READ', 'Membuka form edit Lesson Schedule: ' . ($record->subject->name ?? $record->subject_name));

        return view('panel.lesson_schedule.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Lesson Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $schedule = LessonSchedule::findOrFail($id);

        // normalisasi waktu
        $request->merge([
            'start_time' => $this->normalizeTime($request->input('start_time')),
            'end_time'   => $this->normalizeTime($request->input('end_time')),
        ]);

        $request->validate([
            'teacher_id'   => ['required', 'exists:users,id'],
            'school_level' => ['required', 'in:SD,SMP,SMA'],
            'subject_id'   => ['nullable', 'exists:subjects,id'],
            'subject_name' => ['nullable', 'string', 'max:100'],
            'day_of_week'  => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'start_time'   => ['required', 'date_format:H:i'],
            'end_time'     => ['required', 'date_format:H:i', 'after:start_time'],
            'room'         => ['nullable', 'string', 'max:100'],
        ]);

        if (!$this->isTeacherGasing($request->teacher_id)) {
            return back()->withErrors(['teacher_id' => 'Teacher harus memiliki role "Guru Les Gasing".'])->withInput();
        }

        $subjectId = $request->input('subject_id');
        $subjectName = trim($request->input('subject_name') ?? '');

        if (empty($subjectId) && empty($subjectName)) {
            return back()->withErrors(['subject' => 'Anda harus memilih subject atau mengisi nama subject.'])->withInput();
        }

        if (empty($subjectId) && !empty($subjectName)) {
            DB::beginTransaction();
            try {
                $subject = Subject::whereRaw('LOWER(name) = ?', [mb_strtolower($subjectName)])->first();
                if (!$subject) {
                    $subject = Subject::create([
                        'id' => (string) Str::uuid(),
                        'name' => $subjectName,
                        'description' => null,
                    ]);
                    ActivityLogger::log('CREATE', 'Menambahkan Subject: ' . $subject->name);
                }
                $subjectId = $subject->id;
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                return back()->withErrors(['subject' => 'Gagal membuat subject: ' . $e->getMessage()])->withInput();
            }
        }

        $schedule->teacher_id = $request->teacher_id;
        $schedule->school_level = $request->school_level;
        $schedule->subject_id = $subjectId;
        if ($schedule->getConnection()->getSchemaBuilder()->hasColumn($schedule->getTable(), 'subject_name')) {
            $schedule->subject_name = $subjectName ?: Subject::find($subjectId)->name ?? null;
        }
        $schedule->day_of_week = $request->day_of_week;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->room = $request->room;
        $schedule->save();

        ActivityLogger::log('UPDATE', 'Memperbarui Lesson Schedule: ' . ($subjectName ?: (Subject::find($subjectId)->name ?? '')));

        return redirect()->route('lesson_schedule.list')->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Lesson Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $schedule = LessonSchedule::findOrFail($id);
        $name = $schedule->subject->name ?? $schedule->subject_name;
        $schedule->delete();

        ActivityLogger::log('DELETE', 'Menghapus Lesson Schedule: ' . $name);

        return redirect()->route('lesson_schedule.list')->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    private function isTeacherGasing(string $teacherId): bool
    {
        return User::where('id', $teacherId)
            ->whereHas('role', fn($q) => $q->where('name', 'Guru Les Gasing'))
            ->exists();
    }

    private function normalizeTime(?string $value): ?string
    {
        if (!$value) return $value;
        if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $value)) {
            return substr($value, 0, 5);
        }
        return $value;
    }
}
