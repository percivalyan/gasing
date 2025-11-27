<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherEvent;
use App\Models\PermissionRole;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TeacherEventController extends Controller
{
    /**
     * Menampilkan daftar Teacher Event (INDEX ADMIN)
     */
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Teacher Event', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Teacher Event', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Teacher Event', Auth::user()->role_id);

        $query = TeacherEvent::query();

        // Search (name, NIP, school_origin, expertise_field)
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('nip', 'like', '%' . $keyword . '%')
                    ->orWhere('school_origin', 'like', '%' . $keyword . '%')
                    ->orWhere('expertise_field', 'like', '%' . $keyword . '%');
            });
        }

        // Filter status
        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Sorting
        $allowedSortBy   = ['created_at', 'name', 'expertise_field', 'status'];
        $sortBy          = $request->get('sort_by');
        $sortDirection   = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $sortDirection);

        // Kalau mau pagination, ganti get() jadi paginate()
        $data['getRecord'] = $query->paginate(10)->withQueryString();
        $data['filter_keyword']     = $request->keyword;
        $data['filter_status']      = $request->status;
        $data['sort_by']            = $sortBy;
        $data['sort_direction']     = $sortDirection;

        ActivityLogger::log('READ', 'Melihat daftar Teacher Event (Admin Index)');

        return view('panel.teacher_event.list', $data);
    }

    /**
     * INDEX khusus Kepala Sekolah (hanya data yang dia input)
     */
    public function myRegistrationIndex(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Teacher Event Kepala Sekolah', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $role = Auth::user()->role->name ?? '';
        if (!in_array($role, ['Administrator', 'Kepala Sekolah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = TeacherEvent::where('user_id', Auth::id());

        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('nip', 'like', '%' . $keyword . '%')
                    ->orWhere('school_origin', 'like', '%' . $keyword . '%')
                    ->orWhere('expertise_field', 'like', '%' . $keyword . '%');
            });
        }

        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }

        $query->orderBy('created_at', 'desc');

        $data['getRecord'] = $query->paginate(10)->withQueryString();
        $data['filter_keyword'] = $request->keyword;
        $data['filter_status']  = $request->status;

        ActivityLogger::log('READ', 'Melihat daftar Teacher Event milik sendiri (Kepala Sekolah)');

        return view('panel.teacher_event.my_index', $data);
    }

    /**
     * Update status Teacher Event (validasi oleh Admin)
     */
    public function updateStatus($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'status' => 'required|in:Pending,Accepted,Rejected',
        ]);

        $teacher = TeacherEvent::findOrFail($id);
        $teacher->status = $request->status;
        $teacher->save();

        ActivityLogger::log(
            'UPDATE',
            'Mengubah status Teacher Event: ' . $teacher->name . ' menjadi ' . $teacher->status
        );

        return redirect()->back()->with('success', 'Status Teacher Event berhasil diperbarui.');
    }

    /**
     * Menampilkan form tambah Teacher Event
     */
    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        ActivityLogger::log('READ', 'Membuka form tambah Teacher Event');

        return view('panel.teacher_event.add');
    }

    /**
     * Menyimpan data Teacher Event baru
     */
    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'name' => 'required|string|max:50',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F',
            'nip' => 'nullable|string|max:30|unique:teacher_events,nip',
            'expertise_field' => 'nullable|string|max:50',
            'last_education' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'school_origin' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'letter_of_assignment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $teacher = new TeacherEvent();
        $teacher->id = (string) Str::uuid();
        $teacher->user_id = Auth::id();
        $teacher->name = $request->name;
        $teacher->birth_place = $request->birth_place;
        $teacher->birth_date = $request->birth_date;
        $teacher->gender = $request->gender;
        $teacher->nip = $request->nip;
        $teacher->expertise_field = $request->expertise_field;
        $teacher->last_education = $request->last_education;
        $teacher->whatsapp_number = $request->whatsapp_number;
        $teacher->address = $request->address;
        $teacher->school_origin = $request->school_origin;

        if ($request->hasFile('photo')) {
            $teacher->photo = $request->file('photo')->store('teacher_photos', 'public');
        }

        if ($request->hasFile('letter_of_assignment')) {
            $teacher->letter_of_assignment = $request->file('letter_of_assignment')->store('teacher_letters', 'public');
        }

        $teacher->save();

        ActivityLogger::log('CREATE', 'Menambahkan Teacher Event: ' . $teacher->name);

        return redirect()->route('teacher_event.list')->with('success', 'Data Teacher Event berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit Teacher Event
     */
    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getRecord'] = TeacherEvent::findOrFail($id);

        ActivityLogger::log('READ', 'Membuka form edit Teacher Event: ' . $data['getRecord']->name);

        return view('panel.teacher_event.edit', $data);
    }

    /**
     * Memperbarui data Teacher Event
     */
    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $teacher = TeacherEvent::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F',
            'nip' => 'nullable|string|max:30|unique:teacher_events,nip,' . $teacher->id,
            'expertise_field' => 'nullable|string|max:50',
            'last_education' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'school_origin' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'letter_of_assignment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $teacher->name = $request->name;
        $teacher->birth_place = $request->birth_place;
        $teacher->birth_date = $request->birth_date;
        $teacher->gender = $request->gender;
        $teacher->nip = $request->nip;
        $teacher->expertise_field = $request->expertise_field;
        $teacher->last_education = $request->last_education;
        $teacher->whatsapp_number = $request->whatsapp_number;
        $teacher->address = $request->address;
        $teacher->school_origin = $request->school_origin;

        if ($request->hasFile('photo')) {
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $teacher->photo = $request->file('photo')->store('teacher_photos', 'public');
        }

        if ($request->hasFile('letter_of_assignment')) {
            if ($teacher->letter_of_assignment && Storage::disk('public')->exists($teacher->letter_of_assignment)) {
                Storage::disk('public')->delete($teacher->letter_of_assignment);
            }
            $teacher->letter_of_assignment = $request->file('letter_of_assignment')->store('teacher_letters', 'public');
        }

        $teacher->save();

        ActivityLogger::log('UPDATE', 'Memperbarui Teacher Event: ' . $teacher->name);

        return redirect()->route('teacher_event.list')->with('success', 'Data Teacher Event berhasil diperbarui.');
    }

    /**
     * Form pendaftaran publik Teacher Event
     * (Hanya dapat diakses oleh Administrator & Kepala Sekolah)
     */
    public function formregistration()
    {
        $PermissionRole = PermissionRole::getPermission('Add Teacher Event Kepala Sekolah', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $role = Auth::user()->role->name ?? '';
        if (!in_array($role, ['Administrator', 'Kepala Sekolah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        ActivityLogger::log('READ', 'Membuka form registration Teacher Event');

        return view('panel.teacher_event.registration');
    }

    /**
     * Proses pendaftaran Teacher Event dari formregistration
     */
    public function registration(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Teacher Event Kepala Sekolah', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $role = Auth::user()->role->name ?? '';
        if (!in_array($role, ['Administrator', 'Kepala Sekolah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'name' => 'required|string|max:50',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F',
            'nip' => 'nullable|string|max:30|unique:teacher_events,nip',
            'expertise_field' => 'nullable|string|max:50',
            'last_education' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'school_origin' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'letter_of_assignment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $teacher = new TeacherEvent();
        $teacher->id = (string) Str::uuid();
        $teacher->user_id = Auth::id();
        $teacher->fill($request->except(['photo', 'letter_of_assignment']));

        if ($request->hasFile('photo')) {
            $teacher->photo = $request->file('photo')->store('teacher_photos', 'public');
        }

        if ($request->hasFile('letter_of_assignment')) {
            $teacher->letter_of_assignment = $request->file('letter_of_assignment')->store('teacher_letters', 'public');
        }

        $teacher->save();

        ActivityLogger::log('CREATE', 'Registrasi Teacher Event: ' . $teacher->name);

        return redirect()->route('teacher_event.my_registration')->with('success', 'Pendaftaran Student Event berhasil dikirim.');
    }

    /**
     * Menghapus Teacher Event
     */
    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $teacher = TeacherEvent::findOrFail($id);

        if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }

        if ($teacher->letter_of_assignment && Storage::disk('public')->exists($teacher->letter_of_assignment)) {
            Storage::disk('public')->delete($teacher->letter_of_assignment);
        }

        $teacher->delete();

        ActivityLogger::log('DELETE', 'Menghapus Teacher Event: ' . $teacher->name);

        return redirect()->route('teacher_event.list')->with('success', 'Data Teacher Event berhasil dihapus.');
    }
}
