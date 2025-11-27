<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $article->title }} - Yayasan Gasing Papua</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($article->summary), 70) }}">
    <meta name="keywords" content="artikel, yayasan gasing, papua">

    @include('welcome.head')
</head>

<body class="course-details-page">

    @include('welcome.navbar')

    <main class="main">

        <!-- Page Title -->
        <div class="page-title" data-aos="fade">
            {{-- <div class="heading">
                <div class="container">
                    <div class="row d-flex justify-content-center text-center">
                        <div class="col-lg-8">
                            <h1>{{ $article->title }}</h1>
                            <p class="mb-0">
                                {{ \Illuminate\Support\Str::limit(strip_tags($article->summary), 70) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div> --}}
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/ar') }}">Articles</a></li>
                        <li class="current">{{ $article->title }}</li>
                    </ol>
                </div>
            </nav>
        </div>
        <!-- End Page Title -->

        <!-- Courses Course Details Section -->
        <section id="courses-course-details" class="courses-course-details section">
            <div class="container" data-aos="fade-up">

                <div class="row">
                    <div class="col-lg-12">

                        <h1>{{ $article->title }}</h1>

                        <img src="{{ $article->image_path ? asset('storage/' . $article->image_path) : asset('landingpage/assets/img/default-article.jpg') }}"
                            class="img-fluid mb-4" alt="{{ $article->title }}">

                        <div class="mb-3">
                            <span class="badge bg-primary me-2">
                                {{ $article->category->name ?? 'Uncategorized' }}
                            </span>
                            <span class="text-muted me-2">
                                <i class="bi bi-calendar-event"></i>
                                {{ $article->created_at->format('d M Y') }}
                            </span>
                            @if ($article->user)
                                <span class="text-muted">
                                    <i class="bi bi-person"></i>
                                    {{ $article->user->name }}
                                </span>
                            @endif
                        </div>

                        {{-- Konten artikel. Jika pakai editor HTML, gunakan {!! !!} --}}
                        <div class="article-content">
                            {!! $article->content !!}
                        </div>
                    </div>

                    {{-- <div class="col-lg-4">

                        <div class="course-info d-flex justify-content-between align-items-center">
                            <h5>Category</h5>
                            <p>{{ $article->category->name ?? 'Uncategorized' }}</p>
                        </div>

                        <div class="course-info d-flex justify-content-between align-items-center">
                            <h5>Published At</h5>
                            <p>{{ $article->created_at->format('d M Y') }}</p>
                        </div>

                        @if ($article->user)
                            <div class="course-info d-flex justify-content-between align-items-center">
                                <h5>Author</h5>
                                <p>{{ $article->user->name }}</p>
                            </div>
                        @endif

                        <div class="course-info d-flex justify-content-between align-items-center">
                            <h5>Status</h5>
                            <p class="text-capitalize">{{ $article->status }}</p>
                        </div>

                    </div> --}}
                </div>

            </div>
        </section>
        <!-- /Courses Course Details Section -->

        <!-- (Opsional) Tabs Section: bisa dihapus kalau tidak perlu -->
        {{-- 
        <section id="tabs" class="tabs section">
            ...
        </section>
        --}}

    </main>

    @include('welcome.footer')

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    @include('welcome.script')

</body>

</html>
