@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')
            <div class="row">
                <div class="col-md-8 mx-auto">
                    @include('panel._message')

                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Tambah Nomor Surat Baru</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('referencenumber.insert') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="letter_type_id" class="form-label">Jenis Surat</label>
                                    <select name="letter_type_id" id="letter_type_id" class="form-select" required>
                                        <option value="">-- Pilih Jenis Surat --</option>
                                        @foreach ($letterTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->subject }} ({{ $type->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check"></i> Buat Nomor
                                    </button>
                                    <a href="{{ route('referencenumber.list') }}" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
