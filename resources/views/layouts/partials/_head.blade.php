<base href=""/>
        <title>{{config('app.name')}}  -  @yield('title')</title>
        <meta charset="utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="" />
        <meta property="og:url" content="" />
        <meta property="og:site_name" content="" />
        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{asset('storage/media/favicon/'.config('setting.general.app_favicon'))}}">
        <link rel="icon" sizes="192x192" type="image/png" href="{{asset('storage/media/favicon/'.config('setting.general.app_favicon'))}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('storage/media/favicon/'.config('setting.general.app_favicon'))}}">
        <!-- END Icons -->
        <link rel="canonical" href="" />
        <link rel="shortcut icon" href="" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <link href="{{asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
        <!-- Summernote -->
        {{-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet"> --}}
        <!-- Custom CSS -->
        <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
        @if(config('setting.general.app_favicon') && file_exists(public_path('media/favicon/' . config('setting.general.app_favicon'))))
        <link rel="shortcut icon" href="{{ asset('media/favicon/' . config('setting.general.app_favicon')) }}" type="image/x-icon">
        @else
        <link rel="shortcut icon" href="{{ asset('assets/images/dummy/logo.png') }}" type="image/x-icon">
        @endif
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=code" />
        <link rel="stylesheet" href="{{ asset('\assets\css\summernote.min.css') }}">
         <link rel="stylesheet" href="{{ asset('assets/css/taillwindcss-typography.min.css') }}">
            <!-- CSS Prism.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/toolbar/prism-toolbar.min.css"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.css"
        rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
		<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.2.1/ckeditor5.css" crossorigin>
		<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5-premium-features/44.2.1/ckeditor5-premium-features.css" crossorigin>