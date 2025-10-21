<x-app-layout>
    @section('title', 'Detail Category')
    @if ($errors->any())
        <div id="is_invalid__"></div>
    @endif
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pb-2" style="background-color: #f6f6f6;}">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <!--begin::Toolbar wrapper-->
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        {{ __('Detail Category') }}
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid" style="background-color: #f6f6f6;}">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid" style="padding-left: 0px!important; padding-right: 0px!important">
            <!--begin::Card-->
            <div class="card table-responsive">
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Details container-->
                    <div class="container">
                        <!--begin::Details-->
                        <div class="">
                            <div class="col-lg-6">
                                <!--begin::Name-->
                                <div class="mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __("Name") }}</label>
                                    <div class="text-dark fw-bold">{{ $category->name }}</div>
                                </div>
                                <!--end::Name-->
                            </div>
                            <div class="col-lg-6">
                                <!--begin::Slug-->
                                <div class="mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __("Slug") }}</label>
                                    <div class="text-dark fw-bold">{{ $category->slug }}</div>
                                </div>
                                <!--end::Slug-->
                            </div>
                            <div class="col-lg-6">
                                <!--begin::Email-->
                                <div class="mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __("Status") }}</label>
                                    <div class="text-dark fw-bold">{{ $category->status == 1 ? 'active' : 'inactive' }}</div>
                                </div>
                                <!--end::Email-->
                            </div>
                        </div>
                        <!--end::Details-->
                    </div>
                    <!--end::Details container-->
                    </div>
                <!--end::Card body-->
                </div>
                <!--end::Detail data-->

            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</x-app-layout>
