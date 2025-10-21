<x-app-layout>
    @section('title', 'Feedback')
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
                            {{ __('Feedback') }}
                        </h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>


        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid" style="background-color: #f6f6f6;}">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid" style="padding-left: 0px!important; padding-right: 0px!important">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                <form action="{{ route('feedback.index') }}" method="GET">
                                    <input type="text" id="user-search" name="search" data-kt-user-table-filter="search"
                                        class="form-control form-control-solid w-250px ps-13" placeholder="search"  value="{{ request('search') }}" />
                                </form>
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--begin::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-4">
                        <!--begin::Table-->
                    <div class="table-responsive">
                        <table id="users-table" class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">No</th>
                                    <th class="min-w-125px">Name</th>
                                    <th class="min-w-125px">Email</th>
                                    <th class="min-w-125px">Type</th>
                                    <th class="min-w-125px">Date</th>
                                    <th class="text-end min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach ($feedData as $key)
                                <tr>
                                    <td>{{ $loop->iteration  }}</td>
                                    <td>{{ $key->name }}</td>
                                    <td>{{ $key->email }}</td>
                                    <td>{{ $key->type == 1 ? 'Add More Feature' : ($key->type == 2 ? 'Report Bug' : ($key->type == 3 ? 'Other' : 'Unknown')) }}</td>
                                    <td>{{ $key->created_at->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('feedback.show', ['id' => $key->id]) }}" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="ki-outline ki-eye fs-5 ms-1"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                        <!--end::Table-->
                        <div class="pagination float-end">
                            {{-- {{ $omzets->links('vendor.pagination.bootstrap-4') }} --}}
                        </div>

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

  </x-app-layout>
