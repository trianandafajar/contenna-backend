<x-app-layout>
    @section('title', 'Edit Config')
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
                        {{ __('Edit Config') }}
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
                <!--begin::Card header-->
            <form  class="form px-5" action="{{ route('resources.setting.config.update', ["config" => $config]) }}" method="POST">
                @csrf
                @method("patch")
                  <div class="card-body py-4">
                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Key</label>
                            <input type="text" name="key" id="key"
                                class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("key") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="Config Key"
                                value="{{ $config->key ?? old('key') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('key')" />
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-7">
                            <label class="fw-semibold fs-6 mb-2">Value</label>
                            <textarea name="value" id="value" rows="5"
                                class="form-control form-control-solid mb-3 mb-lg-0 @error('value') is-invalid border border-1 border-danger @enderror"
                                placeholder="Config Value">{{ $config->value ?? old('value') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('value')" />
                        </div>
                  </div>
                  <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ route('resources.setting.config.index') }}">
                        <button type="button" class="btn btn-light me-3">Cancel</button>
                    </a>
                    <!-- <button type="submit" class="btn btn-primary me-3" name="update_and_continue_editing" value="1">
                        <span class="indicator-label" id="submitAndOther">Update & Continue Editing</span>
                    </button> -->
                    <button type="submit" class="btn btn-primary" name="update">
                        <span class="indicator-label" id="submit">Submit</span>
                    </button>
                  </div>
              </form>
            </div>
        </div>
      </div>
    </div>

</x-app-layout>
