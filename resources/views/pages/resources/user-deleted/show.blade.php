<x-app-layout>
  @section('title', 'Detail User Deleted')
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
                        {{ __('Detail User') }}
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

    <div id="kt_app_content" class="app-content flex-column-fluid" style="background-color: #f6f6f6;}">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid" style="padding-left: 0px!important; padding-right: 0px!important">
            <!--begin::Card-->
            <div class="card table-responsive">
                <!--begin::Card header-->

                <!--begin::Detail data-->
                <div class="card card-flush py-4 flex-row-fluid">
                  <!--begin::Card body-->
                  <div class="card-body pt-0">
                    <div class="table-responsive">
                      <!--begin::Table-->
                      <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5">
                        <tbody class="fw-semibold text-gray-600">
                          <tr>
                            <td class="text-muted">{{ __("Avatar") }}</td>
                            <td class="fw-bold text-end">
                                @if (isset($user->avatar))
                                    <div class="avatar__ rounded-circle me-3"
                                        style="width: 100px; height: 100px; overflow: hidden">
                                        <a href="{{ asset('media/avatars/' . $user->avatar) }}" target="_blank">
                                            <img src="{{ asset('media/avatars/' . $user->avatar) }}"
                                                alt="User Profile"
                                                style="width: 100%; height:100%; object-fit: cover;">
                                        </a>
                                    </div>
                                @else
                                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                        <a href="#">
                                            <div class="symbol-label fs-3 bg-danger text-light-danger">
                                                {{ substr($user->name, 0, 1) }}</div>
                                        </a>
                                    </div>
                                @endif
                            </td>
                          </tr>
                          <tr>
                            <td class="text-muted">{{ __("Full Name") }}</td>
                            <td class="fw-bold text-end">{{ $user->name }}</td>
                          </tr>
                          <tr>
                            <td class="text-muted">{{ __("Email") }}</td>
                            <td class="fw-bold text-end">{{ $user->email }}</td>
                          </tr>
                          
                          <tr>
                            <td class="text-muted">{{ __("Created By") }}</td>
                            <td class="fw-bold text-end">{{ $user->created_at->format('d M Y H:i') }}</td>
                          </tr>
                          <tr>
                            <td class="text-muted">{{ __("Updated By") }}</td>
                            <td class="fw-bold text-end">{{ $user->updated_at->format('d M Y H:i') }}</td>
                          </tr>

                        </tbody>
                      </table>
                      <!--end::Table-->
                    </div>
                  </div>
                  <!--end::Card body-->
                </div>
                <!--end::Detail data-->

            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
</x-app-layout>
