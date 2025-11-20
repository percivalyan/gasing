<?php

namespace App\Http\Controllers;

use App\Models\StudentEvent;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentEventPickerController extends Controller
{
    // View dropdown daftar student_event (Blade)
    public function index()
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $students = StudentEvent::orderBy('name')->get(['id', 'name', 'school_level']);

        return view('panel.student_event_picker.index', compact('students'));
    }

    // Endpoint JSON untuk Select2/AJAX search + filter level (opsional)
    public function search(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $term  = $request->input('q', '');
        $level = $request->input('school_level');

        $q = StudentEvent::query();
        if ($term)  $q->where('name', 'like', "%{$term}%");
        if ($level) $q->where('school_level', $level);

        return response()->json(
            $q->orderBy('name')->limit(30)->get(['id', 'name', 'school_level'])
        );
    }
}
