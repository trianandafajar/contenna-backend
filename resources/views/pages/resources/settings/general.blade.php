<x-app-layout>
  @section('title', 'General Settings')
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
                        {{ __('General Settings') }}
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
              <form action="{{ route('resources.setting.general.update') }}" method="POST"  enctype="multipart/form-data" id="kt_account_profile_details_form" class="form"  >
              @csrf
                  <div class="card-body py-4">
                      <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Name</label>
                        <div class="col-lg-8">
                          <input type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="name" value="{{config('app.name')}}" />
                          <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                      </div>

                      <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Url</label>
                        <div class="col-lg-8">
                          <input type="text" name="url" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="url" value="{{config('app.url')}}" />
                          <x-input-error class="mt-2" :messages="$errors->get('url')" />
                        </div>
                      </div>

                      <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Description </label>
                        <div class="col-lg-8">
                          <textarea name="description" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="description" rows="5">{{config('setting.general.app_description')}}</textarea>
                          {{-- <input type="text" name="description" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="description" value="{{config('setting.general.app_description')}}" /> --}}
                          <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                      </div>


                      <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Logo</label>
                        <div class="col-lg-8">
                            @if(config('setting.general.app_logo') !== 'logo.png')
                              <div id="logo_b_image_preview" class="d-inline-block p-3 preview">
                                  <img height="50px" src="{{asset('media/logo/'.config('setting.general.app_logo'))}}">
                              </div>
                            @else
                              <div id="logo_b_image_preview" class="d-inline-block p-3 preview">
                                  <img height="50px" src="{{asset('assets/media/logos/logo.png')}}">
                              </div>
                            @endif
                          <input type="file" name="logo" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" />
                          <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                        </div>

                      </div>

                      <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Favicon</label>
                        <div class="col-lg-8">
                            @if(config('setting.general.app_favicon') !== 'favicon.png')
                              <div id="logo_b_image_preview" class="d-inline-block p-3 preview">
                                  <img height="50px" src="{{asset('media/favicon/'.config('setting.general.app_favicon'))}}">
                              </div>
                            @else
                              <div id="logo_b_image_preview" class="d-inline-block p-3 preview">
                                  <img height="50px" src="{{asset('assets/media/favicon/favicon.png')}}">
                              </div>
                            @endif
                          <input type="file" name="favicon" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" />
                          <x-input-error class="mt-2" :messages="$errors->get('favicon')" />
                        </div>
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
