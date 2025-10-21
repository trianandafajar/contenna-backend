<div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
    <div class="d-flex flex-stack flex-grow-1">
        <div class="app-header-logo d-flex align-items-center ps-lg-12" id="kt_app_header_logo">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
            <a href="{{ route('dashboard') }}" class="app-sidebar-logo">
                @if(config('setting.general.app_logo') !== 'logo.png')
                    <img alt="Logo" src="{{ asset('media/logo/'.config('setting.general.app_logo')) }}" class="h-40px theme-light-show" />
                @else
                    <img alt="Logo" src="{{asset('assets/media/logos/logo.png') }}" class="h-40px theme-light-show" />
                @endif
            </a>
        </div>

        @include('layouts.partials._navbar')
    </div>
    <!-- <div class="app-header-separator"></div> -->
</div>
