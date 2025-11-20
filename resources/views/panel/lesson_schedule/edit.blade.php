@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Edit Lesson Schedule</h5>
                            <a href="{{ route('lesson_schedule.list') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
                        </div>
                        <div class="card-body">
                            @include('panel._message')

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('lesson_schedule.update', $getRecord->id) }}" method="POST" novalidate>
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Teacher (Guru Les Gasing)</label>
                                    <select name="teacher_id" class="form-control" required>
                                        @foreach ($teachers as $t)
                                            <option value="{{ $t->id }}"
                                                {{ old('teacher_id', $getRecord->teacher_id) == $t->id ? 'selected' : '' }}>
                                                {{ $t->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">School Level</label>
                                    <select name="school_level" class="form-control" required>
                                        @foreach (['SD', 'SMP', 'SMA'] as $lv)
                                            <option value="{{ $lv }}"
                                                {{ old('school_level', $getRecord->school_level) == $lv ? 'selected' : '' }}>
                                                {{ $lv }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_level')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Subject</label>

                                    <div class="input-group mb-2">
                                        <select id="subject_id" name="subject_id" class="form-control" aria-label="Select subject">
                                            <option value="">-- Pilih Subject dari daftar --</option>
                                            @foreach ($subjects ?? [] as $s)
                                                <option value="{{ $s->id }}"
                                                    {{ old('subject_id', $getRecord->subject_id) == $s->id ? 'selected' : '' }}>
                                                    {{ $s->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="button" class="btn btn-outline-secondary" id="btnToggleCustom" aria-expanded="false" aria-controls="customSubjectWrapper">
                                            Ketik Baru
                                        </button>
                                    </div>

                                    <div id="customSubjectWrapper" style="display: none;">
                                        <div class="input-group">
                                            <input id="subject_name" type="text" name="subject_name" class="form-control"
                                                   placeholder="Ketik nama subject baru..."
                                                   value="{{ old('subject_name', optional($getRecord->subject)->name ?? $getRecord->subject_name) }}"
                                                   aria-label="Nama subject baru">
                                            <button type="button" class="btn btn-primary" id="btnCreateSubjectInline">Buat Subject</button>
                                        </div>
                                        <small class="text-muted">Atau biarkan kosong dan pilih dari daftar di atas.</small>
                                    </div>

                                    @error('subject_id') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                    @error('subject_name') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                    @error('subject') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Day of Week</label>
                                    <select name="day_of_week" class="form-control" required>
                                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $d)
                                            <option value="{{ $d }}"
                                                {{ old('day_of_week', $getRecord->day_of_week) == $d ? 'selected' : '' }}>
                                                {{ $d }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('day_of_week')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" name="start_time" class="form-control"
                                            value="{{ old('start_time', $getRecord->start_time_hm) }}" required>
                                        @error('start_time')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Time</label>
                                        <input type="time" name="end_time" class="form-control"
                                            value="{{ old('end_time', $getRecord->end_time_hm) }}" required>
                                        @error('end_time')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Room</label>
                                    <input type="text" name="room" class="form-control"
                                        value="{{ old('room', $getRecord->room) }}">
                                    @error('room')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('lesson_schedule.list') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div>
            </div>
        </div>
    </div>

    @push('head')
        {{-- fallback CSRF meta (layout should already include, tetapi aman) --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    @push('scripts')
    <script>
        (function(){
            const toggleBtn = document.getElementById('btnToggleCustom');
            const wrapper = document.getElementById('customSubjectWrapper');
            const subjectSelect = document.getElementById('subject_id');
            const createBtn = document.getElementById('btnCreateSubjectInline');
            const subjectInput = document.getElementById('subject_name');

            // Utility: show/hide custom input + set aria
            function showCustom(show = true) {
                wrapper.style.display = show ? 'block' : 'none';
                toggleBtn.textContent = show ? 'Pilih dari daftar' : 'Ketik Baru';
                toggleBtn.setAttribute('aria-expanded', show ? 'true' : 'false');
                if (!show) subjectInput.value = '';
            }

            // Check old('subject_name') or record subject_name to decide initial state
            const hadOldName = {!! json_encode(old('subject_name', optional($getRecord->subject)->name ?? $getRecord->subject_name) ? true : false) !!};
            if (hadOldName || !subjectSelect.value) {
                showCustom(true);
            } else {
                showCustom(false);
            }

            // When user changes select to empty -> show custom input automatically
            subjectSelect.addEventListener('change', function() {
                if (!subjectSelect.value) showCustom(true);
                else showCustom(false);
            });

            // Toggle button behavior
            toggleBtn.addEventListener('click', function(){
                const isHidden = wrapper.style.display === 'none' || wrapper.style.display === '';
                showCustom(isHidden);
                if (isHidden) subjectInput.focus();
            });

            // Get CSRF token from meta
            function getCsrfToken() {
                const m = document.querySelector('meta[name="csrf-token"]');
                return m ? m.getAttribute('content') : null;
            }

            // Helper to insert option after placeholder and select it
            function insertOptionAfterPlaceholder(selectEl, id, text) {
                const opt = document.createElement('option');
                opt.value = id;
                opt.text = text;
                // insert after first option (placeholder)
                if (selectEl.options.length > 0) {
                    selectEl.insertBefore(opt, selectEl.options[1] || null);
                } else {
                    selectEl.appendChild(opt);
                }
                selectEl.value = id;
            }

            // Inline create subject
            createBtn.addEventListener('click', async function(){
                const name = subjectInput.value.trim();
                if (!name) {
                    alert('Masukkan nama subject');
                    subjectInput.focus();
                    return;
                }

                const token = getCsrfToken();
                if (!token) {
                    alert('CSRF token tidak ditemukan. Pastikan layout memanggil @stack(\"head\") atau meta csrf-token ada.');
                    return;
                }

                createBtn.disabled = true;
                const originalText = createBtn.textContent;
                createBtn.textContent = 'Menyimpan...';

                try {
                    const res = await fetch("{{ route('subjects.store_inline') }}", {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ name })
                    });

                    let json = null;
                    try { json = await res.json(); } catch(e) { /* ignore */ }

                    if (!res.ok) {
                        let errMsg = 'Gagal membuat subject.';
                        if (res.status === 419) errMsg = 'Session habis (419). Silakan refresh halaman dan coba lagi.';
                        else if (res.status === 403) errMsg = (json && json.message) ? json.message : 'Tidak punya izin untuk menambah subject.';
                        else if (json && json.message) errMsg = json.message;
                        else if (json && json.errors) errMsg = Object.values(json.errors).flat().join('\n');
                        alert(errMsg);
                        return;
                    }

                    // success: add option and select it
                    const id = json.data.id;
                    const text = json.data.name;
                    insertOptionAfterPlaceholder(subjectSelect, id, text);

                    showCustom(false);

                    alert('Subject berhasil dibuat dan dipilih: ' + text);
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan saat membuat subject. Cek console untuk detail.');
                } finally {
                    createBtn.disabled = false;
                    createBtn.textContent = originalText;
                }
            });
        })();
    </script>
    @endpush
@endsection
