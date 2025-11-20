<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Session;

class ActivityLogger
{
    public static function log($action, $description = null)
    {
        if (!Auth::check()) {
            return;
        }

        try {
            $user = Auth::user();

            Session::create([
                'id' => (string) Str::uuid(),
                'user_id' => Auth::id(),
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
                'action' => $action,
                'description' => $description ?? "User {$user->name} melakukan {$action}",
                'payload' => json_encode(Request::except(['password', '_token'])),
                'last_activity' => now()->timestamp,
            ]);
        } catch (\Throwable $th) {
            // jangan hentikan proses utama
        }
    }
}
