@extends('panel.layouts.app')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        @include('panel.layouts.breadcrumb')
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Student Event</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student_event.update', $getRecord->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @include('panel.student_event._form', ['action' => 'Edit', 'getRecord' => $getRecord])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
