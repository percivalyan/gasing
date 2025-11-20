@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Daftar Teacher Event</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="text" id="s" class="form-control" placeholder="Cari nama...">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="t">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($teachers as $i => $t)
                                            <tr data-n="{{ Str::lower($t->name) }}">
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $t->name }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center">Tidak ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="small text-muted">Halaman ini opsional; untuk AJAX gunakan endpoint
                                <code>picker.teacher_events.search</code>.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const input = document.getElementById('s');
            const rows = Array.from(document.querySelectorAll('#t tbody tr'));
            input?.addEventListener('input', () => {
                const q = (input.value || '').toLowerCase();
                rows.forEach(r => r.style.display = r.dataset.n.includes(q) ? '' : 'none');
            });
        </script>
    @endpush
@endsection
