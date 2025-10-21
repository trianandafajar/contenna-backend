<x-app-layout>
    @section('title', 'Recovery User')
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
                        {{ __('Recovery User') }}
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
                <form  class="form" action="{{ route('resources.user-deleted.update', $user->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body py-4">
                        <div class="mb-7 mt-4">
                            <h4>Message Reason</h4>
                            <p>{{ $user->deleted_reason }}</p>
                        </div>
                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Role</label>
                            <select name="role_id[]" id="role_id" data-control="select2" data-hide-search="true" multiple="multiple"
                                class="form-select form-select-solid mb-3 mb-lg-0 {{ $errors->get('role_id') ? 'is-invalid border border-1 border-danger' : '' }}">
                                <option value="">Select Role</option>
                                @foreach ($roles as $id => $name)
                                    <option value="{{ $id }}" {{ $user->hasRole($id) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('role_id')" />
                        </div>

                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Circle</label>
                            <div class="row">
                                <div class="col-6">
                                    <select name="circle_id" id="circle_id" data-control="select2"
                                        class="form-select form-select-solid mb-3 mb-lg-0 {{ $errors->has('circle_id') ? 'is-invalid border border-1 border-danger' : '' }}" required>
                                        <option value="">Select Circle</option>
                                        @foreach ($circles as $id => $name)
                                            <option value="{{ $id }}" {{ (old('circle_id', optional($user->circleMember->first())->circle_id ?? '') == $id) ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('circle_id')" />
                                </div>
                                <div class="col-6">
                                    <select name="position_id" id="position_id" data-control="select2"
                                        class="col-6 form-select form-select-solid mb-3 mb-lg-0 {{ $errors->has('position_id') ? 'is-invalid border border-1 border-danger' : '' }}" required>
                                        <option value="">Position</option>
                                        @foreach ($positions as $id => $name)
                                        <option value="{{ $id }}" {{ (old('position_id', optional($user->circleMember->first())->position_id ?? '') == $id) ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('position_id')" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('resources.user-deleted.index') }}">
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


</x-app-layout>
