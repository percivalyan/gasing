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
                            <h5 class="mb-0">Session Logs</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>IP Address</th>
                                            <th>Action</th>
                                            <th>Description</th>
                                            <th>User Agent</th>
                                            <th>Last Activity</th>
                                            @if (Auth::user()->role === 'Administrator')
                                                <th class="text-end">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($sessions as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->user->name ?? '-' }}</td>
                                                <td>{{ $value->ip_address ?? '-' }}</td>
                                                <td>{{ $value->action ?? '-' }}</td>
                                                <td>{{ $value->description ?? '-' }}</td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ Str::limit($value->user_agent, 50) }}
                                                    </small>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromTimestamp($value->last_activity)->diffForHumans() }}
                                                </td>
                                                @if (Auth::user()->role === 'admin')
                                                    <td class="text-end">
                                                        <form action="{{ url('session-logs/delete/' . $value->id) }}" method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this session log?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="feather icon-trash-2 me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No session logs found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $sessions->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
