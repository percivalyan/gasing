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
                        <h5 class="mb-0">Event Batch</h5>
                        @if (!empty($PermissionAdd))
                            <a href="{{ route('event_batch.add') }}" class="btn btn-sm btn-primary">
                                <i class="feather icon-plus-circle me-1"></i> Add Batch
                            </a>
                        @endif
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Tahun</th>
                                        <th>Tahap</th>
                                        <th>Nama Lengkap</th>
                                        @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                            <th class="text-end">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($getRecord as $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->event_year }}</td>
                                            <td>{{ $value->event_phase }}</td>
                                            <td>{{ $value->full_batch_name }}</td>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <td class="text-end">
                                                    @if (!empty($PermissionEdit))
                                                        <a href="{{ route('event_batch.edit', $value->id) }}"
                                                           class="btn btn-sm btn-warning me-1">Edit</a>
                                                    @endif
                                                    @if (!empty($PermissionDelete))
                                                        <form action="{{ route('event_batch.delete', $value->id) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Yakin ingin menghapus batch ini?')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada data Event Batch.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div> {{-- card-body --}}
                </div> {{-- card --}}
            </div>
        </div>
    </div>
</div>
@endsection
