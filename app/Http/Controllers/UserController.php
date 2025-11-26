<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

use App\Models\Role;
use App\Models\User;
use App\Models\PermissionRole;

class UserController extends Controller
{
    /* =========================
     * USER UMUM (SEMUA ROLE)
     * ========================= */

    /**
     * List user umum
     */
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['PermissionAdd']    = PermissionRole::getPermission('Add User', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit User', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete User', Auth::user()->role_id);

        // Query dasar + join role
        $query = User::select('users.*', 'roles.name as role_name')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id');

        // SEARCH (name, email, role_name)
        if (!empty($request->keyword)) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('users.name', 'like', '%' . $keyword . '%')
                    ->orWhere('users.email', 'like', '%' . $keyword . '%')
                    ->orWhere('roles.name', 'like', '%' . $keyword . '%');
            });
        }

        // SORTING
        $allowedSortBy = ['name', 'email', 'role_name', 'created_at'];
        $sortBy = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';

        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'created_at';
        }

        // mapping sort_by ke kolom yang tepat
        if ($sortBy === 'role_name') {
            $query->orderBy('roles.name', $sortDirection);
        } else {
            $query->orderBy('users.' . $sortBy, $sortDirection);
        }

        // PAGINATION
        $perPage = (int) $request->get('per_page', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $data['getRecord']        = $query->paginate($perPage)->withQueryString();
        $data['filter_keyword']   = $request->keyword;
        $data['sort_by']          = $sortBy;
        $data['sort_direction']   = $sortDirection;
        $data['per_page']         = $perPage;

        return view('panel.user.list', $data);
    }

    /**
     * Form tambah user
     */
    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getRole'] = Role::getRecord();

        return view('panel.user.add', $data);
    }

    /**
     * Simpan user baru
     */
    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role_id'  => ['required', 'exists:roles,id'],
        ]);

        $user = new User();
        $user->name     = trim($request->name);
        $user->email    = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->role_id  = $request->role_id;

        // Field tambahan opsional
        $user->nik             = $request->nik;
        $user->birth_place     = $request->birth_place;
        $user->birth_date      = $request->birth_date;
        $user->gender          = $request->gender;
        $user->nip             = $request->nip;
        $user->expertise_field = $request->expertise_field;
        $user->last_education  = $request->last_education;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->address         = $request->address;

        $user->save();

        return redirect()->route('user.index')->with('success', 'User successfully created');
    }

    /**
     * Form edit user
     */
    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getRecord'] = User::getSingle($id);
        if (!$data['getRecord']) {
            abort(404);
        }

        $data['getRole'] = Role::getRecord();

        return view('panel.user.edit', $data);
    }

    /**
     * Update user
     */
    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $user = User::getSingle($id);
        if (!$user) {
            abort(404);
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id, 'id')],
            'password' => ['nullable', 'string', 'min:6'],
            'role_id'  => ['required', 'exists:roles,id'],
        ]);

        $user->name  = trim($request->name);
        $user->email = trim($request->email);

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->role_id = $request->role_id;

        // Field tambahan opsional
        $user->nik             = $request->nik;
        $user->birth_place     = $request->birth_place;
        $user->birth_date      = $request->birth_date;
        $user->gender          = $request->gender;
        $user->nip             = $request->nip;
        $user->expertise_field = $request->expertise_field;
        $user->last_education  = $request->last_education;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->address         = $request->address;

        $user->save();

        return redirect()->route('user.index')->with('success', 'User successfully updated');
    }

    /**
     * Hapus user
     */
    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $user = User::getSingle($id);
        if (!$user) {
            abort(404);
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User successfully deleted');
    }

    /* =========================
     * EDIT PROFIL (TANPA UBAH ROLE)
     * ========================= */

    /**
     * Form edit profil user (tidak mengubah role)
     */
    public function editProfile($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getRecord'] = User::getSingle($id);
        if (!$data['getRecord']) {
            abort(404);
        }

        // getRole hanya jika perlu di form edit profile, boleh dihilangkan jika tidak dipakai
        $data['getRole'] = Role::getRecord();

        return view('panel.user.edit-profile', $data);
    }

    /**
     * Update profil user (tanpa ubah role)
     */
    public function updateProfile($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $user = User::getSingle($id);
        if (!$user) {
            abort(404);
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id, 'id')],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->name  = trim($request->name);
        $user->email = trim($request->email);

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        // Role tidak diubah
        // Field tambahan opsional
        $user->nik             = $request->nik;
        $user->birth_place     = $request->birth_place;
        $user->birth_date      = $request->birth_date;
        $user->gender          = $request->gender;
        $user->nip             = $request->nip;
        $user->expertise_field = $request->expertise_field;
        $user->last_education  = $request->last_education;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->address         = $request->address;

        $user->save();

        return redirect()
            ->route('user.edit-profile', ['id' => $id])
            ->with('success', 'User successfully updated');
    }

    /* ==========================================
     * KHUSUS: GURU LES GASING (ROLE TERKUNCI)
     * ========================================== */

    /**
     * List khusus Guru Les Gasing
     */
    public function listGuruLesGasing()
    {
        $PermissionRole = PermissionRole::getPermission('User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['PermissionAdd']    = PermissionRole::getPermission('Add User Guru Les Gasing', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit User Guru Les Gasing', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete User Guru Les Gasing', Auth::user()->role_id);

        $roleId = $this->guruLesGasingRoleId();
        $data['getRecord'] = User::where('role_id', $roleId)
            ->orderBy('name')
            ->get();

        return view('panel.user.list_guru_les_gasing', $data);
    }

    /**
     * Helper untuk mendapatkan role_id = "Guru Les Gasing"
     */
    protected function guruLesGasingRoleId()
    {
        $role = Role::where('name', 'Guru Les Gasing')->first();
        abort_if(!$role, 500, 'Role "Guru Les Gasing" belum tersedia. Jalankan seeder atau tambah role.');

        return $role->id;
    }

    /**
     * Form tambah Guru Les Gasing
     */
    public function createGuruLesGasing()
    {
        $PermissionRole = PermissionRole::getPermission('Add User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        // Role dikunci di server, tidak perlu kirim list role
        return view('panel.user.create_guru_les_gasing');
    }

    /**
     * Simpan Guru Les Gasing baru
     */
    public function storeGuruLesGasing(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],

            // Umum
            'nik'          => ['nullable', 'string', 'max:30', 'unique:users,nik'],
            'birth_place'  => ['required', 'string', 'max:255'],
            'birth_date'   => ['required', 'date'],
            'gender'       => ['required', Rule::in(['M', 'F'])],

            // Guru
            'nip'              => ['nullable', 'string', 'max:30', 'unique:users,nip'],
            'expertise_field'  => ['nullable', 'string', 'max:30'],
            'last_education'   => ['nullable', 'string', 'max:30'],
            'whatsapp_number'  => ['nullable', 'string', 'max:20'],
            'address'          => ['nullable', 'string'],
        ]);

        $user = new User();
        $user->name     = trim($request->name);
        $user->email    = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->role_id  = $this->guruLesGasingRoleId(); // role dikunci

        // Umum
        $user->nik         = $request->nik;
        $user->birth_place = $request->birth_place;
        $user->birth_date  = $request->birth_date;
        $user->gender      = $request->gender;

        // Guru
        $user->nip             = $request->nip;
        $user->expertise_field = $request->expertise_field;
        $user->last_education  = $request->last_education;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->address         = $request->address;

        $user->save();

        return redirect()
            ->route('user.list-guru-les-gasing')
            ->with('success', 'Guru Les Gasing berhasil dibuat');
    }

    /**
     * Form edit Guru Les Gasing
     */
    public function editGuruLesGasing($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $user = User::getSingle($id);
        abort_if(!$user, 404);
        abort_if((int) $user->role_id !== (int) $this->guruLesGasingRoleId(), 404);

        return view('panel.user.edit_guru_les_gasing', ['getRecord' => $user]);
    }

    /**
     * Update Guru Les Gasing
     */
    public function updateGuruLesGasing($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $user = User::getSingle($id);
        abort_if(!$user, 404);
        abort_if((int) $user->role_id !== (int) $this->guruLesGasingRoleId(), 404);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id, 'id')],
            'password' => ['nullable', 'string', 'min:6'],

            'nik'         => ['nullable', 'string', 'max:30', Rule::unique('users', 'nik')->ignore($user->id, 'id')],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date'  => ['required', 'date'],
            'gender'      => ['required', Rule::in(['M', 'F'])],

            'nip'             => ['nullable', 'string', 'max:30', Rule::unique('users', 'nip')->ignore($user->id, 'id')],
            'expertise_field' => ['nullable', 'string', 'max:30'],
            'last_education'  => ['nullable', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'address'         => ['nullable', 'string'],
        ]);

        $user->name  = trim($request->name);
        $user->email = trim($request->email);

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        // Tetap kunci role sebagai Guru Les Gasing
        $user->role_id = $this->guruLesGasingRoleId();

        // Umum
        $user->nik         = $request->nik;
        $user->birth_place = $request->birth_place;
        $user->birth_date  = $request->birth_date;
        $user->gender      = $request->gender;

        // Guru
        $user->nip             = $request->nip;
        $user->expertise_field = $request->expertise_field;
        $user->last_education  = $request->last_education;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->address         = $request->address;

        $user->save();

        return redirect()
            ->route('user.list-guru-les-gasing')
            ->with('success', 'Guru Les Gasing berhasil diperbarui');
    }

    /**
     * Hapus Guru Les Gasing
     */
    public function deleteGuruLesGasing($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $user = User::getSingle($id);
        abort_if(!$user, 404);
        abort_if((int) $user->role_id !== (int) $this->guruLesGasingRoleId(), 404);

        $user->delete();

        return redirect()
            ->route('user.list-guru-les-gasing')
            ->with('success', 'Guru Les Gasing berhasil dihapus');
    }
}
