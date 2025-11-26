@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="mb-2">Edit Profile</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('user.index') }}">User</a>
                                </li>
                                <li class="breadcrumb-item active">Edit Profile</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ alert message ] -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="feather icon-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="feather icon-alert-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- [ end alert message ] -->

            <!-- [ Form Section ] start -->
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-10">
                    <div class="card shadow-sm border-0">
                        <div class="card-header text-center bg-white">
                            <div class="position-relative d-inline-block mb-3">
                                <!-- Dummy Avatar -->
                                <img src="{{ $getRecord->avatar ? asset('storage/' . $getRecord->avatar) : url('assets/images/user/avatar-2.jpg') }}"
                                    alt="Avatar" class="rounded-circle border border-3 border-primary shadow-sm"
                                    style="width: 130px; height: 130px; object-fit: cover;">

                                <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-1"
                                    style="width: 30px; height: 30px;">
                                    <i class="feather icon-camera text-white" style="font-size: 16px;"></i>
                                </div>
                            </div>
                            <h5 class="mb-0">{{ $getRecord->name }}</h5>
                            <p class="text-muted mb-0">{{ $getRecord->email }}</p>
                        </div>

                        <div class="card-body">
                            {{-- action: update profile by id --}}
                            <form action="{{ route('user.update-profile', $getRecord->id) }}" method="POST">
                                @csrf

                                <!-- Full Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name', $getRecord->name) }}" required>
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-Mail Address</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ old('email', $getRecord->email) }}" required>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Leave blank if not changing">
                                    <small class="text-muted">(Leave blank to keep current password)</small>
                                    @error('password')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="mb-3">
                                    <label for="role" class="form-label">User Role</label>
                                    <select class="form-control" id="role" disabled>
                                        @foreach ($getRole as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $getRecord->role_id == $value->id ? 'selected' : '' }}>
                                                {{ $value->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="role_id" value="{{ $getRecord->role_id }}">
                                </div>

                                <!-- Divider -->
                                <hr class="my-4">

                                <!-- NIK -->
                                <div class="mb-3">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" name="nik" id="nik" class="form-control"
                                        value="{{ old('nik', $getRecord->nik) }}">
                                </div>

                                <!-- Birth -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="birth_place" class="form-label">Birth Place</label>
                                        <input type="text" name="birth_place" id="birth_place" class="form-control"
                                            value="{{ old('birth_place', $getRecord->birth_place) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="birth_date" class="form-label">Birth Date</label>
                                        <input type="date" name="birth_date" id="birth_date" class="form-control"
                                            value="{{ old('birth_date', $getRecord->birth_date) }}">
                                    </div>
                                </div>

                                <!-- Gender -->
                                <div class="mb-3">
                                    <label class="form-label d-block">Gender</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender_m"
                                            value="M"
                                            {{ old('gender', $getRecord->gender) == 'M' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gender_m">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender_f"
                                            value="F"
                                            {{ old('gender', $getRecord->gender) == 'F' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gender_f">Female</label>
                                    </div>
                                </div>

                                <!-- Profession -->
                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" name="nip" id="nip" class="form-control"
                                        value="{{ old('nip', $getRecord->nip) }}">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="expertise_field" class="form-label">Expertise Field</label>
                                        <input type="text" name="expertise_field" id="expertise_field"
                                            class="form-control"
                                            value="{{ old('expertise_field', $getRecord->expertise_field) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_education" class="form-label">Last Education</label>
                                        <input type="text" name="last_education" id="last_education"
                                            class="form-control"
                                            value="{{ old('last_education', $getRecord->last_education) }}">
                                    </div>
                                </div>

                                <!-- WhatsApp -->
                                <div class="mb-3">
                                    <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                                    <input type="text" name="whatsapp_number" id="whatsapp_number"
                                        class="form-control"
                                        value="{{ old('whatsapp_number', $getRecord->whatsapp_number) }}">
                                </div>

                                <!-- Address -->
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" id="address" rows="3" class="form-control">{{ old('address', $getRecord->address) }}</textarea>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('user.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather icon-save me-1"></i> Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Form Section ] end -->

        </div>
    </div>
@endsection
