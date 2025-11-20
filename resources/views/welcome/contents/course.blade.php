<section id="courses" class="courses section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Articles</h2>
        <p>Latest Published Articles</p>
    </div>

    <div class="container">
        <!-- Wrapper untuk scroll horizontal -->
        <div class="article-scroll">
            <div class="d-flex">
                @forelse($articles as $article)
                    <div class="article-card" data-aos="zoom-in" data-aos-delay="100">
                        <div class="course-item">

                            {{-- Gambar artikel --}}
                            <img src="{{ $article->image_path
                                    ? asset('storage/' . $article->image_path)
                                    : asset('landingpage/assets/img/course-1.jpg') }}"
                                class="article-img" alt="{{ $article->title }}">

                            <div class="course-content">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p class="category">{{ $article->category->name ?? 'Uncategorized' }}</p>
                                    <p class="price">{{ $article->created_at->format('d M Y') }}</p>
                                </div>

                                <h3>
                                    <a href="{{ url('/ar/' . $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h3>

                                <p class="description">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($article->summary), 70) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>Tidak ada artikel yang diterbitkan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Tombol Lihat Semua --}}
        @if ($articles->count() > 0)
            <div class="text-center mt-4">
                <a href="{{ url('/ar') }}" class="btn-see-all">
                    Lihat Semua
                </a>
            </div>
        @endif
    </div>

    <style>
        .article-scroll {
            overflow-x: auto;
            padding-bottom: 10px;
        }

        /* Pastikan item tetap deret horizontal */
        .article-scroll .d-flex {
            flex-wrap: nowrap;
        }

        .article-scroll::-webkit-scrollbar {
            height: 8px;
        }

        .article-scroll::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .article-card {
            flex: 0 0 auto;
            width: 300px;
            margin-right: 15px;
            /* Supaya teks di dalam kartu boleh membungkus baris */
            white-space: normal;
        }

        @media (max-width: 768px) {
            .article-card {
                width: 260px;
            }
        }

        /* Ukuran gambar seragam */
        .article-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            /* border-radius: 10px; */
            display: block;
        }

        .btn-see-all {
            background-color: #5fcf80;
            color: white;
            padding: 10px 30px;
            border-radius: 50px;
            border: none;
            display: inline-block;
            text-decoration: none;
            transition: 0.3s ease;
            font-weight: 500;
        }

        .btn-see-all:hover {
            background-color: #4cb96c;
            color: #ffffff;
        }
    </style>
</section>
