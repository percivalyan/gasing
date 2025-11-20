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
    public function list()
    {
        $PermissionRole = PermissionRole::getPermission('Student Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd'] = PermissionRole::getPermission('Add Student Event', Auth::user()->role_id);
        $data['PermissionEdit'] = PermissionRole::getPermission('Edit Student Event', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Student Event', Auth::user()->role_id);

        $data['getRecord'] = StudentEvent::orderBy('created_at', 'desc')->get();

        ActivityLogger::log('READ', 'Melihat daftar Student Event');

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
        $role = Auth::user()->role->name ?? '';
        if (!in_array($role, ['Administrator', 'Kepala Sekolah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        ActivityLogger::log('READ', 'Membuka form registration Student Event');

        return view('panel.student_event.registration');
    }

    /**
     * Proses pendaftaran Student Event dari formregistration
     */
    public function registration(Request $request)
    {
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

        return redirect()->back()->with('success', 'Pendaftaran Student Event berhasil.');
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
}
