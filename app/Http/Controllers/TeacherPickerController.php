<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherPickerController extends Controller
{
    // Dropdown daftar guru (untuk Blade biasa)
    public function index()
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $teachers = User::whereHas('role', fn($q) => $q->where('name', 'Guru Les Gasing'))
            ->orderBy('name')->get();

        return view('panel.teacher_picker.index', compact('teachers'));
    }

    // Endpoint JSON untuk Select2/AJAX search
    public function search(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Assign Teacher', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $term = $request->input('q', '');
        $q = User::whereHas('role', fn($r) => $r->where('name', 'Guru Les Gasing'));

        if ($term) {
            $q->where('name', 'like', "%{$term}%");
        }

        return response()->json(
            $q->orderBy('name')->limit(20)->get(['id', 'name'])
        );
    }
}
