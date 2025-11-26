<footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="{{ url('/') }}" class="logo d-flex align-items-center">
                    <span class="sitename">Gasing Papua</span>
                </a>
                <div class="footer-contact pt-3">
                    <p>{{ $footer->address_street ?? 'Alamat belum diatur' }}</p>
                    <p>{{ $footer->address_post_code ?? '' }}</p>
                    <p class="mt-3">
                        <strong>Phone:</strong>
                        <span>{{ $footer->phone ?? '-' }}</span>
                    </p>
                    <p>
                        <strong>Email:</strong>
                        <span>{{ $footer->email ?? '-' }}</span>
                    </p>
                </div>
                <div class="social-links d-flex mt-4">
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            {{-- Useful Links --}}
            <div class="col-lg-4 col-md-3 footer-links">
                <h4>Useful Links</h4>
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/') }}#about">Tentang Kami</a></li>
                    <li><a href="{{ url('/') }}#courses">Program</a></li>
                    <li><a href="{{ url('/') }}#contact">Kontak</a></li>
                </ul>
            </div>

            {{-- Program & Layanan --}}
            <div class="col-lg-4 col-md-3 footer-links">
                <h4>Program & Layanan</h4>
                <ul>
                    <li><a href="{{ url('/') }}#courses">Kelas Les Gasing</a></li>
                    <li><a href="{{ url('/') }}#trainers">Pelatih & Mentor</a></li>
                    <li><a href="{{ url('/ar') }}">Artikel & Publikasi</a></li>
                    <li><a href="{{ route('public.documents') }}">Dokumen Publik</a></li>
                    <li><a href="{{ url('/') }}#events">Event Pendidikan</a></li>
                </ul>
            </div>

        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span>
            <strong class="px-1 sitename">Gasing Papua</strong>
            <span>All Rights Reserved</span>
        </p>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            Distributed by <a href="https://themewagon.com">ThemeWagon</a>
        </div>
    </div>

</footer>
