<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function list()
    {
        $PermissionRole = PermissionRole::getPermission('Article', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['PermissionAdd'] = PermissionRole::getPermission('Add Article', Auth::user()->role_id);
        $data['PermissionEdit'] = PermissionRole::getPermission('Edit Article', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Article', Auth::user()->role_id);

        $data['getRecord'] = Article::with('category', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('panel.article.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Article', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getCategory'] = Category::orderBy('name')->get();
        return view('panel.article.add', $data);
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Article', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $article = new Article();
        $article->title = trim($request->title);
        $article->slug = Str::slug($request->title) . '-' . Str::random(5);
        $article->summary = trim($request->summary);
        $article->content = $request->content;
        $article->category_id = $request->category_id;
        $article->user_id = Auth::id();
        $article->status = $request->status ?? 'draft';

        if ($request->hasFile('image_path')) {
            $article->image_path = $request->file('image_path')->store('articles', 'public');
        }

        $article->save();

        return redirect('article')->with('success', 'Article successfully created');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Article', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['getRecord'] = Article::findOrFail($id);
        $data['getCategory'] = Category::orderBy('name')->get();

        return view('panel.article.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Article', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $article = Article::findOrFail($id);
        $article->title = trim($request->title);
        $article->summary = trim($request->summary);
        $article->content = $request->content;
        $article->category_id = $request->category_id;
        $article->status = $request->status ?? 'draft';

        if ($request->hasFile('image_path')) {
            $article->image_path = $request->file('image_path')->store('articles', 'public');
        }

        $article->save();

        return redirect('article')->with('success', 'Article successfully updated');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Article', Auth::user()->role_id);
        if (empty($PermissionRole)) {
            abort(404);
        }

        Article::findOrFail($id)->delete();
        return redirect('article')->with('success', 'Article successfully deleted');
    }
}
