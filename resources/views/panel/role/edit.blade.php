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
                                <h5 class="mb-2">Edit Role</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ url('role') }}">Roles</a>
                                </li>
                                <li class="breadcrumb-item active">Edit Role</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Form Section ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Role Information</h5>
                        </div>
                        <div class="card-body">

                            <form action="{{ url('role/edit/' . $getRecord->id) }}" method="POST">
                                @csrf

                                {{-- Role Name --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Role Name</label>
                                    <input type="text" name="name" id="name" value="{{ $getRecord->name }}"
                                        class="form-control" required>
                                </div>

                                {{-- Permissions Grouped like Sidebar --}}
                                <div class="mb-3">
                                    <label class="form-label d-block mb-2">Permissions</label>

                                    @php
                                        /**
                                         * Asumsi struktur:
                                         * $getPermission = [
                                         *   ['name'=>'User','group'=>[ ['id'=>..,'name'=>..], ... ]],
                                         *   ...
                                         * ]
                                         * $getRolePermission = koleksi/array object { permission_id: int }
                                         */
                                        $sections = [
                                            'User & Permission' => ['User', 'Role'],
                                            'Media Publikasi' => ['Category', 'Article', 'Gallery'],
                                            'Sekretariat' => ['Document', 'Reference Number', 'Letter Type'],
                                            'Tentang Kami' => ['About'],
                                            'Struktur Organisasi' => ['Organization Structure', 'Organization Member'],
                                            'Event Gasing' => ['Teacher Event', 'Student Event'],
                                            // Jika tidak punya modul khusus form registrasi, biarkan kosong/abaikan:
                                            // 'Reg. Event Gasing'   => ['Teacher Registration','Student Registration'],
                                            'Les Gasing' => [
                                                'User Guru Les Gasing',
                                                'Student Course',
                                                'Lesson Schedule',
                                                'Assign Teacher',
                                                'Assessment',
                                            ],
                                            'Utilities' => ['Session Log', 'Dashboard'],
                                        ];

                                        // Map modul berdasarkan name agar mudah dipanggil
                                        $moduleMap = collect($getPermission ?? [])->keyBy('name');

                                        // Set permission id yang dimiliki role saat ini
                                        $owned = collect($getRolePermission ?? [])
                                            ->pluck('permission_id')
                                            ->flip(); // flip untuk O(1) lookup

                                        $sectionIndex = 0;
                                    @endphp

                                    @foreach ($sections as $sectionTitle => $moduleNames)
                                        @php
                                            $sectionIndex++;
                                            $modulesInSection = collect($moduleNames)
                                                ->filter(fn($m) => $moduleMap->has($m))
                                                ->values();
                                        @endphp

                                        @if ($modulesInSection->isNotEmpty())
                                            {{-- Header Section --}}
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-2">
                                                <div class="text-uppercase text-muted small fw-bold">{{ $sectionTitle }}
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="checkAllInSection('section-{{ $sectionIndex }}', true)">
                                                        Checklist Semua
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                                        onclick="checkAllInSection('section-{{ $sectionIndex }}', false)">
                                                        Uncheck Semua
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Body Section --}}
                                            <div id="section-{{ $sectionIndex }}" class="border rounded p-3 mb-3">
                                                @foreach ($modulesInSection as $modName)
                                                    @php $mod = $moduleMap->get($modName); @endphp

                                                    <div class="row align-items-start mb-3">
                                                        {{-- Nama modul --}}
                                                        <div class="col-md-3 d-flex align-items-center">
                                                            <span class="fw-semibold">{{ $mod['name'] }}</span>
                                                        </div>

                                                        {{-- Daftar permission per modul --}}
                                                        <div class="col-md-9">
                                                            <div class="row g-2">
                                                                @foreach ($mod['group'] as $group)
                                                                    @php
                                                                        $isChecked = $owned->has($group['id']);
                                                                    @endphp
                                                                    <div class="col-12 col-sm-6 col-md-4">
                                                                        <label class="d-flex gap-2 align-items-start">
                                                                            <input type="checkbox"
                                                                                class="form-check-input mt-1"
                                                                                name="permission_id[]"
                                                                                value="{{ $group['id'] }}"
                                                                                {{ $isChecked ? 'checked' : '' }}>
                                                                            <span>{{ $group['name'] }}</span>
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if (!$loop->last)
                                                        <hr class="my-2">
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="text-end">
                                    <a href="{{ url('role') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
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

@push('scripts')
    <script>
        function checkAllInSection(sectionId, checked) {
            const wrap = document.getElementById(sectionId);
            if (!wrap) return;
            wrap.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = checked);
        }
    </script>
@endpush
