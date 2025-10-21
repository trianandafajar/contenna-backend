<div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url({{ asset('assets/media/misc/auth-bg.png') }})">
    <!--begin::Content-->
    <div class="d-flex flex-column flex-center py-15 py-lg-15 px-5 px-md-15 w-100">
        <!--begin::Logo-->
        <a href="{{route('login')}}" class="mb-0 mb-lg-12">
            @if(config('setting.general.app_logo') !== 'logo.png')
                <img alt="Logo" src="{{ asset('media/logo/'.config('setting.general.app_logo')) }}" class="h-70px" />
            @else
                <img alt="Logo" src="{{asset('assets/media/logos/logo.png') }}" class="h-70px" />
            @endif
        </a>
        <!--end::Logo-->
        <!--begin::Image-->
        <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" style="max-width: 30rem" src="{{asset('assets/media/misc/image.gif')}}" alt="" />
        <!--end::Image-->
        <!--begin::Title-->
        {{-- <h1 class="d-none d-lg-block text-black fs-2qx fw-bolder text-center mb-7">{{ config('app.name') }}</h1> --}}
        <!--end::Title-->
        <!--begin::Text-->
    {{-- <div class="d-none d-lg-block">
        <p class="text-black fw-semibold text-center">
            {{ config('setting.general.app_description') }}
        </p>
    </div> --}}
    <!--end::Content-->
</div>
</div>