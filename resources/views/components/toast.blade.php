<div id="auto-hide-toast" class="toast show position-fixed border border-1 border-{{ $type }} p-2" role="alert" aria-live="assertive"
    aria-atomic="true" style="bottom: 2rem; right: 2rem; z-index: 100;">
    <div class="toast-header d-flex justify-content-between">
        @if(config('setting.general.app_logo') !== 'logo.png')
        <img src="{{ asset('media/logo/'.config('setting.general.app_logo')) }}" class="rounded me-2" alt="logo"
            style="height:2rem; width: auto">
        @else
        <img src="{{ asset('assets/media/logos/logo.png') }}" class="rounded me-2" alt="logo"
            style="height:2rem; width: auto">
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        <h5 class="fs-5">
            {{ $message }}
        </h5>
    </div>
</div>
