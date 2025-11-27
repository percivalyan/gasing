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
     * + generate captcha
     */
    public function showRegistrationForm()
    {
        // ==========================
        // GENERATE MATH CAPTCHA
        // ==========================
        $operators = ['+', '-', 'x'];
        $operator  = $operators[array_rand($operators)];

        switch ($operator) {
            case '+':
                $a = rand(10, 99);
                $b = rand(1, 50);
                $result = $a + $b;
                break;

            case '-':
                $a = rand(20, 99);
                $b = rand(1, $a - 1);
                $result = $a - $b;
                break;

            case 'x':
                $a = rand(2, 9);
                $b = rand(2, 9);
                $result = $a * $b;
                break;
        }

        // Simpan ke session khusus untuk register headmaster
        session([
            'headmaster_captcha_result'       => $result,
            'headmaster_captcha_generated_at' => now()->timestamp,
        ]);

        return view('auth.register_headmaster', compact('a', 'b', 'operator'));
    }

    /**
     * Proses penyimpanan registrasi Kepala Sekolah
     */
    public function register(Request $request)
    {
        // ==========================
        // HONEYPOT: FIELD JEBATAN BOT
        // ==========================
        if ($request->filled('website')) {
            // Jika field ini terisi, anggap bot dan tolak
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, silakan coba lagi.')
                ->withInput();
        }

        // ==========================
        // VALIDASI FORM + CAPTCHA
        // ==========================
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

            // Captcha
            'captcha_answer'  => ['required', 'numeric'],
            'form_time'       => ['required', 'integer'],
        ], [
            'captcha_answer.required' => 'Jawaban captcha wajib diisi.',
            'captcha_answer.numeric'  => 'Jawaban captcha harus berupa angka.',
        ]);

        // ==========================
        // TIME-BASED CHECK
        // ==========================
        $start = (int) $request->input('form_time');
        $now   = now()->timestamp;
        $diff  = $now - $start;

        // Minimal 3 detik sebagai batas wajar isi form
        if ($start <= 0 || $diff < 3) {
            return redirect()
                ->back()
                ->withErrors(['name' => 'Terjadi kesalahan sistem, silakan coba lagi.'])
                ->withInput();
        }

        // ==========================
        // CEK JAWABAN CAPTCHA
        // ==========================
        $expected = (int) session('headmaster_captcha_result');

        if ((int) $request->captcha_answer !== $expected) {
            return redirect()
                ->back()
                ->withErrors(['captcha_answer' => 'Jawaban captcha salah, silakan coba lagi.'])
                ->withInput();
        }

        // Hapus captcha dari session setelah dipakai
        session()->forget('headmaster_captcha_result');
        session()->forget('headmaster_captcha_generated_at');

        // ==========================
        // SIMPAN DATA USER
        // ==========================
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

        return redirect()
            ->route('login')
            ->with('success', 'Pendaftaran akun Kepala Sekolah berhasil. Silakan login.');
    }
}
