@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Daftar Student Event</h5>
                            <div class="d-flex gap-2">
                                <select id="lv" class="form-select form-select-sm">
                                    <option value="">Semua Level</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                </select>
                                <input type="text" id="s" class="form-control form-control-sm"
                                    placeholder="Cari nama...">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="t">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Level</th>
                                            <th>Asal Sekolah</th>
                                            <th>WA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $i => $s)
                                            <tr data-n="{{ Str::lower($s->name) }}" data-lv="{{ $s->school_level }}">
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $s->name }}</td>
                                                <td><span
                                                        class="badge bg-light text-dark">{{ $s->school_level ?? '-' }}</span>
                                                </td>
                                                <td>{{ $s->school_origin ?? '-' }}</td>
                                                <td>{{ $s->whatsapp_number ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="small text-muted">Halaman ini opsional; untuk AJAX gunakan endpoint
                                <code>picker.student_events.search</code>.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const s = document.getElementById('s');
            const lv = document.getElementById('lv');
            const rows = Array.from(document.querySelectorAll('#t tbody tr'));

            function apply() {
                const q = (s.value || '').toLowerCase();
                const level = lv.value;
                rows.forEach(r => {
                    const okName = r.dataset.n.includes(q);
                    const okLv = !level || r.dataset.lv === level;
                    r.style.display = (okName && okLv) ? '' : 'none';
                });
            }
            s?.addEventListener('input', apply);
            lv?.addEventListener('change', apply);
        </script>
    @endpush
@endsection
