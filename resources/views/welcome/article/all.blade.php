<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Articles - Yayasan Gasing Papua</title>
    <meta name="description" content="Kumpulan artikel Yayasan Gasing Papua">
    <meta name="keywords" content="artikel, yayasan gasing, papua">

    @include('welcome.head')
</head>

<body class="course-details-page">

    @include('welcome.navbar')

    <main class="main">

        <!-- Page Title -->
        <div class="page-title" data-aos="fade">
            <div class="heading">
                <div class="container">
                    <div class="row d-flex justify-content-center text-center">
                        <div class="col-lg-8">
                            <h1>Articles</h1>
                            <p class="mb-0">
                                Kumpulan artikel terbaru yang dipublikasikan oleh Yayasan Gasing Papua.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li class="current">Articles</li>
                    </ol>
                </div>
            </nav>
        </div>
        <!-- End Page Title -->

        <!-- Articles Section -->
        <section id="courses" class="courses section">
            <div class="container section-title" data-aos="fade-up">
                <h2>Articles</h2>
                <p>Latest Published Articles</p>
            </div>

            <div class="container">
                <div class="row">
                    @forelse($articles as $article)
                        <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in"
                            data-aos-delay="100">
                            <div class="course-item">

                                <img src="{{ $article->image_path ? asset('storage/' . $article->image_path) : asset('landingpage/assets/img/default-article.jpg') }}"
                                    class="img-fluid" alt="{{ $article->title }}">

                                <div class="course-content">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <p class="category">{{ $article->category->name ?? 'Uncategorized' }}</p>
                                        <p class="price">{{ $article->created_at->format('d M Y') }}</p>
                                    </div>

                                    <h3>
                                        <a href="{{ url('/articles/' . $article->slug) }}">
                                            {{ $article->title }}
                                        </a>
                                    </h3>

                                    <p class="description">
                                        {{ \Illuminate\Support\Str::limit($article->summary, 70) }}
                                    </p>

                                    {{-- Informasi penulis (opsional) --}}
                                    {{-- <div class="trainer d-flex justify-content-between align-items-center mt-3">
                                        <div class="trainer-profile d-flex align-items-center">
                                            <img src="{{ asset($article->user->photo ?? 'assets/img/user-default.png') }}"
                                                class="img-fluid" alt="">
                                            <a href="#" class="trainer-link ms-2">{{ $article->user->name }}</a>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>Tidak ada artikel yang diterbitkan.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $articles->links() }}
                </div>
            </div>
        </section>
        <!-- /Articles Section -->

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
x
