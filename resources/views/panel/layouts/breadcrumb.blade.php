    <!-- [ breadcrumb ] start -->
    @php
        use App\Helpers\BreadcrumbHelper;
        $breadcrumbs = BreadcrumbHelper::generate();
    @endphp

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="mb-2">{{ end($breadcrumbs)['name'] ?? 'Dashboard' }}</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                        </li>
                        @foreach ($breadcrumbs as $index => $breadcrumb)
                            @if ($loop->last)
                                <li class="breadcrumb-item active">{{ $breadcrumb['name'] }}</li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ breadcrumb ] end -->
