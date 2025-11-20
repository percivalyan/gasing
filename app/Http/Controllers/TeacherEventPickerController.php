<?php

namespace App\Http\Controllers;

use App\Models\TeacherEvent;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherEventPickerController extends Controller
{
    // View dropdown daftar teacher_event (Blade)
    public function index()
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $teachers = TeacherEvent::orderBy('name')->get();

        return view('panel.teacher_event_picker.index', compact('teachers'));
    }

    // Endpoint JSON untuk Select2/AJAX search
    public function search(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher Event', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $term = $request->input('q', '');
        $q = TeacherEvent::query();

        if ($term) {
            $q->where('name', 'like', "%{$term}%");
        }

        return response()->json(
            $q->orderBy('name')->limit(20)->get(['id', 'name'])
        );
    }
}
