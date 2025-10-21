<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | {{ $title ?? 'AllFill Documentation' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/frontend.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/taillwindcss-typography.min.css') }}">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/frontend.js') }}"></script>
    <link rel="shortcut icon" href="{{ asset('media/favicon/' . config('setting.general.app_favicon')) }}" />

    <link href="{{ asset('assets/css/prims.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/prims-line-numbers.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/default-content-summernote.css') }}">

    <script src="{{ asset('assets/js/prism.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-jsx.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-bash.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-py.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-java.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-ts.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-tsx.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-kotline.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prims-dark.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prims-line-numbers.min.js') }}"></script>

    @if (config('setting.general.app_favicon') !== 'favicon.png')
        <!--[ Plugin ]-->
        <link rel="shortcut icon" href="{{ asset('/storage/' . config('setting.general.app_favicon')) }}"
            type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('assets/images/dummy/logo.png') }}" type="image/x-icon">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Spectral:wght@400&display=swap" rel="stylesheet">
    <style>
        main {
            font-family: 'Spectral', serif;
        }

        .hidden-scrollbar {
            overflow: auto;
            /* Atau gunakan 'scroll' sesuai kebutuhan */
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE 10+ */
        }

        .hidden-scrollbar::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }
    </style>


</head>

<body class="bg-[#FCFCFC] text-gray-900 flex flex-col min-h-screen overflow-x-hidden">
    <!-- Navigation Menu -->
    @if (in_array('nav', $include ?? []))
        @include('frontend.components.navigation')
    @endif

    <!-- Hero Section -->
    @if (in_array('hero', $include ?? []))
        @include('frontend.components.hero')
    @endif

    {{-- <!-- Tag Menu List -->
    @if (in_array('tag-menu', $include ?? []))
        @include('frontend.pages.tag-menu')
    @endif --}}

    <!-- Feed Cards -->
    <main class="container mx-auto px-4 flex-grow">
        @yield('content')
    </main>

    <footer class="mt-20 md:over">
        @include('frontend.components.footer')
    </footer>

    <script>
        var PLUGIN_NAME = 'line-numbers';
    </script>
    @include('layouts.partials._script')

</body>

</html>
