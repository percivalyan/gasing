<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Category', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Category', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Category', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Category', Auth::user()->role_id);

        // Query dasar
        $query = Category::query();

        // SEARCH: berdasarkan nama / id
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('id', 'like', '%' . $keyword . '%');
            });
        }

        // SORTING
        $allowedSortBy    = ['id', 'name']; // bisa ditambah 'created_at' jika kolomnya ada
        $sortBy           = $request->get('sort_by');
        $sortDirectionRaw = $request->get('sort_direction');

        $sortDirection = $sortDirectionRaw === 'asc' ? 'asc' : 'desc';
        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'id'; // default: id
        }

        $query->orderBy($sortBy, $sortDirection);

        // PAGINATION (10 per halaman)
        $data['getRecord'] = $query->paginate(10)->withQueryString();

        // Kirim balik nilai filter ke view
        $data['filter_keyword'] = $request->keyword;
        $data['sort_by']        = $sortBy;
        $data['sort_direction'] = $sortDirection;

        return view('panel.category.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Category', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        return view('panel.category.add');
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Category', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category       = new Category();
        $category->name = trim($request->name);
        if ($request->hasFile('image_path')) {
            $category->image_path = $request->file('image_path')->store('categories', 'public');
        }
        $category->save();

        return redirect('category')->with('success', 'Category successfully created');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Category', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getRecord'] = Category::findOrFail($id);
        return view('panel.category.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Category', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category       = Category::findOrFail($id);
        $category->name = trim($request->name);
        if ($request->hasFile('image_path')) {
            $category->image_path = $request->file('image_path')->store('categories', 'public');
        }
        $category->save();

        return redirect('category')->with('success', 'Category successfully updated');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Category', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        Category::findOrFail($id)->delete();
        return redirect('category')->with('success', 'Category successfully deleted');
    }
}
