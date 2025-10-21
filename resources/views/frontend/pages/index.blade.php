@extends('layouts.home', ['include' => ['hero', 'tag-menu']], ['title' => 'AllFill Documentation'])

@section('content')
    {{-- <input type="hidden" id="search-query" value="{{ request('q') }}"> --}}
    <div id="blog-container" class="row  row-cols-1 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 mt-4 g-4">
        @foreach ($blogs as $blog)
            @if ($blog->status == 1)
                @if (
                    $blog->special_role == 1 ||
                        (Auth::check() && Auth::user()->hasAnyRole(['super-admin', 'administrator', 'mentor', 'koordinator'])))
                    <div class="col">
                        <div class="card border border-secondary-subtle shadow-sm rounded-lg h-100 border-hover mx-auto"
                            style="cursor: pointer; max-width: 22rem">
                            <!-- Header -->
                            <div class="card-header bg-white d-flex justify-content-between align-items-center"
                                style="border-bottom: none">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center overflow-hidden bg-light position-relative"
                                            style="width: 40px; height: 40px;" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="{{ $blog->user->name ?? 'Author' }}">

                                            @if ($blog->user->avatar)
                                                <img src="{{ URL::asset('media/avatars/' . $blog->user->avatar) }}"
                                                    alt="{{ $blog->user->name ?? 'Author' }}"
                                                    class="img-fluid w-100 h-100 object-fit-cover">
                                            @else
                                                <span
                                                    class="d-flex align-items-center justify-content-center w-100 h-100 bg-danger text-white fw-bold">
                                                    {{ strtoupper(substr($blog->user->name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>

                                        <span
                                            class="position-absolute start-50 translate-middle-x bg-dark text-white small px-2 py-1 rounded opacity-0 d-none">{{ Str::limit($blog->user->name ?? 'Unknown', 15) }}</span>
                                    </div>
                                    <a href="{{ url('/blogs/?category='.$blog->category->slug) }}"
                                        class="ms-2 text-muted text-decoration-none hover-color">
                                        — {{ $blog->category->name ?? 'Uncategorized' }}
                                    </a>
                                </div>
                                @if (Auth::check())
                                    <button class="btn p-0 btn-bookmark" data-blog-id="{{ $blog->id }}"
                                        onclick="toggleBookmark({{ $blog->id }})">
                                        <i
                                            class="{{ in_array($blog->id, $bookmarkedBlogs) ? 'fas fa-bookmark text-primary' : 'far fa-bookmark ' }} fs-1"></i>
                                    </button>
                                @else
                                    <button class="btn p-0" onclick="window.location.href='{{ route('login') }}'">
                                        <i class="far fa-bookmark text-muted fs-1"></i>
                                    </button>
                                @endif
                            </div>

                            <!-- Image & Title -->
                            <div class="card-body  pt-2 ps-6 pe-6 pb-5 ">
                                @php
                                    $thumbnail = $blog->thumbnail
                                        ? (Str::startsWith($blog->thumbnail, 'http')
                                            ? $blog->thumbnail
                                            : asset('storage/' . $blog->thumbnail))
                                        : asset('assets/media/icons/duotune/modul/File_Explorer_Icon.webp');
                                @endphp
                                <a href="{{ route('single.show', $blog->slug) }}">
                                    <img src="{{ $thumbnail }}" alt="{{ $blog->title }}"
                                        class="img-fluid rounded w-100 mb-3" style="height: 160px; object-fit: cover;">
                                    <h5 class="fw-bold fs-3">
                                        {{ Str::limit($blog->title, 40) }}
                                    </h5>
                                </a>
                                <p class="text-muted small mb-3">{{ $blog->created_at->format('M d, Y • H:i') }}</p>

                                <!-- Tags -->
                                <div class="d-flex flex-wrap">
                                    @foreach ($blog->tags->take(5) as $tag)
                                        <a href="{{ url('blogs/?tag='. $tag->slug) }}"
                                            class="badge bg-light me-2 mb-2 text-decoration-none" style="color: #5a6771;">
                                            #{{ Str::limit($tag->name, 10) }}
                                        </a>
                                    @endforeach

                                    @if ($blog->tags->count() > 5)
                                        <span class="badge bg-secondary text-white me-2 mb-2 popover-btn"
                                            data-bs-toggle="popover" data-bs-html="true" data-bs-placement="top"
                                            data-popover-content="#popover-content-{{ $blog->id }}"
                                            style="cursor: pointer">
                                            +{{ $blog->tags->count() - 5 }} more
                                        </span>

                                        <!-- Elemen tersembunyi untuk konten popover -->
                                        <div id="popover-content-{{ $blog->id }}" class="d-none">
                                            @foreach ($blog->tags->slice(5) as $tag)
                                                <a href="{{ url('blogs/?tag='.$tag->slug) }}"
                                                    class="d-block text-decoration-none text-dark badge">
                                                    #{{ $tag->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    </div>

    <!-- Load More Button -->
    <div class="d-flex justify-content-center mt-4">
        @if ($blogs->hasMorePages())
            <button id="load-moree" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2">
                <span class="button-text">Load More</span>
                <div class="spinner-border spinner-border-sm text-light d-none" id="loading-spinner" role="status"></div>
            </button>
        @endif
    </div>


    <script>
        document.getElementById("load-moree").addEventListener("click", function() {
            const button = this;
            const spinner = document.getElementById("loading-spinner"); // Ambil berdasarkan ID
            
            button.disabled = true; // Nonaktifkan tombol sementara
            spinner.classList.remove("d-none"); // Tampilkan spinner
    
            setTimeout(() => {
                button.disabled = false; // Aktifkan tombol kembali
                spinner.classList.add("d-none"); // Sembunyikan spinner lagi
            }, 1000);
        });
    </script>
    <script>
        let isAuthenticated = @json(Auth::check());
        var userRoles = @json(Auth::check() ? Auth::user()->getRoleNames() : []);
    </script>
    <script>
        function userHasRole(requiredRoles) {
            if (!Array.isArray(userRoles)) {
                console.error("❌ userRoles bukan array!", userRoles);
                return false;
            }
            return userRoles.some(role => requiredRoles.includes(role));
        }
        
    </script>
    <script>
        function initializePopovers() {
            $(".popover-btn").each(function() {
                let contentId = $(this).attr("data-popover-content");
                let popoverContent = $(contentId).html();

                new bootstrap.Popover(this, {
                    html: true,
                    trigger: "manual",
                    content: popoverContent,
                });
            });
        }

        // Inisialisasi semua popovers saat halaman pertama kali dimuat
        $(document).ready(function() {
            initializePopovers();
        });

        // Event delegation untuk menangani klik pada semua popover-btn
        $(document).on("click", ".popover-btn", function(e) {
            e.stopPropagation();

            let popover = bootstrap.Popover.getInstance(this);
            if (!popover) {
                let contentId = $(this).attr("data-popover-content");
                let popoverContent = $(contentId).html();

                popover = new bootstrap.Popover(this, {
                    html: true,
                    trigger: "manual",
                    content: popoverContent,
                });
            }

            // Toggle popover
            if ($(this).attr("aria-describedby")) {
                popover.hide();
            } else {
                popover.show();
            }
        });

        // Tutup popover jika klik di luar
        $(document).on("click", function(e) {
            $(".popover-btn").each(function() {
                let popover = bootstrap.Popover.getInstance(this);
                if (popover) popover.hide();
            });
        });
    </script>

@endsection
