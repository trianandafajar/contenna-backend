<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form class="form w-100" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="text-center mb-10">
            <h1 class="text-dark fw-bolder mb-3">Forgot Password ?</h1>
            <div class="text-gray-500 fw-semibold fs-6">Enter your email to reset your password.</div>
        </div>

        <div class="fv-row mb-8">
            <x-text-input id="email" placeholder="Email" class="form-control bg-transparent" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <a href="{{ route('login') }}" class="btn btn-light">Back to Login</a>
            <button type="submit" class="btn btn-primary me-4">
                <span class="indicator-label">Send Email</span>
            </button>
        </div>
    </form>
</x-guest-layout>
