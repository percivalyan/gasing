<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrganizationStructure;
use App\Models\PermissionRole;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrganizationStructureController extends Controller
{
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Organization Structure', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Organization Structure', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Organization Structure', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Organization Structure', Auth::user()->role_id);

        // Query dasar
        $query = OrganizationStructure::query();

        // SEARCH: berdasarkan nama posisi
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('position', 'like', '%' . $keyword . '%')
                    ->orWhere('order', 'like', '%' . $keyword . '%');
            });
        }

        // SORTING (whitelist kolom)
        $allowedSortBy = ['order', 'position', 'created_at', 'id'];
        $sortBy        = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'order'; // default: urutan struktur
        }

        $query->orderBy($sortBy, $sortDirection);

        // PAGINATION (10 per halaman)
        $data['getRecord'] = $query->paginate(10)->withQueryString();

        // Simpan nilai filter/sort untuk view
        $data['filter_keyword'] = $request->keyword;
        $data['sort_by']        = $sortBy;
        $data['sort_direction'] = $sortDirection;

        ActivityLogger::log('READ', 'Melihat daftar struktur organisasi');

        return view('panel.organization.structure.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Organization Structure', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        ActivityLogger::log('READ', 'Membuka form tambah struktur organisasi');

        return view('panel.organization.structure.add');
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Organization Structure', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'position' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $structure = new OrganizationStructure();
        $structure->id = (string) Str::uuid();
        $structure->position = $request->position;
        $structure->order = $request->order ?? 0;
        $structure->save();

        ActivityLogger::log('CREATE', 'Menambah struktur organisasi: ' . $request->position);

        return redirect('organization/structure')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Organization Structure', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getRecord'] = OrganizationStructure::findOrFail($id);

        ActivityLogger::log('READ', 'Membuka form edit struktur organisasi: ' . $data['getRecord']->position);

        return view('panel.organization.structure.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Organization Structure', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'position' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $structure = OrganizationStructure::findOrFail($id);
        $structure->update([
            'position' => $request->position,
            'order' => $request->order ?? 0,
        ]);

        ActivityLogger::log('UPDATE', 'Mengedit struktur organisasi ID: ' . $id);

        return redirect('organization/structure')->with('success', 'Data berhasil diperbarui');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Organization Structure', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $structure = OrganizationStructure::findOrFail($id);
        $structure->delete();

        ActivityLogger::log('DELETE', 'Menghapus struktur organisasi ID: ' . $id);

        return redirect('organization/structure')->with('success', 'Data berhasil dihapus');
    }
}
