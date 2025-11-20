<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function list()
    {
        $PermissionRole = PermissionRole::getPermission('Document', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['PermissionAdd'] = PermissionRole::getPermission('Add Document', Auth::user()->role_id);
        $data['PermissionEdit'] = PermissionRole::getPermission('Edit Document', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Document', Auth::user()->role_id);

        $data['getRecord'] = Document::with('user')->orderBy('created_at', 'desc')->get();
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
