<x-app-layout>
    @section('title', 'Create Version Log')
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
                        {{ __('Create Version Log') }}
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
            <form  class="form" action="{{ route('log-version.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body py-4">

                    <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Update title</label>
                        <input type="text" name="title" id="title"
                            class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("title") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="Title"
                            value="{{ old('title') }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Update date</label>
                        <input type="date" name="update_date" id="update_date"
                            class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("update_date") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="date founded"
                            value="{{ old('update_date') }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('update_date')" />
                    </div>


                    <div class="mb-7">
                        <label class="fw-semibold fs-6 mb-2">Deskripsi update</label>
                        <div id="dynamic-description"></div>
                        <div class="row pt-5 pb-5">
                            <div class="col-12 col-md-6 order-2 order-md-2">
                                <div class="button">
                                    <button type="button" id="add-description" class="btn btn-sm btn-outline-primary border border-primary text-primary text-hover-white">
                                        + Add Description
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ route('log-version.index') }}">
                        <button type="button" class="btn btn-light me-3">Cancel</button>
                    </a>
                    <button type="submit" class="btn btn-primary" name="save">
                        <span class="indicator-label" id="submit">Submit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
   
</x-app-layout>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> --}}
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        var serviceCounter = 0;
        // Menangani klik tombol "+"
        $('#add-description').click(function() {
            var market = '<div class="row border-bottom border-b-1 border-secondary py-2 pb-4" id="row-market-' + (serviceCounter + 1) + '">' +
                            '<div class="col-7 pt-3">' +
                                '<div id="type">' +
                                    '<textarea name="description[]" id="type-' + (serviceCounter + 1) + '" class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get("description") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="Deskripsi"></textarea>' +
                                    '<x-input-error class="mt-2" :messages="$errors->get("type")" />' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-2 pt-3 mb-1 d-flex justify-content-end justify-content-lg-center align-items-end">' +
                                '<button type="button" class="btn btn-danger btn-sm remove-market" id="' + (serviceCounter + 1) + '">x</button>' +
                            '</div>' +
                        '</div>';

            $('#dynamic-description').append(market);
            serviceCounter++; 
        });


        $(document).on('click', '.remove-market', function() {
            var button_id_service = $(this).attr("id");
            $('#row-market-' + button_id_service).remove();
        });
    });


   
</script>