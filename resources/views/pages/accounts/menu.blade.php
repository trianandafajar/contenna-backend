<div class="card mb-5 mb-xl-10">
    <div class="card-body pt-9 pb-0">
        <!--begin::Details-->
        <div class="d-flex flex-wrap flex-sm-nowrap">
            <!--begin: Pic-->
            <div class="me-7 mb-4">
                <div class="symbol  symbol-100px symbol-lg-160px symbol-fixed position-relative">

                    @if ($user->avatar)
                        <img src="{{ asset('media/avatars/' . $user->avatar) }}" alt="image" class="object-cover" />
                    @else
                        <div class="symbol  symbol-50px overflow-hidden me-3">
                            <a href="#">
                                <div class="symbol-label fs-3 bg-danger text-light-danger">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <!--end::Pic-->
            <!--begin::Info-->
            <div class="flex-grow-1">
                <!--begin::Title-->
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <!--begin::User-->
                    <div class="d-flex flex-column">
                        <!--begin::Name-->
                        <div class="d-flex align-items-center mb-2">
                            <a href="#"
                                class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $user->name }}</a>
                            <a href="#">
                                <i class="ki-outline ki-verify fs-1 text-primary"></i>
                            </a>
                        </div>
                        <!--end::Name-->
                        <!--begin::Info-->
                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                            <a href="#"
                                class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                <i class="ki-outline ki-profile-circle fs-4 me-1"></i>
                                {{ Auth::user()->getRoleNames()->first() }}</a>
                            <a href="mailto:{{ $user->email }}" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                <i class="ki-outline ki-sms fs-4 me-1"></i>{{ $user->email }}</a>
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::User-->
                    <!--begin::Actions-->

                    <!--end::Actions-->
                </div>
                <!--end::Title-->
                <!--begin::Stats-->
                <div class="d-flex flex-wrap flex-stack">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-column flex-grow-1">
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap">
                            <!--begin::Stat-->
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">
                                        {{ $countBlog }}
                                    </div>
                                </div>
                                <!--end::Number-->
                                <!--begin::Label-->
                                <div class="fw-semibold fs-6 text-gray-400">Total Post Blog</div>
                                <!--end::Label-->
                            </div>
                            <!--end::Stat-->
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Progress-->
                    <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
                        @php
                            $user = Auth::user(); // get the authenticated user

                            $totalFields = 3; // total number of fields in the users table
                            $filledFields = 0; // initialize the count of filled fields

                            // check each field if it's not null
                            if ($user->name) {
                                $filledFields++;
                            }
                            if ($user->email) {
                                $filledFields++;
                            }
                            if ($user->avatar) {
                                $filledFields++;
                            }
                            // calculate the percentage
                            $percentage = round(($filledFields / $totalFields) * 100, 0);
                        @endphp
                        <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                            <span class="fw-semibold fs-6 text-gray-400">Profile Completeness</span>
                            <span class="fw-bold fs-6">{{ $percentage }} %</span>
                        </div>
                        <div class="h-5px mx-3 w-100 bg-light mb-3">
                            <div class="bg-success rounded h-5px" role="progressbar"
                                style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <!--end::Progress-->
                </div>
                <!--end::Stats-->
            </div>
            <!--end::Info-->
        </div>
        <!--end::Details-->
        <!--begin::Navs-->
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold d-flex flex-wrap">
            <!--begin::Nav item-->
            <li class="nav-item mt-2 me-3">
                <a class="nav-link text-active-primary py-5 {{ request()->is(['page/account/overview']) ? 'active' : '' }}"
                    href="{{ route('account.profile.overview') }}">Overview</a>
            </li>
            <!--end::Nav item-->
            <li class="nav-item mt-2 me-3">
                <a class="nav-link text-active-primary py-5 {{ request()->is(['page/account/profile']) ? 'active' : '' }}"
                    href="{{ route('account.profile.edit') }}">Account Settings</a>
            </li>
            <!--end::Nav item-->
            <li class="nav-item mt-2 me-3">
                <a class="nav-link text-active-primary py-5 {{ request()->is(['page/account/change-password']) ? 'active' : '' }}"
                    href="{{ route('account.profile.password') }}">Change Password</a>
            </li>
            <!--end::Nav item-->
        </ul>        
        <!--begin::Navs-->
    </div>
</div>