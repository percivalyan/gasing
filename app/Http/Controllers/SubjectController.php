<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\PermissionRole;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Subject', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Subject', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Subject', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Subject', Auth::user()->role_id);

        $q = Subject::query();

        // Search
        $search = $request->get('q');
        if (!empty($search)) {
            $q->where(function ($w) use ($search) {
                $w->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting (whitelist kolom)
        $allowedSortBy   = ['name', 'created_at'];
        $sortBy          = $request->get('sort_by');
        $sortDirection   = $request->get('sort_direction') === 'desc' ? 'desc' : 'asc'; // default asc

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'name';
        }

        $q->orderBy($sortBy, $sortDirection);

        $data['subjects'] = $q->paginate(20)->withQueryString();

        // kirim nilai filter & sort ke view
        $data['filter_q']       = $search;
        $data['sort_by']        = $sortBy;
        $data['sort_direction'] = $sortDirection;

        ActivityLogger::log('READ', 'Melihat daftar Subject');

        return view('panel.subjects.index', $data);
    }

    public function create()
    {
        $PermissionRole = PermissionRole::getPermission('Add Subject', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        ActivityLogger::log('READ', 'Membuka form tambah Subject');

        return view('panel.subjects.create');
    }

    public function store(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Subject', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:subjects,name'],
            'description' => ['nullable', 'string'],
        ]);

        $subject = Subject::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        ActivityLogger::log('CREATE', 'Menambahkan Subject: ' . $subject->name);

        return redirect()->route('subjects.index')->with('success', 'Subject berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Subject', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['subject'] = Subject::findOrFail($id);

        ActivityLogger::log('READ', 'Membuka form edit Subject: ' . $data['subject']->name);

        return view('panel.subjects.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Subject', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $subject = Subject::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:subjects,name,' . $subject->id . ',id'],
            'description' => ['nullable', 'string'],
        ]);

        $subject->name = $request->name;
        $subject->description = $request->description;
        $subject->save();

        ActivityLogger::log('UPDATE', 'Memperbarui Subject: ' . $subject->name);

        return redirect()->route('subjects.index')->with('success', 'Subject berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Subject', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $subject = Subject::findOrFail($id);
        $name = $subject->name;
        $subject->delete();

        ActivityLogger::log('DELETE', 'Menghapus Subject: ' . $name);

        return redirect()->route('subjects.index')->with('success', 'Subject berhasil dihapus.');
    }

    /**
     * Endpoint untuk membuat subject langsung dari form Lesson (AJAX)
     * Request: name (string)
     * Response: json { success: true, data: { id, name } }
     */
    public function storeInline(Request $request)
    {
        // bisa bypass permission atau cek permission Add Subject sesuai kebutuhan
        $PermissionRole = PermissionRole::getPermission('Add Subject', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            return response()->json(['success' => false, 'message' => 'Tidak punya izin menambah subject.'], 403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $name = trim($request->input('name'));
        if ($name === '') {
            return response()->json(['success' => false, 'message' => 'Nama subject kosong.'], 422);
        }

        // case-insensitive check & create
        $subject = Subject::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first();
        if (!$subject) {
            $subject = Subject::create([
                'id' => (string) Str::uuid(),
                'name' => $name,
                'description' => null,
            ]);
            ActivityLogger::log('CREATE', 'Menambahkan Subject (inline): ' . $subject->name);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $subject->id,
                'name' => $subject->name,
            ],
        ], 201);
    }
}
