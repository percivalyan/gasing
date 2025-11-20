<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\ActivityLogger;

class GalleryController extends Controller
{
    public function list()
    {
        $PermissionRole = PermissionRole::getPermission('Gallery', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd'] = PermissionRole::getPermission('Add Gallery', Auth::user()->role_id);
        $data['PermissionEdit'] = PermissionRole::getPermission('Edit Gallery', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Gallery', Auth::user()->role_id);
        $data['getRecord'] = Gallery::with('user', 'images')->orderBy('created_at', 'desc')->get();

        ActivityLogger::log('READ', 'Melihat daftar galeri');
        return view('panel.gallery.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Gallery', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        ActivityLogger::log('READ', 'Membuka halaman tambah galeri');
        return view('panel.gallery.add');
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Gallery', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $gallery = Gallery::create([
            'title' => trim($request->title),
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'description' => $request->description,
            'user_id' => Auth::id()
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('galleries', 'public');
                GalleryImage::create([
                    'gallery_id' => $gallery->id,
                    'image_path' => $path,
                ]);
            }
        }

        ActivityLogger::log('CREATE', "Menambahkan galeri baru: {$gallery->title}");
        return redirect('gallery')->with('success', 'Galeri berhasil dibuat');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Gallery', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getRecord'] = Gallery::with('images')->findOrFail($id);

        ActivityLogger::log('READ', "Membuka halaman edit galeri: {$data['getRecord']->title}");
        return view('panel.gallery.edit', $data);
    }

    public function update($id, Request $request)
    {
        $gallery = Gallery::with('images')->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $gallery->title = trim($request->title);
        $gallery->slug = Str::slug($request->title) . '-' . Str::random(5);
        $gallery->description = $request->description;
        $gallery->save();

        // 🔥 Hapus gambar yang dicentang
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = \App\Models\GalleryImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // 🔥 Upload gambar baru (bisa banyak)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('galleries', 'public');
                \App\Models\GalleryImage::create([
                    'gallery_id' => $gallery->id,
                    'image_path' => $path,
                ]);
            }
        }

        ActivityLogger::log('UPDATE', "Memperbarui galeri: {$gallery->title}");

        return redirect('gallery')->with('success', 'Galeri berhasil diperbarui');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Gallery', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $gallery = Gallery::with('images')->findOrFail($id);
        foreach ($gallery->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $deletedTitle = $gallery->title;
        $gallery->delete();

        ActivityLogger::log('DELETE', "Menghapus galeri: {$deletedTitle}");
        return redirect('gallery')->with('success', 'Galeri berhasil dihapus');
    }

    public function deleteImage($id)
    {
        $image = GalleryImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        ActivityLogger::log('DELETE', "Menghapus gambar dari galeri ID: {$image->gallery_id}");
        return back()->with('success', 'Gambar berhasil dihapus');
    }
}
