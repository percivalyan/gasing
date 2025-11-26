<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class HeadmasterRegistrationController extends Controller
{
    /**
     * Ambil role_id untuk role "Kepala Sekolah"
     */
    protected function headmasterRoleId()
    {
        $role = Role::where('name', 'Kepala Sekolah')->first();
        abort_if(!$role, 500, 'Role "Kepala Sekolah" belum tersedia. Jalankan RoleSeeder atau tambah role secara manual.');

        return $role->id;
    }

    /**
     * Tampilkan form register khusus Kepala Sekolah
     */
    public function showRegistrationForm()
    {
        return view('auth.register_headmaster');
    }

    /**
     * Proses penyimpanan registrasi Kepala Sekolah
     */
    public function register(Request $request)
    {
        $request->validate([
            // Akun dasar
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],

            // Data umum
            'nik'         => ['nullable', 'string', 'max:30', 'unique:users,nik'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date'  => ['required', 'date'],
            'gender'      => ['required', Rule::in(['M', 'F'])],

            // Data pendidik (opsional / bisa diubah sesuai kebutuhan)
            'nip'             => ['nullable', 'string', 'max:30', 'unique:users,nip'],
            'expertise_field' => ['nullable', 'string', 'max:30'],
            'last_education'  => ['nullable', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'address'         => ['nullable', 'string'],
        ]);

        $user = new User();
        $user->name     = trim($request->name);
        $user->email    = trim($request->email);
        $user->password = Hash::make($request->password);

        // Kunci role ke "Kepala Sekolah"
        $user->role_id = $this->headmasterRoleId();

        // Umum
        $user->nik         = $request->nik;
        $user->birth_place = $request->birth_place;
        $user->birth_date  = $request->birth_date;
        $user->gender      = $request->gender;

        // Data guru/kepala sekolah
        $user->nip             = $request->nip;
        $user->expertise_field = $request->expertise_field;
        $user->last_education  = $request->last_education;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->address         = $request->address;

        $user->save();

        // Setelah register, silakan pilih: redirect ke login atau dashboard
        return redirect()
            ->route('login')
            ->with('success', 'Pendaftaran akun Kepala Sekolah berhasil. Silakan login.');
    }
}
