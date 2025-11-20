 @extends('panel.layouts.app')

 @section('content')
     <div class="pc-container">
         <div class="pc-content">
             @include('panel.layouts.breadcrumb')

             <div class="row">
                 <div class="col-sm-12">
                     <div class="card shadow-sm border-0">
                         <div class="card-header">
                             <h5 class="mb-0">Add Jenis Surat</h5>
                         </div>
                         <div class="card-body">
                             <form action="{{ url('lettertype/insert') }}" method="POST">
                                 @csrf
                                 <div class="mb-3">
                                     <label class="form-label">Subject</label>
                                     <input type="text" name="subject" class="form-control" value="{{ old('subject') }}"
                                         required>
                                 </div>

                                 <div class="mb-3">
                                     <label class="form-label">Kode</label>
                                     <input type="text" name="code" class="form-control" value="{{ old('code') }}"
                                         required>
                                 </div>

                                 <div class="text-end">
                                     <a href="{{ url('lettertype') }}" class="btn btn-secondary me-2">Cancel</a>
                                     <button type="submit" class="btn btn-primary">Submit</button>
                                 </div>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 @endsection
