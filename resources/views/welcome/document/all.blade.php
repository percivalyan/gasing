<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dokumen Publik - Yayasan Gasing Papua</title>
    <meta name="description" content="Daftar dokumen publik Yayasan Gasing Papua yang dapat diunduh oleh umum.">
    <meta name="keywords" content="dokumen, yayasan gasing, papua, publik, download">

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
                            <h1>Dokumen Publik</h1>
                            <p class="mb-0">
                                Daftar dokumen yang dapat diakses oleh masyarakat secara umum.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li class="current">Dokumen Publik</li>
                    </ol>
                </div>
            </nav>
        </div>
        <!-- End Page Title -->

        <!-- Documents List Section -->
        <section id="documents" class="courses section">
            <div class="container" data-aos="fade-up">

                <div class="row">
                    @forelse ($documents as $document)
                        <div class="col-lg-4 col-md-6 d-flex align-items-stretch mb-4">
                            <div class="course-item w-100 d-flex flex-column justify-content-between">

                                <div>
                                    <h4 class="mb-2">
                                        {{ $document->name }}
                                    </h4>

                                    <p class="small text-muted mb-2">
                                        <i class="bi bi-calendar-event"></i>
                                        {{ $document->created_at?->format('d M Y') ?? '-' }}
                                    </p>

                                    <p class="mb-0">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($document->description), 120) }}
                                    </p>
                                </div>

                                <div class="mt-3 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success text-light text-capitalize">
                                        {{ $document->visibility }}
                                    </span>

                                    @if ($document->file_path)
                                        <a href="{{ asset('storage/' . $document->file_path) }}"
                                            class="btn btn-primary btn-sm" target="_blank" rel="noopener">
                                            <i class="bi bi-file-earmark-arrow-down"></i> Download
                                        </a>
                                    @else
                                        <span class="text-muted small">
                                            <i class="bi bi-exclamation-circle"></i> File belum tersedia
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>Tidak ada dokumen publik yang tersedia saat ini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($documents instanceof \Illuminate\Pagination\AbstractPaginator)
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $documents->links() }}
                    </div>
                @endif

            </div>
        </section>
        <!-- /Documents List Section -->

    </main>

    @include('welcome.footer')

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Preloader -->
    <div id="preloader"></div>

    @include('welcome.script')

</body>

</html>
