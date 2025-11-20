<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>@yield('title', 'CMS') || Yayasan Gasing Papua</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- --- CSRF meta default (selalu ada) --- --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- include header partial (CSS, favicons, dll) --}}
    @include('panel.layouts.header')

    {{-- Allow child pages to push additional head content (meta/script/css) --}}
    @stack('head')
</head>
<!-- [Head] end -->

<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    @include('panel.layouts.sidebar')
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        @include('panel.layouts.navbar')
    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    @yield('content')
    <!-- [ Main Content ] end -->

    <footer class="pc-footer">
        @include('panel.layouts.footer')
    </footer>

    {{-- include base scripts (jQuery, Bootstrap, app.js, dll) --}}
    @include('panel.layouts.script')

    {{-- allow child pages to push inline scripts after base scripts --}}
    @stack('scripts')
</body>
<!-- [Body] end -->

</html>
