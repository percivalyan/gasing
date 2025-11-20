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
        return view('auth.login');
    }

    // public function auth_login(Request $request)
    // {
    //     $remember = !empty($request->remember) ? true : false;
    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
    //         return redirect('dashboard');
    //     } else {
    //         return redirect()->back()->with('error', 'Please enter correct email and password');
    //     }
    // }

    public function auth_login(Request $request)
    {
        $remember = !empty($request->remember) ? true : false;

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {

            // ==============================
            // AUTO ABSEN KETIKA LOGIN
            // ==============================
            try {
                app(\App\Http\Controllers\AttendanceCourseController::class)->autoCheckin($request);
            } catch (\Exception $e) {
                // Jangan gagalkan login, cukup log error
                Log::error('Gagal auto checkin: ' . $e->getMessage());
            }

            return redirect('dashboard');
        } else {
            return redirect()->back()->with('error', 'Please enter correct email and password');
        }
    }

    // public function logout()
    // {
    //     Auth::logout();
    //     return redirect(url(''));
    // }

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
