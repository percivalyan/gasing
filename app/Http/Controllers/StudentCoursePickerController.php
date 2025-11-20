<?php

namespace App\Http\Controllers;

use App\Models\StudentCourse;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentCoursePickerController extends Controller
{
    // Dropdown siswa (untuk Blade biasa)
    public function index()
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $students = StudentCourse::orderBy('name')->get(['id', 'name', 'school_level']);

        return view('panel.student_picker.index', compact('students'));
    }

    // Endpoint JSON untuk Select2/AJAX search + filter level
    public function search(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $term  = $request->input('q', '');
        $level = $request->input('school_level'); // SD/SMP/SMA (opsional)

        $q = StudentCourse::query();
        if ($term)  $q->where('name', 'like', "%{$term}%");
        if ($level) $q->where('school_level', $level);

        return response()->json(
            $q->orderBy('name')->limit(30)->get(['id', 'name', 'school_level'])
        );
    }
}
