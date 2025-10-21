<x-app-layout>
    @section('title', 'Edit Category')
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
                        {{ __('Edit Category') }}
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
                <form  class="form" action="{{ route('master.categories.update', $category->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body py-4">
                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("name") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="Name"
                                value="{{ $category->name }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div class="mb-7">
                            <label class="fw-semibold fs-6 mb-2">Slug</label>
                            <input type="text" name="slug" id="slug"
                                class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("slug") ? "is-invalid border border-1 border-danger" : "" }}" id="slug" placeholder="Slug"
                                value="{{ $category->slug }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        </div>
                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Status</label>
                            <select name="status" id="status" data-control="select2" data-placeholder="Select Status..." data-hide-search="true" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="" hidden selected disabled>Select Status</option>
                                <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ $category->status == 2 ? 'selected' : '' }}>Non Active</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('master.categories.index') }}">
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
</x-app-layout>

<script>
    $(document).ready(function() {
        if ($('#slug').val() == '') {
            $('#slug').val($('#name').val().toLowerCase().replace(/ /g, '-'));
        }
        $('#name').on('input', function() {
            $('#slug').val($(this).val().toLowerCase().replace(/ /g, '-'));
        });
    });
</script>