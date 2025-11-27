<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentEvent;
use App\Models\PermissionRole;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StudentEventController extends Controller
{
    /**
     * Menampilkan daftar Student Event
     */
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Student Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd'] = PermissionRole::getPermission('Add Student Event', Auth::user()->role_id);
        $data['PermissionEdit'] = PermissionRole::getPermission('Edit Student Event', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Student Event', Auth::user()->role_id);

        // Filter & search
        $query = StudentEvent::query();

        // Search (nama, NIK, sekolah, distrik)
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('nik', 'like', '%' . $keyword . '%')
                    ->orWhere('school_origin', 'like', '%' . $keyword . '%')
                    ->orWhere('origin_district', 'like', '%' . $keyword . '%');
            });
        }

        // Filter status
        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter tingkat sekolah (opsional)
        if (!empty($request->school_level)) {
            $query->where('school_level', $request->school_level);
        }

        // Sorting
        $allowedSortBy = ['created_at', 'name', 'school_level', 'status'];
        $sortBy = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $sortDirection);

        // Jika mau pakai pagination, ganti ->get() jadi ->paginate(20)
        $data['getRecord'] = $query->paginate(10)->withQueryString();

        // Untuk mempertahankan nilai filter di view
        $data['filter_status'] = $request->status;
        $data['filter_school_level'] = $request->school_level;
        $data['filter_keyword'] = $request->keyword;
        $data['sort_by'] = $sortBy;
        $data['sort_direction'] = $sortDirection;

        ActivityLogger::log('READ', 'Melihat daftar Student Event (Admin Index)');

        return view('panel.student_event.list', $data);
    }

    /**
     * Menampilkan form tambah Student Event
     */
    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Student Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        ActivityLogger::log('READ', 'Membuka form tambah Student Event');

        return view('panel.student_event.add');
    }

    /**
     * Menyimpan data Student Event baru
     */
    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Student Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'name' => 'required|string|max:50',
            'nik' => 'nullable|string|max:30|unique:student_events,nik',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F',
            'address' => 'nullable|string',
            'origin_district' => 'nullable|string|max:100',
            'school_level' => 'nullable|in:SD,SMP,SMA',
            'whatsapp_number' => 'nullable|string|max:20',
            'dream' => 'nullable|string|max:255',
            'school_origin' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'letter_of_assignment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $student = new StudentEvent();
        $student->id = (string) Str::uuid();
        $student->user_id = Auth::id();
        $student->fill($request->except(['photo', 'letter_of_assignment']));

        if ($request->hasFile('photo')) {
            $student->photo = $request->file('photo')->store('student_photos', 'public');
        }

        if ($request->hasFile('letter_of_assignment')) {
            $student->letter_of_assignment = $request->file('letter_of_assignment')->store('student_letters', 'public');
        }

        $student->save();

        ActivityLogger::log('CREATE', 'Menambahkan Student Event: ' . $student->name);

        return redirect()->route('student_event.list')->with('success', 'Data Student Event berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit Student Event
     */
    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Student Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getRecord'] = StudentEvent::findOrFail($id);

        ActivityLogger::log('READ', 'Membuka form edit Student Event: ' . $data['getRecord']->name);

        return view('panel.student_event.edit', $data);
    }

    /**
     * Memperbarui data Student Event
     */
    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Student Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $student = StudentEvent::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50',
            'nik' => 'nullable|string|max:30|unique:student_events,nik,' . $student->id,
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F',
            'address' => 'nullable|string',
            'origin_district' => 'nullable|string|max:100',
            'school_level' => 'nullable|in:SD,SMP,SMA',
            'whatsapp_number' => 'nullable|string|max:20',
            'dream' => 'nullable|string|max:255',
            'school_origin' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'letter_of_assignment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $student->fill($request->except(['photo', 'letter_of_assignment']));

        if ($request->hasFile('photo')) {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $student->photo = $request->file('photo')->store('student_photos', 'public');
        }

        if ($request->hasFile('letter_of_assignment')) {
            if ($student->letter_of_assignment && Storage::disk('public')->exists($student->letter_of_assignment)) {
                Storage::disk('public')->delete($student->letter_of_assignment);
            }
            $student->letter_of_assignment = $request->file('letter_of_assignment')->store('student_letters', 'public');
        }

        $student->save();

        ActivityLogger::log('UPDATE', 'Memperbarui Student Event: ' . $student->name);

        return redirect()->route('student_event.list')->with('success', 'Data Student Event berhasil diperbarui.');
    }

    /**
     * Form pendaftaran publik Student Event
     * (Hanya Administrator & Kepala Sekolah)
     */
    public function formregistration()
    {
        $PermissionRole = PermissionRole::getPermission('Add Student Event Kepala Sekolah', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $role = Auth::user()->role->name ?? '';
        if (!in_array($role, ['Administrator', 'Kepala Sekolah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        ActivityLogger::log('READ', 'Membuka form registration Student Event');

        return view('panel.student_event.registration',);
    }

    /**
     * Proses pendaftaran Student Event dari formregistration
     */
    public function registration(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Student Event Kepala Sekolah', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $role = Auth::user()->role->name ?? '';
        if (!in_array($role, ['Administrator', 'Kepala Sekolah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'name' => 'required|string|max:50',
            'nik' => 'nullable|string|max:30|unique:student_events,nik',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F',
            'address' => 'nullable|string',
            'origin_district' => 'nullable|string|max:100',
            'school_level' => 'nullable|in:SD,SMP,SMA',
            'whatsapp_number' => 'nullable|string|max:20',
            'dream' => 'nullable|string|max:255',
            'school_origin' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'letter_of_assignment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $student = new StudentEvent();
        $student->id = (string) Str::uuid();
        $student->user_id = Auth::id();
        $student->fill($request->except(['photo', 'letter_of_assignment']));

        if ($request->hasFile('photo')) {
            $student->photo = $request->file('photo')->store('student_photos', 'public');
        }

        if ($request->hasFile('letter_of_assignment')) {
            $student->letter_of_assignment = $request->file('letter_of_assignment')->store('student_letters', 'public');
        }

        $student->save();

        ActivityLogger::log('CREATE', 'Registrasi Student Event: ' . $student->name);

        return redirect()->route('student_event.my_registration')->with('success', 'Pendaftaran Student Event berhasil dikirim.');
    }

    /**
     * Menghapus Student Event
     */
    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Student Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $student = StudentEvent::findOrFail($id);

        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        if ($student->letter_of_assignment && Storage::disk('public')->exists($student->letter_of_assignment)) {
            Storage::disk('public')->delete($student->letter_of_assignment);
        }

        $student->delete();

        ActivityLogger::log('DELETE', 'Menghapus Student Event: ' . $student->name);

        return redirect()->route('student_event.list')->with('success', 'Data Student Event berhasil dihapus.');
    }

    /**
     * Index khusus Kepala Sekolah untuk melihat pendaftaran yang dia input sendiri
     */
    public function myRegistrationIndex(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Student Event Kepala Sekolah', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $role = Auth::user()->role->name ?? '';
        if (!in_array($role, ['Administrator', 'Kepala Sekolah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = StudentEvent::where('user_id', Auth::id());

        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('nik', 'like', '%' . $keyword . '%')
                    ->orWhere('school_origin', 'like', '%' . $keyword . '%')
                    ->orWhere('origin_district', 'like', '%' . $keyword . '%');
            });
        }

        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }

        $query->orderBy('created_at', 'desc');

        $data['getRecord'] = $query->paginate(10)->withQueryString();
        $data['filter_status'] = $request->status;
        $data['filter_keyword'] = $request->keyword;

        ActivityLogger::log('READ', 'Melihat daftar Student Event milik sendiri (Kepala Sekolah)');

        return view('panel.student_event.my_index', $data);
    }

    /**
     * Update status Student Event (validasi oleh Admin)
     */
    public function updateStatus($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Student Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'status' => 'required|in:Pending,Accepted,Rejected',
        ]);

        $student = StudentEvent::findOrFail($id);
        $student->status = $request->status;
        $student->save();

        ActivityLogger::log('UPDATE', 'Mengubah status Student Event: ' . $student->name . ' menjadi ' . $student->status);

        return redirect()->back()->with('success', 'Status Student Event berhasil diperbarui.');
    }
}
