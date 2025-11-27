<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentCourse;
use App\Models\PermissionRole;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StudentCourseController extends Controller
{
    /**
     * Menampilkan daftar Student Course
     */
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Student Course', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Student Course', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Student Course', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Student Course', Auth::user()->role_id);

        // Query dasar
        $query = StudentCourse::query();

        // SEARCH: nama / NIK / sekolah / distrik
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('nik', 'like', '%' . $keyword . '%')
                    ->orWhere('school_origin', 'like', '%' . $keyword . '%')
                    ->orWhere('origin_district', 'like', '%' . $keyword . '%');
            });
        }

        // FILTER: level sekolah
        if (!empty($request->school_level)) {
            $query->where('school_level', $request->school_level);
        }

        // SORTING (whitelist kolom)
        $allowedSortBy = ['created_at', 'name', 'school_level', 'nik'];
        $sortBy        = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $sortDirection);

        // PAGINASI
        $data['getRecord'] = $query->paginate(10)->withQueryString();

        // simpan nilai filter & sort untuk view
        $data['filter_keyword']     = $request->keyword;
        $data['filter_school_level'] = $request->school_level;
        $data['sort_by']            = $sortBy;
        $data['sort_direction']     = $sortDirection;

        ActivityLogger::log('READ', 'Melihat daftar Student Course');

        return view('panel.student_course.list', $data);
    }

    /**
     * Menampilkan form tambah Student Course
     */
    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Student Course', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        ActivityLogger::log('READ', 'Membuka form tambah Student Course');

        return view('panel.student_course.add');
    }

    /**
     * Menyimpan data Student Course baru
     */
    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Student Course', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'name' => 'required|string|max:100',
            'nik' => 'nullable|string|max:30|unique:student_courses,nik',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F',
            'address' => 'nullable|string',
            'origin_district' => 'nullable|string|max:100',
            'school_level' => 'nullable|in:SD,SMP,SMA',
            'whatsapp_number' => 'nullable|string|max:20',
            'dream' => 'nullable|string|max:255',
            'school_origin' => 'nullable|string|max:100',
            'fee_note' => 'nullable|in:yellow,red,green',
            'note' => 'nullable|string|max:255',
        ]);

        $student = new StudentCourse();
        $student->id = (string) Str::uuid();
        $student->user_id = Auth::id();
        $student->name = $request->name;
        $student->fill($request->except('name'));
        $student->save();

        ActivityLogger::log('CREATE', 'Menambahkan Student Course: ' . $student->name);

        return redirect()->route('student_course.list')->with('success', 'Data Student Course berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit Student Course
     */
    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Student Course', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getRecord'] = StudentCourse::findOrFail($id);

        ActivityLogger::log('READ', 'Membuka form edit Student Course: ' . $data['getRecord']->name);

        return view('panel.student_course.edit', $data);
    }

    /**
     * Memperbarui data Student Course
     */
    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Student Course', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $student = StudentCourse::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'nik' => 'nullable|string|max:30|unique:student_courses,nik,' . $student->id,
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F',
            'address' => 'nullable|string',
            'origin_district' => 'nullable|string|max:100',
            'school_level' => 'nullable|in:SD,SMP,SMA',
            'whatsapp_number' => 'nullable|string|max:20',
            'dream' => 'nullable|string|max:255',
            'school_origin' => 'nullable|string|max:100',
            'fee_note' => 'nullable|in:yellow,red,green',
            'note' => 'nullable|string|max:255',
        ]);

        $student->name = $request->name;
        $student->fill($request->except('name'));
        $student->save();

        ActivityLogger::log('UPDATE', 'Memperbarui Student Course: ' . $student->name);

        return redirect()->route('student_course.list')->with('success', 'Data Student Course berhasil diperbarui.');
    }

    /**
     * Menghapus Student Course
     */
    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Student Course', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $student = StudentCourse::findOrFail($id);
        $name = $student->name;
        $student->delete();

        ActivityLogger::log('DELETE', 'Menghapus Student Course: ' . $name);

        return redirect()->route('student_course.list')->with('success', 'Data Student Course berhasil dihapus.');
    }
}
