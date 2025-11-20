@extends('panel.layouts.app')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        @include('panel.layouts.breadcrumb')

        <div class="row">
            <div class="col-sm-12">
                @include('panel._message')

                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Absensi Guru Les Gasing</h5>
                        <div>
                            @if(Auth::user() && Auth::user()->role && strtolower(Auth::user()->role->name) === 'guru les gasing')
                                <button id="btn-checkin" class="btn btn-primary btn-sm">Check-in (Auto)</button>
                                <button id="btn-checkout" class="btn btn-outline-secondary btn-sm">Check-out</button>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="GET" action="{{ route('attendance.index') }}" class="mb-3">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Guru</label>
                                    <select name="teacher_id" class="form-select">
                                        <option value="">-- Semua --</option>
                                        @foreach($teachers as $t)
                                            <option value="{{ $t->id }}" {{ (request('teacher_id') == $t->id) ? 'selected' : '' }}>{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Dari tanggal</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sampai tanggal</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100">Filter</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Guru</th>
                                        <th>Checkin</th>
                                        <th>Checkout</th>
                                        <th>Status</th>
                                        <th>Metode</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $rec)
                                        <tr>
                                            <td>{{ $rec->attendance_date }}</td>
                                            <td>{{ optional($rec->teacher)->name }}</td>
                                            <td>{{ $rec->checkin_at ? $rec->checkin_at : '-' }}</td>
                                            <td>{{ $rec->checkout_at ? $rec->checkout_at : '-' }}</td>
                                            <td>{{ ucfirst($rec->status) }}</td>
                                            <td>{{ $rec->method }}</td>
                                            <td style="max-width:240px">{{ 
                                                Str::limit($rec->note ?? '-', 80) }}</td>
                                            <td>
                                                @if(Auth::user() && Auth::user()->role && strtolower(Auth::user()->role->name) === 'administrator')
                                                    <a href="{{ route('attendance.edit', $rec->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                                    <form action="{{ route('attendance.destroy', $rec->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus absensi ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center">Tidak ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">{{ $records->links() }}</div>
                    </div>
                </div>

                {{-- Modal / form manual untuk admin --}}
                @if(Auth::user() && Auth::user()->role && strtolower(Auth::user()->role->name) === 'administrator')
                <div class="mt-3">
                    <h6>Kelola Manual Absensi</h6>
                    <form action="{{ route('attendance.store_manual') }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-3">
                            <select name="teacher_id" class="form-select" required>
                                <option value="">Pilih Guru</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="attendance_date" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <input type="datetime-local" name="checkin_at" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <input type="datetime-local" name="checkout_at" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="present">Present</option>
                                <option value="late">Late</option>
                                <option value="permission">Permission</option>
                                <option value="absent">Absent</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-success w-100">Simpan</button>
                        </div>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');

        const btnCheckin = document.getElementById('btn-checkin');
        if (btnCheckin) btnCheckin.addEventListener('click', async function(){
            btnCheckin.disabled = true;
            try{
                const res = await fetch('{{ route('attendance.auto_checkin') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify({ method: 'auto' })
                });
                const data = await res.json();
                if (data.success) location.reload(); else alert('Gagal checkin');
            }catch(e){ alert('Error: ' + e.message); }
            btnCheckin.disabled = false;
        });

        const btnCheckout = document.getElementById('btn-checkout');
        if (btnCheckout) btnCheckout.addEventListener('click', async function(){
            btnCheckout.disabled = true;
            try{
                const res = await fetch('{{ route('attendance.checkout') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify({})
                });
                const data = await res.json();
                if (data.success) location.reload(); else alert('Gagal checkout');
            }catch(e){ alert('Error: ' + e.message); }
            btnCheckout.disabled = false;
        });
    });
</script>
@endpush

