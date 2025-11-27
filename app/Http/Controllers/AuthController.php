<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login()
    {
        if (!empty(Auth::check())) {
            return redirect('dashboard');
        }

        // ==========================
        // GENERATE MATH CAPTCHA LEBIH KOMPLEKS
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
                // pastikan hasil positif
                $a = rand(20, 99);
                $b = rand(1, $a - 1);
                $result = $a - $b;
                break;

            case 'x':
                // angka kecil supaya user tidak keberatan
                $a = rand(2, 9);
                $b = rand(2, 9);
                $result = $a * $b;
                break;
        }

        // Simpan hasil & waktu generate ke session
        session([
            'login_captcha_result'        => $result,
            'login_captcha_generated_at'  => now()->timestamp,
        ]);

        return view('auth.login', compact('a', 'b', 'operator'));
    }

    public function auth_login(Request $request)
    {
        // ==========================
        // ANTI BOT: HONEYPOT
        // ==========================
        if ($request->filled('website')) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, silakan coba lagi.')
                ->withInput();
        }

        // ==========================
        // VALIDASI INPUT + CAPTCHA
        // ==========================
        $request->validate(
            [
                'email'          => 'required|email',
                'password'       => 'required',
                'captcha_answer' => 'required|numeric',
                'form_time'      => 'required|integer',
            ],
            [
                'email.required'          => 'Email wajib diisi.',
                'email.email'             => 'Format email tidak valid.',
                'password.required'       => 'Password wajib diisi.',
                'captcha_answer.required' => 'Jawaban captcha wajib diisi.',
                'captcha_answer.numeric'  => 'Jawaban captcha harus berupa angka.',
            ]
        );

        // ==========================
        // ANTI BOT: TIME-BASED CHECK
        // ==========================
        $start = (int) $request->input('form_time');
        $now   = now()->timestamp;
        $diff  = $now - $start;

        // Misal: user wajar butuh minimal 3 detik untuk isi form
        if ($start <= 0 || $diff < 3) {
            return redirect()
                ->back()
                ->withErrors(['email' => 'Terjadi kesalahan, silakan coba lagi.'])
                ->withInput();
        }

        // ==========================
        // CEK JAWABAN CAPTCHA
        // ==========================
        $expected = (int) session('login_captcha_result');

        if ((int) $request->captcha_answer !== $expected) {
            return redirect()
                ->back()
                ->withErrors(['captcha_answer' => 'Jawaban captcha salah, silakan coba lagi.'])
                ->withInput();
        }

        // Opsional: hapus captcha dari session setelah dicek
        session()->forget('login_captcha_result');
        session()->forget('login_captcha_generated_at');

        $remember = !empty($request->remember);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {

            // ==============================
            // AUTO ABSEN KETIKA LOGIN
            // ==============================
            try {
                app(\App\Http\Controllers\AttendanceCourseController::class)->autoCheckin($request);
            } catch (\Exception $e) {
                Log::error('Gagal auto checkin: ' . $e->getMessage());
            }

            return redirect('dashboard')->with('success', 'Login berhasil. Selamat datang!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Please enter correct email and password')
                ->withInput();
        }
    }

    public function logout()
    {
        $user = Auth::user();

        // =====================================
        // AUTO CHECKOUT KETIKA LOGOUT
        // =====================================
        try {
            if ($user) {
                app(\App\Http\Controllers\AttendanceCourseController::class)->checkout(new \Illuminate\Http\Request());
            }
        } catch (\Exception $e) {
            Log::error('Gagal auto checkout: ' . $e->getMessage());
        }

        Auth::logout();
        return redirect(url(''));
    }
}
