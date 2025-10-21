<x-app-layout>
    @section('title', 'Send Feedback')
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
                        {{ __('Send Your Feedback') }}
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Toolbar container-->
    </div>


    <div id="kt_app_content" class="app-content flex-column-fluid" style="background-color: #f6f6f6;}">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid"
            style="padding-left: 0px!important; padding-right: 0px!important">
            <!--begin::Card-->
            <div class="card">
                <form  class="form" action="{{ route('feedback.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body py-4 pt-3 pb-10">
                        <div>
                            <label class="fw-semibold fs-6 mb-2 mt-5">Name</label>
                            <p>{{ $feedback->name }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                            <label class="fw-semibold fs-6 mb-2 mt-5">Email</label>
                            <p>{{ $feedback->email }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('email')"/>
                            <label class="fw-semibold fs-6 mb-2 mt-5">Type</label>
                            <p>{{ $feedback->type == 1 ? 'Add More Feature' : ($feedback->type == 2 ? 'Report Bug' : ($feedback->type == 3 ? 'Other' : 'Unknown')) }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('type')"/>
                            <label class="fw-semibold fs-6 mb-2 mt-5">Date</label>
                            <p>{{ $feedback->created_at->format('Y-m-d') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('date')" />
                        </div>
                        <div>
                            <label class="mb-3 d-block mb-3 fw-semibold fs-6 mt-5">Message</label>
                            <p>{{ $feedback->message }}</p>
                        </div>

                        @if($feedback->file)
                            <div>
                                <label class="mb-3 d-block mb-3 fw-semibold fs-6 mt-5">File</label>
                                @if(pathinfo($feedback->file, PATHINFO_EXTENSION) == 'mp4')
                                    <video height="240" controls>
                                        <source src="{{ asset('media/feedback/'.$feedback->file) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ asset('media/feedback/'.$feedback->file) }}" alt="File"  height="240">
                                @endif
                            </div>
                        @endif  
                    </div>
                </form>
            </div>
        </div>


</x-app-layout>
