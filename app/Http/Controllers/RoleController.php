<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Role', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Role', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Role', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Role', Auth::user()->role_id);

        // Query dasar
        $query = Role::query();

        // SEARCH (berdasarkan nama role)
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('id', 'like', '%' . $keyword . '%');
            });
        }

        // SORTING
        $allowedSortBy    = ['created_at', 'name', 'id'];
        $sortBy           = $request->get('sort_by');
        $sortDirectionRaw = $request->get('sort_direction');

        $sortDirection = $sortDirectionRaw === 'asc' ? 'asc' : 'desc';
        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $sortDirection);

        // PAGINATION (10 per halaman)
        $data['getRecord'] = $query->paginate(10)->withQueryString();

        // Kirim kembali nilai filter ke view
        $data['filter_keyword']  = $request->keyword;
        $data['sort_by']         = $sortBy;
        $data['sort_direction']  = $sortDirection;

        return view('panel.role.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Role', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $getPermission = Permission::getRecord();
        $data['getPermission'] = $getPermission;
        return view('panel.role.add', $data);
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Role', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $save       = new Role;
        $save->name = $request->name;
        $save->save();

        PermissionRole::InsertUpdateRecord($request->permission_id, $save->id);

        return redirect('panel/role')->with('success', "Role successfully created");
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Role', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getRecord']        = Role::getSingle($id);
        $data['getPermission']    = Permission::getRecord();
        $data['getRolePermission'] = PermissionRole::getRolePermission($id);
        return view('panel.role.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Role', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $save       = Role::getSingle($id);
        $save->name = $request->name;
        $save->save();

        PermissionRole::InsertUpdateRecord($request->permission_id, $save->id);

        return redirect('panel/role')->with('success', "Role successfully updated");
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Role', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $save = Role::getSingle($id);
        $save->delete();

        return redirect('panel/role')->with('success', "Role successfully deleted");
    }
}
