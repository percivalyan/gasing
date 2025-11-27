<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Register Kepala Sekolah | Permission</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ url('') }}/images/favicon.svg" type="image/x-icon">
    <!-- [Google Font] Family -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <!-- [Tabler Icons] -->
    <link rel="stylesheet" href="{{ url('') }}/assets/fonts/tabler-icons.min.css">
    <!-- [Feather Icons] -->
    <link rel="stylesheet" href="{{ url('') }}/assets/fonts/feather.css">
    <!-- [Font Awesome Icons] -->
    <link rel="stylesheet" href="{{ url('') }}/assets/fonts/fontawesome.css">
    <!-- [Material Icons] -->
    <link rel="stylesheet" href="{{ url('') }}/assets/fonts/material.css">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ url('') }}/assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="{{ url('') }}/assets/css/style-preset.css">
</head>
<!-- [Head] end -->

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card my-4">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('assets/logo_gasing.jpg') }}" alt="Logo Yayasan Gasing"
                                    class="img-fluid mb-2" style="max-width: 120px; height: auto;">
                            </a>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <h3 class="mb-0"><b>Register Kepala Sekolah</b></h3>
                        </div>
                        <p class="text-muted mb-4">
                            Form pendaftaran khusus akun Kepala Sekolah.
                        </p>

                        @include('auth._message')

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register.headmaster.process') }}" method="POST">
                            @csrf

                            {{-- Honeypot (jebakan bot) --}}
                            <div style="display:none;">
                                <label>Website</label>
                                <input type="text" name="website" autocomplete="off">
                            </div>

                            {{-- Time-based check --}}
                            <input type="hidden" name="form_time"
                                value="{{ session('headmaster_captcha_generated_at') }}">

                            {{-- Nama --}}
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap"
                                    value="{{ old('name') }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="form-group mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email"
                                    value="{{ old('email') }}" required>
                            </div>

                            {{-- Password --}}
                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    required>
                            </div>

                            {{-- NIK --}}
                            <div class="form-group mb-3">
                                <label class="form-label">NIK</label>
                                <input type="text" name="nik" class="form-control" placeholder="NIK (opsional)"
                                    value="{{ old('nik') }}">
                            </div>

                            {{-- Tempat & Tanggal Lahir --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" name="birth_place" class="form-control"
                                            placeholder="Tempat Lahir" value="{{ old('birth_place') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="birth_date" class="form-control"
                                            value="{{ old('birth_date') }}" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="form-group mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                            </div>

                            <hr class="my-3">

                            {{-- NIP --}}
                            <div class="form-group mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control"
                                    placeholder="NIP (opsional)" value="{{ old('nip') }}">
                            </div>

                            {{-- Bidang Keahlian & Pendidikan Terakhir --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Bidang Keahlian</label>
                                        <input type="text" name="expertise_field" class="form-control"
                                            placeholder="Bidang Keahlian" value="{{ old('expertise_field') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Pendidikan Terakhir</label>
                                        <input type="text" name="last_education" class="form-control"
                                            placeholder="Pendidikan Terakhir" value="{{ old('last_education') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- No. WhatsApp --}}
                            <div class="form-group mb-3">
                                <label class="form-label">Nomor WhatsApp</label>
                                <input type="text" name="whatsapp_number" class="form-control"
                                    placeholder="Contoh: 62812xxxxxxx" value="{{ old('whatsapp_number') }}">
                            </div>

                            {{-- Alamat --}}
                            <div class="form-group mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                            </div>

                            {{-- SECURITY CHECK / CAPTCHA --}}
                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    {{-- <label class="form-label mb-0">Security Check</label>
                                    <span class="badge rounded-pill bg-light border small text-muted px-3 py-1">
                                        <i class="ti ti-shield-lock me-1"></i> Math Captcha
                                    </span> --}}
                                </div>

                                <div
                                    class="border rounded-3 px-3 py-2 bg-light d-flex flex-column flex-sm-row
                                            align-items-sm-center justify-content-between mb-2">
                                    <div class="mb-1 mb-sm-0">
                                        {{-- <span class="text-muted small d-block">Jawab soal berikut</span> --}}
                                        <span class="fw-semibold fs-5">
                                            {{ $a ?? '?' }} {{ $operator ?? '+' }} {{ $b ?? '?' }} = ?
                                        </span>
                                    </div>
                                    {{-- <span class="small text-muted">
                                        Verifikasi sederhana untuk mencegah pendaftaran otomatis.
                                    </span> --}}
                                </div>

                                <input type="number" name="captcha_answer"
                                    class="form-control @error('captcha_answer') is-invalid @enderror" placeholder=""
                                    required>

                                @error('captcha_answer')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @else
                                    <small class="text-muted">
                                        Pastikan Anda mengisi jawaban dengan angka yang benar.
                                    </small>
                                @enderror
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <a href="{{ route('login') }}" class="text-secondary f-w-400">
                                    Sudah punya akun? Login
                                </a>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Daftar Kepala Sekolah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="{{ url('') }}/assets/js/plugins/popper.min.js"></script>
    <script src="{{ url('') }}/assets/js/plugins/simplebar.min.js"></script>
    <script src="{{ url('') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="{{ url('') }}/assets/js/fonts/custom-font.js"></script>
    <script src="{{ url('') }}/assets/js/pcoded.js"></script>
    <script src="{{ url('') }}/assets/js/plugins/feather.min.js"></script>
    <script>
        layout_change('light');
        change_box_container('false');
        layout_rtl_change('false');
        preset_change("preset-1");
        font_change("Public-Sans");
    </script>

    {{-- SweetAlert Notifikasi --}}
    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $errors->first() }}'
            })
        </script>
    @endif

</body>

</html>
