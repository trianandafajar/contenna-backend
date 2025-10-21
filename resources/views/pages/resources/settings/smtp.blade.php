<x-app-layout>
    @section('title', 'SMTP Settings')
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
                        {{ __('SMTP Settings') }}
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
        <div id="kt_app_content_container" class="app-container container-fluid"
            style="padding-left: 0px!important; padding-right: 0px!important">
            <!--begin::Card-->
            <div class="card">
                <form action="{{ route('resources.setting.smtp.update') }}" method="POST" enctype="multipart/form-data"
                    id="kt_account_profile_details_form" class="form">
                    @csrf
                    <div class="card-body py-4">
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Mail Name</label>
                            <div class="col-lg-8">
                                <input type="text" name="mail_name"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    placeholder="Mail Name"
                                    value="{{ config('mail.from.name') ?? old('mail_name') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_name')" />
                                <small class="font-14 text-muted">This will be the display name for your sent
                                    email.</small>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Mail Address</label>
                            <div class="col-lg-8">
                                <input type="text" name="mail_address"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    placeholder="Mail Address"
                                    value="{{ config('mail.from.address') ?? old('mail_address') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_address')" />
                                <small class="font-14 text-muted">This email will be used for "Contact Form"
                                    correspondence.</small>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Mail Driver</label>
                            <div class="col-lg-8">
                                <input type="text" name="mail_driver"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    placeholder="Mail Driver"
                                    value="{{ config('mail.default') ?? old('mail_driver') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_driver')" />
                                <small class="font-14 text-muted">You can select any driver you want for your Mail
                                    setup. Ex. SMTP, Mailgun, Mandrill, SparkPost, Amazon SES, etc. Add a single driver
                                    only.</small>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Mail Host</label>
                            <div class="col-lg-8">
                                <input type="text" name="mail_host"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    placeholder="Mail Host"
                                    value="{{ config('mail.mailers.smtp.host') ?? old('mail_host') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_host')" />
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Mail Port</label>
                            <div class="col-lg-8">
                                <input type="text" name="mail_port"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    placeholder="Mail Port"
                                    value="{{ config('mail.mailers.smtp.port') ?? old('mail_port') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_port')" />
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Mail Username</label>
                            <div class="col-lg-8">
                                <input type="text" name="mail_username"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    placeholder="Mail Username"
                                    value="{{ config('mail.mailers.smtp.username') ?? old('mail_username') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_username')" />
                                <small class="font-14 text-muted">Add your email ID you want to configure for sending
                                    emails.</small>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Mail Password</label>
                            <div class="col-lg-8 position-relative">
                                <div class="d-flex align-items-center">
                                    <input type="password" name="mail_password" id="mail_password"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        placeholder="Mail Password"
                                        value="{{ config('mail.mailers.smtp.password') ?? old('mail_password') }}" />
                                    <span class="ms-3" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                                        <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                    </span>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('mail_password')" />
                                <small class="font-14 text-muted">Add your email password you want to configure for
                                    sending emails.</small>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Mail Encryption</label>
                            <div class="col-lg-8">
                                <input type="text" name="mail_encryption"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    placeholder="Mail Encryption"
                                    value="{{ config('mail.mailers.smtp.encryption') ?? old('mail_encryption') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_encryption')" />
                                <small class="font-14 text-muted">Use "tls" if your site uses HTTP protocol and "ssl"
                                    if your site uses HTTPS protocol.</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="button" class="btn btn-primary" id="kt_account_profile_details_submit"
                            onclick="confirmSave()">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('mail_password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    function confirmSave() {
        Swal.fire({
            title: 'Are You Sure?',
            text: "You won't be able to undo this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, sure!',
            cancelButtonText: 'Cancel'
        }).then((firstResult) => {
            if (firstResult.isConfirmed) {
                Swal.fire({
                    title: 'Are You Absolutely Sure?',
                    text: "Once saved, this action cannot be reversed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'Cancel'
                }).then((secondResult) => {
                    if (secondResult.isConfirmed) {
                        document.getElementById('kt_account_profile_details_form').submit();
                    }
                });
            }
        });
    }
</script>
