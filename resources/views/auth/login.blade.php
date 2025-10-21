<x-guest-layout>
    <!-- TODO : set Translate -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="text-center mb-11">
            <h1 class="text-dark fw-bolder mb-3">Login</h1>
            <div class="text-gray-500 fw-semibold fs-6">input your credentials</div>
        </div>

        <div class="fv-row mb-8">
            <x-text-input id="email" class="form-control bg-transparent" name="email"
                :value="old('email')" autocomplete="off" placeholder="Email / Username" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="fv-row mb-3" data-kt-password-meter="true">
            <div class="position-relative mb-3">
                <x-text-input id="password" class="form-control bg-transparent" type="password" name="password"
                    placeholder="Password" required autocomplete="current-password" />
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                    data-kt-password-meter-control="visibility">
                    <i class="ki-outline ki-eye-slash fs-2"></i>
                    <i class="ki-outline ki-eye fs-2 d-none"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <div></div>
            <a href="{{ route('password.request') }}" class="link-primary">Forgot Password ?</a>
        </div>

        <div class="d-grid mb-10">
            <button type="submit" class="btn btn-primary">
                <span class="indicator-label">Login</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>

        <div class="text-gray-500 text-center fw-semibold fs-6">Not registered?
            <a href="{{ route('register') }}" class="link-primary">Sign up</a>
        </div>
    </form>
</x-guest-layout>
