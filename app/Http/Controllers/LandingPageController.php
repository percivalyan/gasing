<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\StudentCourse;
use App\Models\StudentEvent;
use App\Models\TeacherEvent;
use App\Models\Article;
use App\Models\Document;
use App\Models\Footer;

class LandingPageController extends Controller
{
    /**
     * Tampilkan landing page dengan data About + counter
     */
    public function index()
    {
        $about = About::first();
        $footer = Footer::first();
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
            'countGuruGasing',
            'footer'
        ));
    }

    public function all()
    {
        $footer = Footer::first();
        $articles = Article::with('category', 'user')
            ->where('status', 'published')
            ->latest()
            ->paginate(9);

        return view('welcome.article.all', compact('articles', 'footer'));
    }

    public function detail($slug)
    {
        $footer = Footer::first();
        $article = Article::with('category', 'user')
            ->where('slug', $slug)
            ->where('status', 'published') // Pastikan artikel sudah publish
            ->firstOrFail();

        return view('welcome.article.detail', compact('article', 'footer'));
    }

    public function documents()
    {
        $footer = Footer::first();
        $documents = Document::where('visibility', 'public')
            ->latest()
            ->paginate(9);

        return view('welcome.document.all', compact('documents', 'footer'));
    }

    public function mapPapua()
    {
        $footer = Footer::first();

        // Contoh mengambil dari StudentEvent dan TeacherEvent
        // Sesuaikan dengan kebutuhan Anda
        $locations = StudentEvent::select('name', 'latitude', 'longitude', 'mimpi as info')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('welcome.maps.map', compact('locations', 'footer'));
    }
}
