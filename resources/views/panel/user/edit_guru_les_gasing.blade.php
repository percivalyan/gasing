{{-- resources/views/panel/user/edit_guru_les_gasing.blade.php --}}
@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="mb-2">Edit Guru Les Gasing</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('user.list-guru-les-gasing') }}">Guru Les
                                        Gasing</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @include('panel._message')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Form Edit</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.guru-les-gasing.update', $getRecord->id) }}">
                                @csrf
                                {{-- routes kamu memakai POST untuk update, jadi tidak perlu @method('PUT') --}}

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', $getRecord->name) }}"
                                            class="form-control @error('name') is-invalid @enderror">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ old('email', $getRecord->email) }}"
                                            class="form-control @error('email') is-invalid @enderror">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Kata Sandi (opsional)</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Biarkan kosong jika tidak diubah">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">NIK</label>
                                        <input type="text" name="nik" value="{{ old('nik', $getRecord->nik) }}"
                                            class="form-control @error('nik') is-invalid @enderror">
                                        @error('nik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                        <input type="text" name="birth_place"
                                            value="{{ old('birth_place', $getRecord->birth_place) }}"
                                            class="form-control @error('birth_place') is-invalid @enderror">
                                        @error('birth_place')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" name="birth_date"
                                            value="{{ old('birth_date', optional($getRecord->birth_date)->format('Y-m-d')) }}"
                                            class="form-control @error('birth_date') is-invalid @enderror">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                            <option value="">- Pilih -</option>
                                            <option value="M"
                                                {{ old('gender', $getRecord->gender) === 'M' ? 'selected' : '' }}>Laki-laki
                                            </option>
                                            <option value="F"
                                                {{ old('gender', $getRecord->gender) === 'F' ? 'selected' : '' }}>Perempuan
                                            </option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">NIP</label>
                                        <input type="text" name="nip" value="{{ old('nip', $getRecord->nip) }}"
                                            class="form-control @error('nip') is-invalid @enderror">
                                        @error('nip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Bidang Keahlian</label>
                                        <input type="text" name="expertise_field"
                                            value="{{ old('expertise_field', $getRecord->expertise_field) }}"
                                            class="form-control @error('expertise_field') is-invalid @enderror">
                                        @error('expertise_field')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Pendidikan Terakhir</label>
                                        <input type="text" name="last_education"
                                            value="{{ old('last_education', $getRecord->last_education) }}"
                                            class="form-control @error('last_education') is-invalid @enderror">
                                        @error('last_education')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">No. WhatsApp</label>
                                        <input type="text" name="whatsapp_number"
                                            value="{{ old('whatsapp_number', $getRecord->whatsapp_number) }}"
                                            class="form-control @error('whatsapp_number') is-invalid @enderror">
                                        @error('whatsapp_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Alamat</label>
                                        <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $getRecord->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3">
                                    Role akun ini <strong>tetap</strong> sebagai <strong>Guru Les Gasing</strong> (dikunci
                                    di server).
                                </div>

                                <div class="d-flex gap-2 mt-2">
                                    <a href="{{ route('user.list-guru-les-gasing') }}" class="btn btn-light">Kembali</a>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
