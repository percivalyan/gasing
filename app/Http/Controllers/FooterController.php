<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Footer;
use App\Models\PermissionRole;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class FooterController extends Controller
{
    public function list()
    {
        // Cek permission akses menu Footer
        $PermissionRole = PermissionRole::getPermission('Footer', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        // Cek permission edit
        $data['PermissionEdit'] = PermissionRole::getPermission('Edit Footer', Auth::user()->role_id);

        // Ambil satu data saja (karena hanya satu footer)
        $data['getRecord'] = Footer::first();

        // Jika belum ada data, buat default
        if (!$data['getRecord']) {
            $data['getRecord'] = Footer::create([
                'phone'             => '08123456789',
                'email'             => 'info@yayasangasingpapua.org',
                'address_street'    => 'Jl. Contoh Alamat Yayasan Gasing Papua',
                'address_post_code' => '99111',
            ]);
        }

        ActivityLogger::log('READ', 'Melihat halaman Footer (kontak & alamat)');

        return view('panel.footer.list', $data);
    }

    public function edit($id)
    {
        // Cek permission edit
        $PermissionRole = PermissionRole::getPermission('Edit Footer', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getRecord'] = Footer::findOrFail($id);

        ActivityLogger::log('READ', 'Membuka halaman edit Footer ID: ' . $id);

        return view('panel.footer.edit', $data);
    }

    public function update($id, Request $request)
    {
        // Cek permission edit
        $PermissionRole = PermissionRole::getPermission('Edit Footer', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'phone'             => 'nullable|string|max:255',
            'email'             => 'nullable|email|max:255',
            'address_street'    => 'nullable|string|max:255',
            'address_post_code' => 'nullable|string|max:50',
        ]);

        $record = Footer::findOrFail($id);

        $record->update($request->only([
            'phone',
            'email',
            'address_street',
            'address_post_code',
        ]));

        ActivityLogger::log('UPDATE', 'Mengupdate data Footer ID: ' . $id);

        return redirect('footer')->with('success', 'Data Footer berhasil diperbarui');
    }

    public function delete($id)
    {
        // Cek permission delete
        $PermissionRole = PermissionRole::getPermission('Delete Footer', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $record = Footer::findOrFail($id);

        // Mengosongkan isi footer, bukan hapus row
        $record->update([
            'phone'             => null,
            'email'             => null,
            'address_street'    => null,
            'address_post_code' => null,
        ]);

        ActivityLogger::log('CLEAR', 'Mengosongkan data Footer ID: ' . $id);

        return redirect('footer')->with('success', 'Data Footer telah dikosongkan');
    }
}
