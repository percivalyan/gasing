@extends('panel.layouts.app')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        @include('panel.layouts.breadcrumb')
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="mb-0">Teacher Event Registration</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('teacher_event.registration') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @include('panel.teacher_event._form', ['action' => 'Register'])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
