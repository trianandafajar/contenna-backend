<x-app-layout>
    @section('title', 'Create User')
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
                        {{ __('Create User') }}
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
            <form  class="form" action="{{ route('resources.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body py-4">
                    <div class="mb-7">
                        <label class="fw-semibold fs-6 mb-2">Avatar</label>
                        <input type="file" name="avatar" id="avatar"
                            class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("avatar") ? "is-invalid border border-1 border-danger" : "" }}"
                            value="{{ old('avatar') }}" accept="image/*"/>
                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>

                    <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Full Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("name") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="Full name"
                            value="{{ old('name') }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Email</label>
                        <input type="email" name="email" id="email"
                            class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("email") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="example@domain.com"
                            value="{{ old('email') }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                    <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Role</label>
                        <select name="role_id[]" id="role_id" data-control="select2" data-hide-search="true" multiple="multiple" aria-placeholder="Select Role"
                            class="form-select form-select-solid mb-3 mb-lg-0 {{ $errors->get('role_id') ? 'is-invalid border border-1 border-danger' : '' }}">
                            <option value="" disabled hidden requried>Select Role</option>
                            @foreach ($roles as $id => $name)
                                <option value="{{ $id }}" {{ old('role_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role_id')" />
                    </div>
                    <div class="mb-7">
                        <label class="fw-semibold fs-6">Password</label>
                        <br><small class="mb-2" style="color: gray">*)Biarkan Password Kosong untuk generate otomatis : docuverse123</small>
                        <div class="position-relative" data-kt-password-meter="true">
                            <input type="password" name="password" id="password"
                                class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("password") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="Password" />
                            <span class="btn btn-sm btn-icon position-absolute translate-middle end-0 me-n2 top-50" style="{{ $errors->get("password") ? "left: 97%;" : "" }}"
                                data-kt-password-meter-control="visibility">
                                <i class="ki-outline ki-eye-slash fs-2"></i>
                                <i class="ki-outline ki-eye fs-2 d-none"></i>
                            </span>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>
                    <div class="mb-7">
                        <label class="fw-semibold fs-6 mb-2">Retype Password</label>
                        <div class="position-relative" data-kt-password-meter="true">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get('password_confirmation') ? 'is-invalid border border-1 border-danger' : '' }}"
                                placeholder="Retype Password" />
                            <span class="btn btn-sm btn-icon position-absolute translate-middle end-0 me-n2 top-50" style="{{ $errors->get("password_confirmation") ? "left: 97%;" : "" }}"
                                data-kt-password-meter-control="visibility">
                                <i class="ki-outline ki-eye-slash fs-2"></i>
                                <i class="ki-outline ki-eye fs-2 d-none"></i>
                            </span>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ route('resources.users.index') }}">
                        <button type="button" class="btn btn-light me-3">Cancel</button>
                    </a>
                    <button type="submit" class="btn btn-primary me-3" name="save_and_add_other" value="1">
                        <span class="indicator-label" id="submitAndOther">Create & Add Another</span>
                    </button>
                    <button type="submit" class="btn btn-primary" name="save">
                        <span class="indicator-label" id="submit">Create</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
</script>