<x-app-layout>
    @section('title', 'Create Roles')
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
                        {{ __('Create Roles') }}
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
            <form id="kt_modal_add_role_form" class="form" action="{{ route('resources.roles.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                  <div class="card-body py-4">
                    <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Role Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("name") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="Role Name"
                            value="{{ old('name') }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div class="mb-10">
                        <label class="required fw-semibold fs-6 mb-5">Permission</label>
                        @if(count($permissions) > 0)
                            @foreach($permissions as $key => $value)
                                <div class="d-flex fv-row">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input me-3" name="permission[]" type="checkbox" value="{{ $value->id }}" id="kt_modal_add_role_option_{{ $value->id }}">
                                        <label class="form-check-label" for="kt_modal_add_role_option_{{ $value->id }}">
                                            <div class="fw-bold text-gray-800">{{ $value->name }}</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-5"></div>
                            @endforeach
                        @else
                            <p>No permissions available. Please create some permissions first.</p>
                        @endif
                        <x-input-error class="mt-2" :messages="$errors->get('permission')" />
                    </div>

                  </div>
                  <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('resources.roles.index') }}">
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
