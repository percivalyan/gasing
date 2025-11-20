<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Yayasan Gasing Papua </title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    @include('welcome.head')
</head>

<body class="index-page">

    @include('welcome.navbar')

    <main class="main">

        <!-- Hero Section -->
        @include('welcome.hero')
        <!-- /Hero Section -->

        <!-- About Section -->
        @include('welcome.contents.about')
        <!-- /About Section -->

        <!-- Counts Section -->
        @include('welcome.contents.count')
        <!-- /Counts Section -->

        <!-- Why Us Section -->
        {{-- @include('welcome.contents.whyus') --}}
        <!-- /Why Us Section -->

        <!-- Features Section -->
        {{-- @include('welcome.contents.feature') --}}
        <!-- /Features Section -->

        <!-- Courses Section -->
        @include('welcome.contents.course')
        <!-- /Courses Section -->

        <!-- Trainers Index Section -->
        {{-- @include('welcome.contents.trainer') --}}
        <!-- /Trainers Index Section -->

    </main>

    @include('welcome.footer')

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    @include('welcome.script')

</body>

</html>
