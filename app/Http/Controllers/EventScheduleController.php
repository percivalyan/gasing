<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventSchedule;
use App\Models\EventBatch;
use App\Models\User;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventScheduleController extends Controller
{
    /**
     * List & matrix view of Event Schedule
     */
    public function list(Request $request)
    {
        // Cek permission utama
        $PermissionRole = PermissionRole::getPermission('Event Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        // Permission per aksi
        $data['PermissionAdd']    = PermissionRole::getPermission('Add Event Schedule', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Event Schedule', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Event Schedule', Auth::user()->role_id);

        /**
         * Ambil semua batch untuk dropdown.
         * Urutan: tahun terbaru dulu, lalu fase (tahap) terbesar dulu
         * Contoh: 2026 Tahap 2, 2026 Tahap 1, 2025 Tahap 2, dst.
         */
        $batches = EventBatch::orderBy('event_year', 'desc')
            ->orderBy('event_phase', 'desc')
            ->get();

        $selectedBatchId = $request->get('batch_id');

        // Default: batch terbaru (jika user belum memilih apapun)
        if (!$selectedBatchId) {
            if ($batches->count() > 0) {
                $selectedBatchId = $batches->first()->id; // batch paling baru
            } else {
                $selectedBatchId = 'all';
            }
        }

        /**
         * Query utama jadwal:
         * - Join ke event_batchs supaya bisa sort berdasarkan tahun & fase batch
         * - Lalu sort tanggal & waktu
         */
        $query = EventSchedule::with(['eventBatch', 'user'])
            ->join('event_batchs as eb', 'eb.id', '=', 'event_schedules.event_batch_id')
            ->select('event_schedules.*')
            ->orderBy('eb.event_year', 'desc')
            ->orderBy('eb.event_phase', 'desc')
            ->orderBy('event_schedules.date', 'desc')
            ->orderBy('event_schedules.start_time', 'asc');

        // Filter batch: "all" = semua batch
        if ($selectedBatchId && $selectedBatchId !== 'all') {
            $query->where('event_schedules.event_batch_id', $selectedBatchId);
        }

        // Optional: search q (agenda / tempat / nama user)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('event_schedules.agenda', 'like', '%' . $q . '%')
                    ->orWhere('event_schedules.place', 'like', '%' . $q . '%')
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', '%' . $q . '%');
                    });
            });
        }

        $records = $query->get();

        $data['getRecord']       = $records;
        $data['getBatch']        = $batches;
        $data['selectedBatchId'] = $selectedBatchId;

        /**
         * MATRIX UNTUK TABEL JADWAL (MIRIP LESSON SCHEDULE)
         * - Kolom: Hari (Senin s/d Minggu)
         * - Baris: Slot waktu (start_time - end_time)
         * - Isi: daftar EventSchedule pada hari+slot tersebut
         */
        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        $dayMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        $timeSlots = [];   // array slot unik: ['start' => xx, 'end' => yy]
        $matrix    = [];   // $matrix[Hari][slotKey][] = EventSchedule

        foreach ($records as $item) {
            // Jika tidak ada date / jam, skip dari matrix
            if (!$item->date || !$item->start_time || !$item->end_time) {
                continue;
            }

            // Slot key
            $slotKey = $item->start_time . '-' . $item->end_time;
            if (!isset($timeSlots[$slotKey])) {
                $timeSlots[$slotKey] = [
                    'start' => $item->start_time,
                    'end'   => $item->end_time,
                ];
            }

            // Hari (bahasa Indonesia)
            try {
                $engDay  = Carbon::parse($item->date)->format('l'); // Monday, Tuesday, ...
                $dayName = $dayMap[$engDay] ?? $engDay;
            } catch (\Exception $e) {
                $dayName = 'Lainnya';
            }

            if (!isset($matrix[$dayName])) {
                $matrix[$dayName] = [];
            }
            if (!isset($matrix[$dayName][$slotKey])) {
                $matrix[$dayName][$slotKey] = [];
            }

            $matrix[$dayName][$slotKey][] = $item;
        }

        // Urutkan slot waktu berdasarkan start_time
        $timeSlots = array_values($timeSlots);
        usort($timeSlots, function ($a, $b) {
            return strcmp($a['start'], $b['start']);
        });

        $data['days']      = $days;
        $data['timeSlots'] = $timeSlots;
        $data['matrix']    = $matrix;

        return view('panel.event_schedule.list', $data);
    }

    /**
     * Form tambah jadwal event
     */
    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Event Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getBatch'] = EventBatch::orderBy('event_year', 'desc')
            ->orderBy('event_phase', 'desc')
            ->get();

        $data['getUser'] = User::orderBy('name')->get();

        return view('panel.event_schedule.add', $data);
    }

    /**
     * Simpan jadwal event baru
     */
    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Event Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'event_batch_id' => 'required|exists:event_batchs,id',
            'user_id'        => 'nullable|exists:users,id',
            'date'           => 'nullable|date',
            'start_time'     => 'nullable|date_format:H:i',
            'end_time'       => 'nullable|date_format:H:i|after:start_time',
            'place'          => 'nullable|string|max:255',
            'agenda'         => 'nullable|string|max:500',
            'status'         => 'nullable|string|max:50',
        ]);

        EventSchedule::create($request->only([
            'event_batch_id',
            'user_id',
            'date',
            'start_time',
            'end_time',
            'place',
            'agenda',
            'status',
        ]));

        return redirect('event-schedule')->with('success', 'Jadwal Event berhasil dibuat');
    }

    /**
     * Form edit jadwal event
     */
    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Event Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getRecord'] = EventSchedule::findOrFail($id);
        $data['getBatch'] = EventBatch::orderBy('event_year', 'desc')
            ->orderBy('event_phase', 'desc')
            ->get();
        $data['getUser'] = User::orderBy('name')->get();

        return view('panel.event_schedule.edit', $data);
    }

    /**
     * Update jadwal event
     */
    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Event Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'event_batch_id' => 'required|exists:event_batchs,id',
            'user_id'        => 'nullable|exists:users,id',
            'date'           => 'nullable|date',
            'start_time'     => 'nullable|date_format:H:i',
            'end_time'       => 'nullable|date_format:H:i|after:start_time',
            'place'          => 'nullable|string|max:255',
            'agenda'         => 'nullable|string|max:500',
            'status'         => 'nullable|string|max:50',
        ]);

        $schedule = EventSchedule::findOrFail($id);

        $schedule->fill($request->only([
            'event_batch_id',
            'user_id',
            'date',
            'start_time',
            'end_time',
            'place',
            'agenda',
            'status',
        ]))->save();

        return redirect('event-schedule')->with('success', 'Jadwal Event berhasil diperbarui');
    }

    /**
     * Hapus jadwal event
     */
    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Event Schedule', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        EventSchedule::findOrFail($id)->delete();

        return redirect('event-schedule')->with('success', 'Jadwal Event berhasil dihapus');
    }
}
