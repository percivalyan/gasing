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
                            <h5 class="mb-0">Subjects</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ route('subjects.create') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Subject
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            <form method="GET" class="row g-2 mb-3">
                                <div class="col-md-8">
                                    <input type="text" name="q" class="form-control" placeholder="Search name/description..." value="{{ request('q') }}">
                                </div>
                                <div class="col-md-4 d-grid">
                                    <button class="btn btn-outline-secondary">Search</button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($subjects as $s)
                                            <tr>
                                                <td>{{ $loop->iteration + ($subjects->currentPage()-1)*$subjects->perPage() }}</td>
                                                <td>{{ $s->name }}</td>
                                                <td>{{ $s->description ?? '-' }}</td>
                                                <td class="text-end">
                                                    @if (!empty($PermissionEdit))
                                                        <a href="{{ route('subjects.edit', $s->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                                                    @endif
                                                    @if (!empty($PermissionDelete))
                                                        <form action="{{ route('subjects.destroy', $s->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus subject ini?')">Delete</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Belum ada subject.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $subjects->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
@endsection
