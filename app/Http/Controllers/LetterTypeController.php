<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterType;
use App\Models\PermissionRole;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class LetterTypeController extends Controller
{
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('LetterType', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['PermissionAdd']    = PermissionRole::getPermission('Add LetterType', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit LetterType', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete LetterType', Auth::user()->role_id);

        // Query dasar
        $query = LetterType::query();

        // SEARCH: subject / code
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('subject', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%');
            });
        }

        // SORTING (whitelist kolom)
        $allowedSortBy = ['subject', 'code', 'created_at', 'id'];
        $sortBy        = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'subject'; // default
        }

        $query->orderBy($sortBy, $sortDirection);

        // PAGINATION (10 per halaman)
        $data['getRecord'] = $query->paginate(10)->withQueryString();

        // Simpan nilai filter/sort untuk view
        $data['filter_keyword'] = $request->keyword;
        $data['sort_by']        = $sortBy;
        $data['sort_direction'] = $sortDirection;

        ActivityLogger::log('READ', 'Melihat daftar Jenis Surat');

        return view('panel.lettertype.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add LetterType', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        ActivityLogger::log('READ', 'Membuka halaman tambah Jenis Surat');

        return view('panel.lettertype.add');
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add LetterType', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'subject' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:letter_types,code',
        ]);

        LetterType::create([
            'subject' => $request->subject,
            'code' => $request->code,
        ]);

        ActivityLogger::log('CREATE', 'Menambahkan Jenis Surat: ' . $request->subject);

        return redirect('lettertype')->with('success', 'Jenis Surat successfully created');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit LetterType', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        ActivityLogger::log('READ', 'Membuka halaman edit Jenis Surat');

        $data['getRecord'] = LetterType::findOrFail($id);
        return view('panel.lettertype.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit LetterType', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'subject' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:letter_types,code,' . $id,
        ]);

        $record = LetterType::findOrFail($id);
        $record->subject = $request->subject;
        $record->code = $request->code;
        $record->save();

        ActivityLogger::log('UPDATE', 'Mengupdate Jenis Surat ID: ' . $id);

        return redirect('lettertype')->with('success', 'Jenis Surat successfully updated');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete LetterType', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        ActivityLogger::log('DELETE', 'Menghapus Jenis Surat ID: ' . $id);

        LetterType::findOrFail($id)->delete();

        return redirect('lettertype')->with('success', 'Jenis Surat successfully deleted');
    }
}
