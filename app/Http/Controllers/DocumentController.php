<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Document', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Document', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Document', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Document', Auth::user()->role_id);

        // Query dasar
        $query = Document::with('user');

        // SEARCH: name / description / uploader name
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            })->orWhereHas('user', function ($u) use ($keyword) {
                $u->where('name', 'like', '%' . $keyword . '%');
            });
        }

        // FILTER: visibility (public / private)
        if (!empty($request->visibility)) {
            $query->where('visibility', $request->visibility);
        }

        // FILTER: has_file (all / with / without)
        if (!empty($request->has_file)) {
            if ($request->has_file === 'with') {
                $query->whereNotNull('file_path');
            } elseif ($request->has_file === 'without') {
                $query->whereNull('file_path');
            }
        }

        // SORTING
        $allowedSortBy    = ['created_at', 'name', 'visibility'];
        $sortBy           = $request->get('sort_by');
        $sortDirectionRaw = $request->get('sort_direction');

        $sortDirection = $sortDirectionRaw === 'asc' ? 'asc' : 'desc';
        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'created_at'; // default
        }

        $query->orderBy($sortBy, $sortDirection);

        // PAGINATION
        $data['getRecord'] = $query->paginate(10)->withQueryString();

        // Kirim nilai filter ke view
        $data['filter_keyword']   = $request->keyword;
        $data['filter_visibility'] = $request->visibility;
        $data['filter_has_file']  = $request->has_file;
        $data['sort_by']          = $sortBy;
        $data['sort_direction']   = $sortDirection;

        return view('panel.document.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Document', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        return view('panel.document.add');
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Document', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'visibility' => 'in:public,private',
            'file_path' => 'nullable|file|max:10240', // max 10MB
        ]);

        $document = new Document();
        $document->name = trim($request->name);
        $document->description = $request->description;
        $document->visibility = $request->visibility ?? 'private';
        $document->user_id = Auth::id();

        if ($request->hasFile('file_path')) {
            $document->file_path = $request->file('file_path')->store('documents', 'public');
        }

        $document->save();

        return redirect('document')->with('success', 'Document successfully created');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Document', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getRecord'] = Document::findOrFail($id);
        return view('panel.document.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Document', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'visibility' => 'in:public,private',
            'file_path' => 'nullable|file|max:10240',
        ]);

        $document = Document::findOrFail($id);
        $document->name = trim($request->name);
        $document->description = $request->description;
        $document->visibility = $request->visibility ?? 'private';

        if ($request->hasFile('file_path')) {
            $document->file_path = $request->file('file_path')->store('documents', 'public');
        }

        $document->save();

        return redirect('document')->with('success', 'Document successfully updated');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Document', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        Document::findOrFail($id)->delete();
        return redirect('document')->with('success', 'Document successfully deleted');
    }
}
