<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionLogController extends Controller
{
    /**
     * Tampilkan daftar session log.
     */
    public function index()
    {
        $user = Auth::user();

        // Pastikan relasi role dimuat
        $user->load('role');

        // Jika user adalah Administrator → tampilkan semua session
        if ($user->role && $user->role->name === 'Administrator') {
            $sessions = Session::with('user')
                ->orderByDesc('last_activity')
                ->paginate(20);
        } else {
            // Selain Administrator → tampilkan hanya session miliknya
            $sessions = Session::with('user')
                ->where('user_id', $user->id)
                ->orderByDesc('last_activity')
                ->paginate(20);
        }

        return view('admin.session_logs', compact('sessions'));
    }

    /**
     * Hapus log session tertentu.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $user->load('role');

        // Hanya Administrator yang boleh hapus
        if (!($user->role && $user->role->name === 'Administrator')) {
            abort(403, 'Unauthorized.');
        }

        $session = Session::findOrFail($id);
        $session->delete();

        return back()->with('success', 'Session log deleted successfully.');
    }
}
