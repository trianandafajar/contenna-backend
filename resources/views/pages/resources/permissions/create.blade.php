<x-app-layout>
    @section('title', 'Create Permission')
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
                        {{ __('Create Permission') }}
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
            <form  class="form px-5" action="{{ route('resources.permissions.store') }}" method="POST">
                @csrf
                  <div class="card-body py-4">
                    <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("name") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="Permission Name"
                            value="{{ old('name') }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Guard Name</label>
                        <select name="guard_name" id="guard_name" class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get('guard_name') ? 'is-invalid border border-1 border-danger' : '' }}">
                            <option value="">Select Guard Name</option>
                            <option value="web" {{ old('guard_name') === 'web' ? 'selected' : '' }}>Web</option>
                            <option value="api" {{ old('guard_name') === 'api' ? 'selected' : '' }}>API</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('guard_name')" />
                    </div>
                  </div>
                  <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ route('resources.permissions.index') }}">
                        <button type="button" class="btn btn-light me-3">Cancel</button>
                    </a>
                    <!-- <button type="submit" class="btn btn-primary me-3" name="save_and_add_other" value="1">
                        <span class="indicator-label" id="submit">Create & Add Another</span>
                    </button> -->
                    <button type="submit" class="btn btn-primary" name="save">
                        <span class="indicator-label" id="submit">Submit</span>
                    </button>
                  </div>
              </form>
            </div>
        </div>
      </div>
    </div>
</x-app-layout>
