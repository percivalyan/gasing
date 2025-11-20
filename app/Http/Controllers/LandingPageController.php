<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\StudentCourse;
use App\Models\StudentEvent;
use App\Models\TeacherEvent;
use App\Models\Article;
use App\Models\Document;

class LandingPageController extends Controller
{
    /**
     * Tampilkan landing page dengan data About + counter
     */
    public function index()
    {
        $about = About::first();
        $articles = Article::with('category', 'user')
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        // COUNTER
        $countSiswaLesGasing = StudentCourse::count();
        $countSiswaGasingEvent = StudentEvent::count();
        $countGuruGasing = TeacherEvent::count();

        return view('welcome', compact(
            'about',
            'articles',
            'countSiswaLesGasing',
            'countSiswaGasingEvent',
            'countGuruGasing'
        ));
    }

    public function all()
    {
        $articles = Article::with('category', 'user')
            ->where('status', 'published')
            ->latest()
            ->paginate(9);

        return view('welcome.article.all', compact('articles'));
    }

    public function detail($slug)
    {
        $article = Article::with('category', 'user')
            ->where('slug', $slug)
            ->where('status', 'published') // Pastikan artikel sudah publish
            ->firstOrFail();

        return view('welcome.article.detail', compact('article'));
    }

    public function documents()
    {
        $documents = Document::where('visibility', 'public')
            ->latest()
            ->paginate(9);

        return view('welcome.document.all', compact('documents'));
    }
}
