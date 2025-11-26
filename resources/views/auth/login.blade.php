<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login | Permission</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" href="{{ url('') }}/images/favicon.svg" type="image/x-icon">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ url('') }}/assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ url('') }}/assets/fonts/feather.css">
    <link rel="stylesheet" href="{{ url('') }}/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="{{ url('') }}/assets/fonts/material.css">
    <link rel="stylesheet" href="{{ url('') }}/assets/css/style.css">
    <link rel="stylesheet" href="{{ url('') }}/assets/css/style-preset.css">
</head>

<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card my-4">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('assets/logo_gasing.jpg') }}" alt="Logo"
                                    class="img-fluid mb-2" style="max-width: 120px;">
                            </a>
                        </div>

                        <h3 class="mb-3 text-center"><b>Login</b></h3>

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

                        <form action="{{ route('login.process') }}" method="POST" class="mt-3">
                            @csrf

                            {{-- Honeypot (jebakan bot) --}}
                            <div style="display:none;">
                                <label>Website</label>
                                <input type="text" name="website" autocomplete="off">
                            </div>

                            {{-- Time-based validation --}}
                            <input type="hidden" name="form_time"
                                value="{{ session('login_captcha_generated_at') }}">

                            {{-- EMAIL --}}
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- PASSWORD --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label mb-0">Password</label>
                                    <a href="#" class="text-secondary small">Forgot Password?</a>
                                </div>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- CAPTCHA --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    {{-- <label class="form-label mb-0">Security Check</label>
                                    <span class="badge bg-light border small text-muted">
                                        <i class="ti ti-shield-lock me-1"></i> Advanced Captcha
                                    </span> --}}
                                </div>

                                <div class="border rounded-3 px-3 py-2 bg-light mb-2">
                                    <span class="small text-muted">Jawab soal berikut:</span>
                                    <div class="fw-semibold fs-5">
                                        {{ $a }} {{ $operator }} {{ $b }} = ?
                                    </div>
                                </div>

                                <input type="number" name="captcha_answer"
                                    class="form-control @error('captcha_answer') is-invalid @enderror"
                                    placeholder="Masukkan hasil operasi" required>

                                @error('captcha_answer')
                                    <small class="text-danger">{{ $message }}</small>
                                @else
                                    <small class="text-muted">Pastikan jawaban benar.</small>
                                @enderror
                            </div>

                            {{-- REMEMBER ME --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" name="remember"
                                        class="form-check-input input-primary"
                                        id="rememberCheck" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="rememberCheck" class="form-check-label text-muted">
                                        Keep me signed in
                                    </label>
                                </div>
                            </div>

                            {{-- SUBMIT --}}
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-login me-2"></i> Login
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JS --}}
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
</body>

</html>
