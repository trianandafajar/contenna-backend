<div class="app-header-primary" data-kt-sticky="true" data-kt-sticky-name="app-header-primary-sticky"
    data-kt-sticky-offset="{default: 'false', lg: '300px'}">
    <!--begin::Header primary container-->
    <div class="app-container container-xxl d-flex align-items-stretch justify-content-between">
        <!--begin::Logo and search-->
        <div class="d-flex flex-grow-1 flex-lg-grow-0">
            <!--begin::Logo wrapper-->
            <div class="d-flex align-items-center me-7" id="kt_app_header_logo_wrapper">
                <!--begin::Header toggle-->
                <button
                    class="d-lg-none btn btn-icon btn-flex btn-color-gray-600 btn-active-color-primary w-35px h-35px ms-n2 me-2"
                    id="kt_app_header_menu_toggle">
                    <i class="ki-outline ki-abstract-14 fs-2"></i>
                </button>
                <!--end::Header toggle-->
                <!--begin::Logo-->
                <a href="{{ url('/') }}" class="d-flex align-items-center me-lg-20 me-5">
                    @if (config('setting.general.app_logo') !== 'logo.png')
                        <img alt="Logo Docuverse" src="{{ asset('media/logo/' . config('setting.general.app_logo')) }}"
                            class="h-40px d-none d-md-inline" />
                    @else
                        <img alt="Logo Docuverse" src="{{ asset('assets/media/logos/logo.png') }}"
                            class="h-40px d-none d-md-inline" />
                    @endif
                </a>
                <!--end::Logo-->
            </div>
            <!--end::Logo wrapper-->
            <!--begin::Menu wrapper-->
            <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
                data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
                data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
                data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
                data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                <!--begin::Menu-->
                <div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
                    id="kt_app_header_menu" data-kt-menu="true">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <a class="menu-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                            <span class="menu-title">Home</span>
                        </a>
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <a class="menu-link {{ request()->is('page/about') ? 'active' : '' }}"
                            href="{{ route('about') }}">
                            <span class="menu-title">About</span>
                        </a>
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Menu wrapper-->
        </div>
        <!--end::Logo and search-->
        <!--begin::Navbar-->
        <div class="app-navbar flex-shrink-0">
            <!--begin::User menu-->
            <div class="app-navbar-item ms-3 ms-lg-9" id="kt_header_user_menu_toggle">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary fs-5" style="width: 8rem">
                        Login
                    </a>
                @endguest

                @auth
                    <!--begin::Menu wrapper-->
                    <div class="d-flex align-items-center" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <!--begin::User-->
                        <div class="cursor-pointer symbol symbol symbol-circle symbol-35px symbol-md-40px">
                            @if (Auth::user()->avatar)
                                <img class="" src="{{ URL::asset('media/avatars/' . Auth::user()->avatar) }}"
                                    alt="user" style="object-fit: cover"/>
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-danger text-white fw-bold text-xl rounded-circle"
                                    style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div
                                class="position-absolute translate-middle bottom-0 mb-1 start-100 ms-n1 bg-success rounded-circle h-8px w-8px">
                            </div>
                        </div>
                        <!--end::User-->
                    </div>
                    <!--begin::User account menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                        data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <!--begin::Avatar-->
                                @if (!empty(Auth::user()->avatar))
                                <div class="symbol symbol-50px me-5">
                                    <img alt="Avatar" src="{{ filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('media/avatars/' . Auth::user()->avatar) }}" style="object-fit: cover"/>
                                </div>
                            @else
                                <div class="symbol symbol-50px me-5 bg-danger d-flex align-items-center justify-content-center text-white fw-bold " style="width: 50px; height: 50px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            
                                <!--end::Avatar-->
                                <!--begin::Username-->
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name }}
                                        <span
                                            class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">{{ Auth::user()->getRoleNames()->first() }}</span>
                                    </div>
                                    <a href="#"
                                        class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
                                </div>
                                <!--end::Username-->
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="{{ route('account.profile.overview') }}" class="menu-link px-5">My Profile</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->
                        <!--begin::Menu item-->
                        {{-- <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                <a href="#" class="menu-link px-5">
                    <span class="menu-title position-relative">Mode
                        <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                            <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                            <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                        </span></span>
                </a>
                <!--begin::Menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                    data-kt-menu="true" data-kt-element="theme-mode-menu">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-night-day fs-2"></i>
                            </span>
                            <span class="menu-title">Light</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-moon fs-2"></i>
                            </span>
                            <span class="menu-title">Dark</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-screen fs-2"></i>
                            </span>
                            <span class="menu-title">System</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::Menu-->
            </div> --}}
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        
                        <div class="menu-item px-5 my-1">
                            <a href="{{ route('dashboard') }}" class="menu-link px-5">Dashboard</a>
                        </div>

                        <div class="menu-item px-5 my-1">
                            <a href="{{ route('account.profile.edit') }}" class="menu-link px-5">Account Settings</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="separator my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <div class="menu-item px-5">
                                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="menu-link px-5">Sign Out</a>
                            </div>
                        </form>
                        <!--end::Menu item-->
                    </div>

                @endauth
                <!--end::User account menu-->
                <!--end::Menu wrapper-->
            </div>
            <!--end::User menu-->
            <!--begin::Header menu toggle-->
            <div class="app-navbar-item d-lg-none ms-2 me-n3" title="Show header menu">
                <div class="btn btn-icon btn-color-gray-500 btn-active-color-primary w-35px h-35px"
                    id="kt_app_header_menu_toggle">
                    <i class="ki-outline ki-text-align-left fs-1"></i>
                </div>
            </div>
            <!--end::Header menu toggle-->
        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header primary container-->
</div>
