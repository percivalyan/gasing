<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use App\Models\PermissionRole;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller
{
    public function list()
    {
        $PermissionRole = PermissionRole::getPermission('About', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionEdit'] = PermissionRole::getPermission('Edit About', Auth::user()->role_id);

        // Ambil satu data saja (karena hanya satu page)
        $data['getRecord'] = About::first();

        // Jika belum ada data, buat default
        if (!$data['getRecord']) {
            $data['getRecord'] = About::create([
                'vision' => 'Visi Yayasan Gasing Center Papua',
                'mission' => 'Misi Yayasan Gasing Center Papua',
                'history' => 'Sejarah singkat Yayasan Gasing Center Papua.',
            ]);
        }

        ActivityLogger::log('READ', 'Melihat halaman About Yayasan');

        return view('panel.about.list', $data);
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit About', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getRecord'] = About::findOrFail($id);
        ActivityLogger::log('READ', 'Membuka halaman edit About Yayasan');

        return view('panel.about.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit About', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'history' => 'nullable|string',
        ]);

        $record = About::findOrFail($id);
        $record->update($request->only(['vision', 'mission', 'history']));

        ActivityLogger::log('UPDATE', 'Mengupdate data About ID: ' . $id);

        return redirect('about')->with('success', 'Data About berhasil diperbarui');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete About', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = About::findOrFail($id);
        $record->update([
            'vision' => null,
            'mission' => null,
            'history' => null,
        ]);

        ActivityLogger::log('CLEAR', 'Mengosongkan data About ID: ' . $id);

        return redirect('about')->with('success', 'Data About telah dikosongkan');
    }
}
