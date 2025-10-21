@extends('layouts.home', ['include' => ['nav', 'footer']], ['title' => 'About Us'])

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <p class="fw-bold text-dark font-bold" style="font-size: 3rem; font-weight:bolder" >About Us</p>
        <p class="text-muted fs-3">Discover more about {{ config('app.name') }}</p>
    </div>

    <div class="row align-items-center">
        <!-- Image Section -->
        {{-- <div class="col-md-6 text-center mb-4 mb-md-0">
            @if (config('setting.general.app_logo') !== 'logo.png')
                <img src="{{ asset('media/logo/'.config('setting.general.app_logo')) }}" 
                     alt="Logo" class="img-fluid" style="max-height: 400px;">
            @else
                <img src="{{ asset('assets/media/logos/logo.png') }}" 
                     alt="About Us" class="img-fluid rounded shadow">
            @endif
        </div> --}}

        <!-- Text Section -->
        <div class="col-md-6 text-center text-md-start">
            <p class="text-muted fs-5">
                {{ config('setting.general.app_description') }}
            </p>
        </div>
    </div>
</div>

@endsection
