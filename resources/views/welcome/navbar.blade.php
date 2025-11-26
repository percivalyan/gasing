<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
            <img src="{{ asset('landingpage/assets/img/logo_gasing.jpg') }}" alt="Logo Gasing" class="me-2"
                style="max-height: 60px; width: auto;">
        </a>

        {{-- Navbar --}}
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>

                {{-- Artikel --}}
                <li><a href="{{ url('/ar') }}" class="{{ request()->is('ar*') ? 'active' : '' }}">Artikel</a></li>

                {{-- Dokumen Publik --}}
                <li><a href="{{ route('public.documents') }}"
                        class="{{ request()->is('doc*') ? 'active' : '' }}">Dokumen</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        {{-- Login / Dashboard + Pendaftaran Kepala Sekolah --}}
        @auth
            <a class="btn-getstarted" href="{{ url('/dashboard') }}">Dashboard</a>
        @else
            {{-- Tombol Login biasa --}}
            {{-- <a class="btn-getstarted me-2" href="{{ route('login') }}">Get Started</a> --}}

            {{-- Tombol Registrasi Kepala Sekolah --}}
            <a class="btn-getstarted" href="{{ route('register.headmaster') }}">
                Pendaftaran Kepala Sekolah
            </a>
        @endauth

    </div>
</header>
