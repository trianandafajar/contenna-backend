<x-app-layout>
    @section('title', 'Register Setting')
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
                        {{ __('Register Settings') }}
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
              <form action="{{ route('resources.setting.register.update') }}" method="POST"  enctype="multipart/form-data" id="kt_account_profile_details_form" class="form"  >
              @csrf
                  <div class="card-body py-4">
                      <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Registration Code</label>
                        <div class="col-lg-8">
                          <input type="text" name="registration_code" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Registration code" value="{{config('app.registration_code')}}" />
                          <x-input-error class="mt-2" :messages="$errors->get('registration_code')" />
                        </div>
                      </div>

                  <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
                  </div>
              </form>
            </div>
        </div>
      </div>
    </div>
</x-app-layout>
