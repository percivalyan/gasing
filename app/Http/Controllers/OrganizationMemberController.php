<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrganizationMember;
use App\Models\OrganizationStructure;
use App\Models\PermissionRole;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrganizationMemberController extends Controller
{
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Organization Member', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Organization Member', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Organization Member', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Organization Member', Auth::user()->role_id);

        // Query dasar dengan relasi
        $query = OrganizationMember::with('structure');

        // SEARCH: nama anggota / nama posisi
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhereHas('structure', function ($sub) use ($keyword) {
                        $sub->where('position', 'like', '%' . $keyword . '%');
                    });
            });
        }

        // FILTER: berdasarkan struktur (posisi)
        if (!empty($request->structure_id)) {
            $query->where('structure_id', $request->structure_id);
        }

        // SORTING (whitelist kolom)
        $allowedSortBy = ['structure_id', 'order', 'name', 'created_at', 'id'];
        $sortBy        = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'structure_id'; // default: per posisi
        }

        // Tetap jaga urutan struktur -> order
        if ($sortBy === 'structure_id') {
            $query->orderBy('structure_id', $sortDirection)
                ->orderBy('order', 'asc');
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        // PAGINATION (10 per halaman)
        $data['getRecord'] = $query->paginate(10)->withQueryString();

        // Data untuk filter dropdown
        $data['structures'] = OrganizationStructure::orderBy('order', 'asc')->get();

        // Simpan nilai filter/sort untuk view
        $data['filter_keyword']     = $request->keyword;
        $data['filter_structure_id'] = $request->structure_id;
        $data['sort_by']            = $sortBy;
        $data['sort_direction']     = $sortDirection;

        ActivityLogger::log('READ', 'Melihat daftar anggota organisasi');

        return view('panel.organization.member.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Organization Member', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['structure'] = OrganizationStructure::orderBy('order', 'asc')->get();

        ActivityLogger::log('READ', 'Membuka form tambah anggota organisasi');

        return view('panel.organization.member.add', $data);
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Organization Member', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'structure_id' => 'required|uuid|exists:organization_structures,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $member = new OrganizationMember();
        $member->id = (string) Str::uuid();
        $member->structure_id = $request->structure_id;
        $member->name = $request->name;
        $member->order = $request->order ?? 0;
        $member->save();

        ActivityLogger::log('CREATE', 'Menambah anggota organisasi: ' . $request->name);

        return redirect('organization/member')->with('success', 'Anggota berhasil ditambahkan');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Organization Member', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getRecord'] = OrganizationMember::findOrFail($id);
        $data['structure'] = OrganizationStructure::orderBy('order', 'asc')->get();

        ActivityLogger::log('READ', 'Membuka form edit anggota organisasi: ' . $data['getRecord']->name);

        return view('panel.organization.member.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Organization Member', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'structure_id' => 'required|uuid|exists:organization_structures,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $member = OrganizationMember::findOrFail($id);
        $member->update($request->only(['structure_id', 'name', 'order']));

        ActivityLogger::log('UPDATE', 'Mengupdate anggota organisasi ID: ' . $id);

        return redirect('organization/member')->with('success', 'Data anggota berhasil diperbarui');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Organization Member', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $member = OrganizationMember::findOrFail($id);
        $member->delete();

        ActivityLogger::log('DELETE', 'Menghapus anggota organisasi ID: ' . $id);

        return redirect('organization/member')->with('success', 'Data anggota berhasil dihapus');
    }
}
