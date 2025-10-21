<x-app-layout>
    @section('title', 'Log Versions')
    @if ($errors->any())
        <div id="is_invalid__"></div>
    @endif

        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pb-6" style="background-color: #f6f6f6;}">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            {{ __('Log Versions') }}
                        </h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>

        <!--begin::Main-->
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main" style="background-color: #f6f6f6;}">
            <!--begin::Content wrapper-->
            <div class="d-flex flex-column flex-column-fluid">
                <!--begin::Content-->
                <div id="kt_app_content" class="app-content flex-column-fluid rounded">
                    <!--begin::Content container-->
                    <div id="kt_app_content_container" class="app-container container-fluid">
                        <!--begin::Timeline-->
                        <div class="card">
                            <!--end::Card head-->
                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Tab Content-->
                                <div class="tab-content">
                                    <!--begin::Tab panel-->
                                    <div id="kt_activity_today" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="kt_activity_today_tab">
                                        <!--begin::Timeline-->
                                        @foreach ($updateLogs as $updateLog)
                                            <div class="timeline">
                                                <!--begin::Timeline item-->
                                                <div class="timeline-item">
                                                    <!--begin::Timeline line-->
                                                    <div class="timeline-line w-40px"></div>
                                                    <!--end::Timeline line-->
                                                    <!--begin::Timeline icon-->
                                                    <div class="timeline-icon symbol symbol-circle symbol-40px me-4">
                                                        <div class="symbol-label bg-light">
                                                            <i class="ki-outline ki-flag fs-2 text-gray-500"></i>
                                                        </div>
                                                    </div>
                                                    <!--end::Timeline icon-->
                                                    <!--begin::Timeline content-->
                                                    <div class="timeline-content mb-10 mt-n1">
                                                        <!--begin::Timeline heading-->
                                                        <div class="pe-3 mb-5">
                                                            <!--begin::Title-->
                                                            <div class="fs-5 fw-semibold mb-2">{{ $updateLog->title }}</div>
                                                            <!--end::Title-->
                                                            <!--begin::Description-->
                                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                                <!--begin::Info-->
                                                                <div class="text-muted me-2 fs-7">Updated on {{ $updateLog->created_at->format('Y-m-d') }}</div>
                                                                <!--end::Info-->
                                                            </div>
                                                            <!--end::Description-->
                                                        </div>
                                                        <!--end::Timeline heading-->
                                                        <!--begin::Timeline details-->
                                                        <div class="overflow-auto pb-5">
                                                            <!--begin::Record-->
                                                            @foreach ($updateLog->descriptions as $description)
                                                                <div class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5 justify-content-between">
                                                                    {{ $description->description }}
                                                               
                                                                </div>
                                                            @endforeach
                                                            <!--end::Record-->
                                                        </div>
                                                        <!--end::Timeline details-->
                                                    </div>
                                                    <!--end::Timeline content-->
                                                </div>
                                                <!--end::Timeline item-->
                                            </div>
                                        @endforeach
                                        <!--end::Timeline-->
                                    </div>
                                    <!--end::Tab panel-->
                                </div>
                                <div class="pagination float-end">
                                    {{ $updateLogs->links('vendor.pagination.bootstrap-4') }}
                                </div>
                                <!--end::Tab Content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Timeline-->
                    </div>
                    <!--end::Content container-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Content wrapper-->
        </div>
        <!--end:::Main-->

</x-app-layout>
