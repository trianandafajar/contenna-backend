<div id="kt_app_footer" class="app-footer">
    <!--begin::Footer container-->
    <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
        <!--begin::Copyright-->
        <div class="text-muted fw-semibold order-2 order-md-1" style="text-align: center;">
            <span class="text-muted fw-semibold me-1">2025 &copy;</span>
            <span class="text-muted">{{ config('app.name') }}</span>
        </div>
        <!--end::Copyright-->
        <!--begin::Menu-->
        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <a href="{{ route('version.logs') }}"  class="menu-link px-2">Version</a>
            </li>
          
            {{-- @if (!Auth::user()->hasRole('admin')) --}}
            <li class="menu-item">
                <div class="menu-link px-2" id="kt_drawer_chat_toggle">Feedback</div>
            </li>
            {{-- @endif --}}
        </ul>
        <!--end::Menu-->
    </div>
    <!--end::Footer container-->
</div>

<div id="kt_drawer_chat" class="bg-body overflow" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
    <!--begin::Messenger-->
    <div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
        <!--begin::Card header-->
        <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
            <!--begin::Title-->
            <div class="card-title">
                <!--begin::User-->
                <div class="d-flex justify-content-center flex-column me-3">
                    <p class="fs-4 fw-bold text-gray-900 me-1 mt-4">Send Your Feedback</p>
                </div>

                <!--end::User-->
            </div>
            <div class="btn btn-sm btn-icon btn-active-color-primary mt-5" id="kt_drawer_chat_close">
                <i class="ki-outline ki-cross-square fs-2"></i>
            </div>
            <!--end::Title-->
            <!--begin::Card toolbar-->
            <!--end::Card toolbar-->
        </div>
        <form id="kt_modal_add_user_form"  class="m-n8" class="form" action="{{ route('feedback.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body overflow-hidden" id="kt_drawer_chat_messenger_body">
            <!--begin::Messages-->
            <div data-kt-element="messages" 
            data-kt-scroll="true" 
            data-kt-scroll-activate="true" 
            data-kt-scroll-height="auto" 
            data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header" 
            data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" 
            data-kt-scroll-offset="50px">
                    <div class="card-body">
                        <div>
                            <label class="mb-3 fw-semibold fs-5 fw-bold">Select Your Feedback Type</label>
                            <div class="d-block mb-3">
                                <!--begin::Radio-->
                                <div class="form-check form-check-custom form-check-solid">
                                    <!--begin::Input-->
                                    <input class="form-check-input me-3" name="user_role" type="radio" value="1"/>
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <label class="form-check-label" for="kt_modal_update_role_option_0">
                                        <div class="fw-bold text-gray-800">Add More Features</div>
                                        {{-- <div class="text-gray-600">Description</div> --}}
                                    </label>
                                    <!--end::Label-->
                                </div>
                            </div>
                            <div class="d-block mb-3">
                                <div class="form-check form-check-custom form-check-solid">
                                    <!--begin::Input-->
                                    <input class="form-check-input me-3" name="user_role" type="radio" value="2"/>
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <label class="form-check-label" for="kt_modal_update_role_option_0">
                                        <div class="fw-bold text-gray-800">Report Bug</div>
                                        {{-- <div class="text-gray-600">Description</div> --}}
                                    </label>
                                    <!--end::Label-->
                                </div>
                            </div>
                            <div class="d-block mb-3">
                                <div class="form-check form-check-custom form-check-solid">
                                    <!--begin::Input-->
                                    <input class="form-check-input me-3" name="user_role" type="radio" value="3"/>
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <label class="form-check-label" for="kt_modal_update_role_option_0">
                                        <div class="fw-bold text-gray-800">Other</div>
                                        {{-- <div class="text-gray-600">Description</div> --}}
                                    </label>
                                    <!--end::Label-->
                                </div>
                            </div>
                            <span class="text-danger" id="alertSelect" style="display: none;">Select One of the Options Before Continuing!</span>
                        </div>
                        <div class="mt-10">
                            <label class="d-block mb-3 fw-semibold fs-5 fw-bold position-relative">Attachment <small class="fw-lighter mt-auto position-absolute top-0 ms-2" style="font-size: 10px; top: 0;">max 10mb</small></label>
                            <input type="file" name="file" class="form-control form-control-solid mb-3 mb-lg-0">
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                            <span class="text-danger" id="alertFeedback" style="display: none;">Please Provide Your Feedback!</span>
                        </div>
                        <div class="mt-10">
                            <label class="d-block mb-3 fw-semibold fs-5 fw-bold">Write Your Feedback</label>
                            <textarea class="form-control" name="feedback" id="feedback" cols="50" rows="5" style="resize: none; height:70%;"></textarea>
                            <x-input-error :messages="$errors->get('feedback')" class="mt-2" />
                            <span class="text-danger" id="alertFeedback" style="display: none;">Please Provide Your Feedback!</span>
                        </div>
                    </div>

            </div>
            <!--end::Messages-->
        </div>
        <!--end::Card body-->
        <!--begin::Card footer-->
        <div class="card-footer me-8 mt-8 mb-n8" id="kt_drawer_chat_messenger_footer">
            <!--begin:Toolbar-->
            <div class="d-flex flex-end">
                    <a class="btn btn-light me-3" id="clearForm">Clear</a>
                    <!-- <button type="submit" class="btn btn-primary me-3" name="save_and_add_other" value="1">
                        <span class="indicator-label" id="submitAndOther">Create & Add Another</span>
                    </button> -->
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label" id="submit">Submit</span>
                    </button>
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Card footer-->
        </form>
    </div>
    <!--end::Messenger-->
</div>

<script>
    var getButton = document.getElementById("clearForm");
    getButton.addEventListener('click', function(){
        document.getElementById("kt_modal_add_user_form").reset();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radioButtons = document.querySelectorAll('input[name="user_role"]');
        const feedbackTextarea = document.getElementById('feedback');
        const form = document.getElementById('kt_modal_add_user_form');
        const alertSelect = document.getElementById('alertSelect');
        const alertFeedback = document.getElementById('alertFeedback');

        form.addEventListener('submit', function (event) {
            let isSelected = false;
            radioButtons.forEach(function (radio) {
                if (radio.checked) {
                    isSelected = true;
                }
            });

            if (!isSelected) {
                event.preventDefault();
                alertSelect.style.display = 'block'; // Tampilkan pesan kesalahan jika tidak ada opsi yang dipilih
            } else {
                alertSelect.style.display = 'none'; // Sembunyikan pesan kesalahan jika opsi dipilih
            }

            if (!feedbackTextarea.value.trim()) {
                event.preventDefault();
                alertFeedback.style.display = 'block'; // Tampilkan pesan kesalahan jika textarea kosong
            } else {
                alertFeedback.style.display = 'none'; // Sembunyikan pesan kesalahan jika textarea diisi
            }
        });
    });
</script>
