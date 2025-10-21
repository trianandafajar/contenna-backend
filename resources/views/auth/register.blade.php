<x-guest-layout>
     <!-- TODO : set Translate -->
    <form method="POST" class="form w-100" novalidate="novalidate" action="{{ route('register') }}">
        @csrf
        
        <div class="text-center mb-11">
            <h1 class="text-dark fw-bolder mb-3">Sign Up</h1>
            <div class="text-gray-500 fw-semibold fs-6">Register your account</div>
        </div>

        <div class="mb-7">
            <label class="required fw-semibold fs-6 mb-2">Full Name</label>
            <input type="text" name="name" id="name"
                class="form-control bg-transparent mb-3 mb-lg-0 {{ $errors->get("name") ? "is-invalid border border-1 border-danger" : "" }}" placeholder="Full name"
                value="{{ old('name') }}" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="fv-row mb-8">
            <label class="required fw-semibold fs-6 mb-2">Email</label>
            <x-text-input id="email" class="form-control bg-transparent" type="email" name="email" :value="old('email')" placeholder="Email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="fv-row mb-8" data-kt-password-meter="true">
            <label class="required fw-semibold fs-6 mb-2">Password</label>
            <div class="mb-1">
                <div class="position-relative mb-3">
                    <x-text-input id="password" class="form-control bg-transparent"
                                type="password"
                                name="password"
                                placeholder="Password"
                                required autocomplete="new-password" />
                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                        <i class="ki-outline ki-eye-slash fs-2"></i>
                        <i class="ki-outline ki-eye fs-2 d-none"></i>
                    </span>
                </div>
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
            </div>
            <div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="fv-row mb-8">
            <label class="required fw-semibold fs-6 mb-2">Repeat Password</label>
            <x-text-input id="password_confirmation" class="form-control bg-transparent"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Repeat Password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="fv-row mb-8" data-kt-password-meter>
            <label class="required fw-semibold fs-6 mb-2">Registration Code</label>
            <div class="position-relative mb-3">
                    <x-text-input id="registration_code" class="form-control bg-transparent"
                                type="password"
                                name="registration_code"
                                placeholder="registration_code"
                                required autocomplete="new-registration_code" />
                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                        <i class="ki-outline ki-eye-slash fs-2"></i>
                        <i class="ki-outline ki-eye fs-2 d-none"></i>
                    </span>
                </div>
            <x-input-error :messages="$errors->get('registration_code')" class="mt-2" />
        </div>

        {{-- <div class="fv-row mb-8">
            <label class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="accept_terms" required/>
                <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">I Accept the
                    <a href="#" class="ms-1 link-primary">Terms</a>
                </span>
            </label>
            <x-input-error :messages="$errors->get('accept_terms')" class="mt-2" />
        </div> --}}

        <div class="d-grid mb-10">
            <button type="submit" class="btn btn-primary">
                <span class="indicator-label">Sign up</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>

        <div class="text-gray-500 text-center fw-semibold fs-6">Already have an Account?
            <a href="{{ route('login') }}" class="link-primary fw-semibold">Sign in</a>
        </div>
    </form>
</x-guest-layout>

