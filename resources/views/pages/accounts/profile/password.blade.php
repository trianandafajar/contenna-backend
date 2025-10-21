<x-app-layout>
    @section('title', 'Change Password')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!-- TODO : Integrasi System -->
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar pb-2" style="background-color: #f6f6f6;}">
                <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <h1
                                class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                                Change Password</h1>
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ route('account.profile.overview') }}"
                                        class="text-muted text-hover-primary">Overview</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-400 w-3px h-3px"></span>
                                </li>
                                <li class="breadcrumb-item text-muted"> Change Password</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div id="kt_app_content" class="app-content flex-column-fluid rounded-xl mt-4 pt-12">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <!--begin::Navbar-->
                    @include('pages.accounts.menu')

                    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                        <div id="kt_account_settings_profile_details" class="">
                            <!-- TODO : Membuat Alert  -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form class="form"  method="post" action="{{ route('account.profile.change-password') }}">
                                @csrf
                                @method('patch')
                                <!-- TODO password baru dibuat seperti register -->
                                <div class="card-body border-top p-9">
                                    <div class="row mb-6">
                                        <x-input-label  class="col-lg-4 col-form-label required fw-semibold fs-6" for="current_password" :value="__('Current Password')" />
                                        <div class="col-lg-8 fv-row">
                                            <x-text-input id="current_password" name="current_password" type="password" class="form-control form-control-lg form-control-solid" autocomplete="current-password" placeholder="Current Password"/>
                                            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <x-input-label class="col-lg-4 col-form-label required fw-semibold fs-6" for="password" :value="__('New Password')" />
                                        <div class="col-lg-8 fv-row">
                                            <x-text-input id="password" name="password" type="password" class="form-control form-control-lg form-control-solid" autocomplete="new-password" placeholder="New Password"/>
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <x-input-label class="col-lg-4 col-form-label required fw-semibold fs-6"  for="password_confirmation" :value="__('Confirm Password')" />
                                        <div class="col-lg-8 fv-row">
                                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="form-control form-control-lg form-control-solid" autocomplete="new-password" placeholder="Confirm Password"/>
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end py-6 px-9">
                                    <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">{{ __('Save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
               
                </div>
                <!--end::Content container-->
            </div>
        </div>
    </div>

</x-app-layout>
