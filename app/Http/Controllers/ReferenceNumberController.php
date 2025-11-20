<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use App\Models\ReferenceNumber;
use App\Models\ReferenceNumberTracker;
use App\Models\LetterType;
use App\Models\PermissionRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LetterHelper;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReferenceNumberController extends Controller
{
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('ReferenceNumber', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd'] = PermissionRole::getPermission('Add ReferenceNumber', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete ReferenceNumber', Auth::user()->role_id);

        // Query dasar dengan relasi
        $query = ReferenceNumber::with(['user', 'letterType']);

        // FILTER
        if ($request->filled('letter_type_id')) {
            $query->where('letter_type_id', $request->letter_type_id);
        }
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // SORTING
        if ($request->filled('sort_by')) {
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($request->sort_by, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // PAGINATION (10 per halaman)
        $data['getRecord'] = $query->paginate(10)->withQueryString();

        // Tracker tetap sederhana
        $data['trackers'] = ReferenceNumberTracker::with('letterType')->get();

        // Data tambahan untuk filter dropdown
        $data['letterTypes'] = LetterType::all();
        $data['users'] = User::all();

        ActivityLogger::log('READ', 'Melihat daftar Nomor Surat');

        return view('panel.referencenumber.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add ReferenceNumber', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['letterTypes'] = LetterType::orderBy('subject', 'asc')->get();

        ActivityLogger::log('READ', 'Melihat halaman tambah Nomor Surat');
        return view('panel.referencenumber.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'letter_type_id' => 'required|exists:letter_types,id',
        ]);

        $letterType = LetterType::findOrFail($request->letter_type_id);
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        $romanMonth = \App\Helpers\LetterHelper::romanMonth($month);

        // Ambil tracker untuk tipe surat ini
        $tracker = ReferenceNumberTracker::firstOrCreate(
            ['letter_type_id' => $letterType->id],
            ['current_number' => 0]
        );

        $nextNumber = $tracker->current_number + 1;

        // Cek apakah nomor ini sudah pernah dibuat
        $exists = ReferenceNumber::where('letter_type_id', $letterType->id)
            ->where('serial_number', $nextNumber)
            ->where('year', $year)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Nomor ' . $nextNumber . ' untuk tipe surat ini sudah digunakan.');
        }

        $serialFormatted = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $ref = "{$serialFormatted}/YAGACEPA/{$letterType->code}/{$romanMonth}/{$year}";

        $reference = new ReferenceNumber();
        $reference->id = Str::uuid();
        $reference->letter_type_id = $letterType->id;
        $reference->serial_number = $nextNumber;
        $reference->institution = 'YAGACEPA';
        $reference->month = $month;
        $reference->year = $year;
        $reference->ref = $ref;
        $reference->user_id = Auth::id();
        $reference->save();

        // Update tracker urutan terakhir
        $tracker->current_number = $nextNumber;
        $tracker->save();

        ActivityLogger::log('CREATE', 'Membuat Nomor Surat: ' . $ref);

        return redirect('referencenumber')->with('success', 'Nomor surat berhasil dibuat: ' . $ref);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'letter_type_id' => 'required|exists:letter_types,id',
        ]);

        $tracker = ReferenceNumberTracker::where('letter_type_id', $request->letter_type_id)->first();

        if (!$tracker) {
            return back()->with('error', 'Tipe surat belum memiliki tracker.');
        }

        // Cek apakah sudah ada surat aktif
        $hasLetters = ReferenceNumber::where('letter_type_id', $request->letter_type_id)->exists();
        if ($hasLetters) {
            return back()->with('error', 'Tidak dapat reset karena sudah ada surat untuk tipe ini.');
        }

        $tracker->current_number = 0;
        $tracker->save();

        ActivityLogger::log('UPDATE', 'Mereset urutan nomor untuk tipe surat ID: ' . $request->letter_type_id);

        return back()->with('success', 'Urutan nomor untuk tipe surat ini berhasil direset ke 0.');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete ReferenceNumber', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus nomor surat.');
        }

        $reference = ReferenceNumber::find($id);
        if (!$reference) {
            return back()->with('error', 'Nomor surat tidak ditemukan.');
        }

        // Jika ingin juga mengurangi tracker saat menghapus nomor terakhir
        $tracker = ReferenceNumberTracker::where('letter_type_id', $reference->letter_type_id)->first();
        if ($tracker && $reference->serial_number == $tracker->current_number) {
            $tracker->current_number = $tracker->current_number - 1;
            $tracker->save();
        }

        $reference->delete();

        ActivityLogger::log('DELETE', 'Menghapus Nomor Surat: ' . $reference->ref);

        return back()->with('success', 'Nomor surat berhasil dihapus.');
    }
}
