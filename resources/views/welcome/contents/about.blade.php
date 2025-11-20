<section id="about" class="about section">
    <div class="container">
        <div class="row gy-4">

            {{-- <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
                <img src="{{ asset('assets/img/about.jpg') }}" class="img-fluid" alt="Tentang Kami">
            </div> --}}

            <div class="col-lg-12 order-2 order-lg-1 content" data-aos="fade-up" data-aos-delay="200">

                <div class="text-center">
                    <h3>Tentang Kami</h3>
                    <p class="fst-italic">
                        {{ $about->history ?? 'Deskripsi sejarah belum tersedia.' }}
                    </p>

                    @if (isset($about->vision))
                        <h5>Visi</h5>
                        <p>{{ $about->vision }}</p>
                    @endif

                    @if (isset($about->mission))
                        <h5>Misi</h5>
                        <ul>
                            @foreach (explode("\n", $about->mission) as $item)
                                @if (trim($item) != '')
                                    <li><i class="bi bi-check-circle"></i> <span>{{ $item }}</span></li>
                                @endif
                            @endforeach
                        </ul>
                    @endif

                    @if (!isset($about))
                        <p><em>Belum ada data tentang kami.</em></p>
                    @endif
                </div>

                {{-- <a href="{{ route('about.more') }}" class="read-more">
                    <span>Selengkapnya</span><i class="bi bi-arrow-right"></i>
                </a> --}}

            </div>

        </div>
    </div>
</section>
