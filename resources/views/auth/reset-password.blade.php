<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" class="form w-100" novalidate="novalidate">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="text-center mb-10">
            <h1 class="text-dark fw-bolder mb-3">Setup New Password</h1>
            <div class="text-gray-500 fw-semibold fs-6">
                Have you already reset the password? <a href="{{ route('login') }}" class="link-primary fw-bold">Sign in</a>
            </div>
        </div>

        <div class="fv-row mb-8">
            <x-text-input id="email" class="form-control bg-transparent" placeholder="Email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="fv-row mb-8" data-kt-password-meter="true">
            <div class="mb-1">
                <div class="position-relative mb-3">
                    <x-text-input id="password" class="form-control bg-transparent" placeholder="Password" type="password" name="password" required autocomplete="new-password" />
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
            <x-text-input id="password_confirmation" class="form-control bg-transparent" type="password" placeholder="Repeat Password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="d-grid mb-10">
            <button type="submit" id="kt_new_password_submit" class="btn btn-primary">
                <span class="indicator-label">Submit</span>
            </button>
        </div>
    </form>
</x-guest-layout>
