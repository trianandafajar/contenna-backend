<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals." />
    <meta name="keywords"
        content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dark mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <title>{{ config('app.name') }} | {{ $title ?? 'AllFill Documentation' }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('media/favicon/' . config('setting.general.app_favicon')) }}" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Spectral:wght@400&display=swap" rel="stylesheet">


    <!-- Stylesheets -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/global/home-plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/home-style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!-- CSS Prism.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/toolbar/prism-toolbar.min.css"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.css"
        rel="stylesheet">
    {{-- <link href="{{ asset('assets/css/prims.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/prims-line-numbers.min.css') }}" rel="stylesheet" /> --}}

    <!-- Scripts -->
    {{-- <script type="text/javascript" src="{{ asset('assets/js/prism.js') }}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JS Prism.js -->
    <script type="text/javascript" src="{{ asset('assets/js/prism.js') }}"></script>
    </script>
    {{-- <script src="{{ asset('assets/js/prism.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-jsx.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-bash.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-py.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-java.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-ts.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-tsx.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prism-kotline.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prims-dark.min.js') }}"></script>
    <script src="{{ asset('assets/js/language/prims-line-numbers.min.js') }}"></script> --}}
    <script src="{{ asset('assets/js/frontend.js') }}"></script>
    </script>
    @if (config('setting.general.app_favicon') !== 'favicon.png')
        <!--[ Plugin ]-->
        <link rel="shortcut icon" href="{{ asset('/storage/' . config('setting.general.app_favicon')) }}"
            type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('assets/images/dummy/logo.png') }}" type="image/x-icon">
    @endif
    <style>
        .badge {
            font-weight: 525 !important;
            font-size: 0.775rem !important;
        }

        .border-hover:hover {
            border-color: black !important;

        }

        .hover-color:hover {
            color: #1E90FF !important;
        }

        body {
            font-family: 'poppins', sans-serif
        }

        .text-docuverse {
            color: #FFA317;
        }

        .copy-btn {
            display: none !important;
        }

        h5.fw-bold.fs-3 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Maksimal 2 baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="home-page" data-kt-app-header-stacked="true" data-kt-app-header-primary-enabled="true" class="app-default">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            <div id="kt_app_header" class="app-header">
                <!--begin::Header primary-->
                @include('frontend.components.navigation')
                <!--end::Header primary-->
                <!--begin::Header secondary-->
                <!--end::Header secondary-->
            </div>
            <div class="">
                <!--begin::Header secondary container-->
                <div class="">
                    <!-- Hero Section -->
                    @if (in_array('hero', $include ?? []))
                        @include('frontend.components.hero')
                    @endif
                </div>
                <!--end::Header secondary container-->
            </div>
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <!--begin::Wrapper container-->
                <div class="app-container container-xxl d-flex flex-row flex-column-fluid">
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            @yield('content')
                        </div>
                        <!--end::Content wrapper-->

                        <!--end::Content wrapper-->
                        <!--begin::Footer-->
                        @include('frontend.components.footer')
                        <!--end::Footer-->
                    </div>
                    <!--end:::Main-->
                </div>
                <!--end::Wrapper container-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->
    <!--end::Drawers-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-outline ki-arrow-up"></i>
    </div>
    <!--end::Scrolltop-->
    <!--begin::Modals-->


    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('assets/js/widgets.bundle.js ') }}"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
