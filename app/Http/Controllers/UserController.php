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

    public function list()
    {
        $PermissionRole = PermissionRole::getPermission('User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['PermissionAdd']    = PermissionRole::getPermission('Add User', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit User', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete User', Auth::user()->role_id);

        $data['getRecord'] = User::getRecord(); // asumsi sudah ada scope/model method
        return view('panel.user.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getRole'] = Role::getRecord();
        return view('panel.user.add', $data);
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6'],
            'role_id'  => ['required','exists:roles,id'],
        ]);

        $user = new User;
        $user->name     = trim($request->name);
        $user->email    = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->role_id  = $request->role_id;

        // Field tambahan opsional bila ingin dipakai juga pada create umum:
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

        return redirect('panel/user')->with('success', "User successfully created");
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getRecord'] = User::getSingle($id);
        $data['getRole']   = Role::getRecord();
        return view('panel.user.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $user = User::getSingle($id);
        if (!$user) abort(404);

        $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id, 'id')],
            'password' => ['nullable','string','min:6'],
            'role_id'  => ['required','exists:roles,id'],
        ]);

        $user->name  = trim($request->name);
        $user->email = trim($request->email);
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->role_id = $request->role_id;

        // Field tambahan opsional:
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

        return redirect('panel/user')->with('success', "User successfully updated");
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $user = User::getSingle($id);
        if (!$user) abort(404);

        $user->delete();

        return redirect('panel/user')->with('success', "User successfully deleted");
    }

    /* =========================
     * EDIT PROFIL (TANPA UBAH ROLE)
     * ========================= */

    public function editProfile($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getRecord'] = User::getSingle($id);
        $data['getRole']   = Role::getRecord();
        return view('panel.user.edit-profile', $data);
    }

    public function updateProfile($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $user = User::getSingle($id);
        if (!$user) abort(404);

        $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id, 'id')],
            'password' => ['nullable','string','min:6'],
        ]);

        $user->name  = trim($request->name);
        $user->email = trim($request->email);
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        // tidak update role_id
        // Field tambahan opsional:
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

        return redirect()->route('user.edit-profile', ['id' => $id])
            ->with('success', 'User successfully updated');
    }

    /* ==========================================
     * KHUSUS: GURU LES GASING (ROLE TERKUNCI)
     * ========================================== */

    public function listGuruLesGasing()
    {
        $PermissionRole = PermissionRole::getPermission('User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd']    = PermissionRole::getPermission('Add User Guru Les Gasing', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit User Guru Les Gasing', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete User Guru Les Gasing', Auth::user()->role_id);

        $roleId = $this->guruLesGasingRoleId();
        $data['getRecord'] = User::where('role_id', $roleId)->orderBy('name')->get();

        return view('panel.user.list_guru_les_gasing', $data);
    }

    protected function guruLesGasingRoleId()
    {
        $role = Role::where('name', 'Guru Les Gasing')->first();
        abort_if(!$role, 500, 'Role "Guru Les Gasing" belum tersedia. Jalankan seeder atau tambah role.');
        return $role->id;
    }

    public function createGuruLesGasing()
    {
        $PermissionRole = PermissionRole::getPermission('Add User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        // Role dikunci di server, tidak kirim daftar role ke view
        return view('panel.user.create_guru_les_gasing');
    }

    public function storeGuruLesGasing(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],

            // Umum
            'nik'          => ['nullable', 'string', 'max:30', 'unique:users,nik'],
            'birth_place'  => ['required', 'string', 'max:255'],
            'birth_date'   => ['required', 'date'],
            'gender'       => ['required', Rule::in(['M','F'])],

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
        $user->role_id  = $this->guruLesGasingRoleId(); // kunci role

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

        return redirect()->route('user.list-guru-les-gasing')->with('success', 'Guru Les Gasing berhasil dibuat');
    }

    public function editGuruLesGasing($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $user = User::getSingle($id);
        abort_if(!$user, 404);
        abort_if((int)$user->role_id !== (int)$this->guruLesGasingRoleId(), 404);

        return view('panel.user.edit_guru_les_gasing', ['getRecord' => $user]);
    }

    public function updateGuruLesGasing($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $user = User::getSingle($id);
        abort_if(!$user, 404);
        abort_if((int)$user->role_id !== (int)$this->guruLesGasingRoleId(), 404);

        $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id, 'id')],
            'password' => ['nullable','string','min:6'],

            'nik'         => ['nullable','string','max:30', Rule::unique('users','nik')->ignore($user->id, 'id')],
            'birth_place' => ['required','string','max:255'],
            'birth_date'  => ['required','date'],
            'gender'      => ['required', Rule::in(['M','F'])],

            'nip'             => ['nullable','string','max:30', Rule::unique('users','nip')->ignore($user->id, 'id')],
            'expertise_field' => ['nullable','string','max:30'],
            'last_education'  => ['nullable','string','max:30'],
            'whatsapp_number' => ['nullable','string','max:20'],
            'address'         => ['nullable','string'],
        ]);

        $user->name  = trim($request->name);
        $user->email = trim($request->email);
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        // Tetap kunci role
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

        return redirect()->route('user.list-guru-les-gasing')->with('success', 'Guru Les Gasing berhasil diperbarui');
    }

    public function deleteGuruLesGasing($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete User Guru Les Gasing', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $user = User::getSingle($id);
        abort_if(!$user, 404);
        abort_if((int)$user->role_id !== (int)$this->guruLesGasingRoleId(), 404);

        $user->delete();

        return redirect()->route('user.list-guru-les-gasing')->with('success', 'Guru Les Gasing berhasil dihapus');
    }
}
