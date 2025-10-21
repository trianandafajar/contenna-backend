@extends('layouts.home')
@section('title', 'blogs filter')
@section('content')
    <style>
        .card-header {
            padding-left: 16px !important;
            padding-right: 16px !important;

        }
    </style>
    <div class="container-fluid px-4 py-5">
        <header class=" text-white text-center py-5 px-4 rounded shadow-md position-relative"
            style="background: linear-gradient(320deg, #1153FC, #5581F1);">
            <h1 class="fw-bold text-uppercase" style="font-size: 3rem; letter-spacing: 2px; color:#ffffff">
                Discover Your Content
            </h1>
            <p class="mt-2 fs-4 text-light">
                {{ $blogs->total() }} post
            </p>
        </header>
        {{-- sidebar --}}
        <div class="row mt-5">
            <!-- Sidebar Column -->
            <div class="col-lg-3">
                @include('frontend.components.searchbar')
            </div>
            <!-- Content Column -->
            <div class="col-lg-9">
                <div id="blog-container" class="row row-cols-1 row-cols-md-3 row-cols-lg-3 g-6">
                    @foreach ($blogs as $blog)
                        @if ($blog->status == 1)
                            @if (
                                $blog->special_role == 1 ||
                                    (Auth::check() && Auth::user()->hasAnyRole(['super-admin', 'administrator', 'mentor', 'koordinator'])))
                                 <div class="col">
                                    <div class="card border border-secondary-subtle shadow-sm rounded-lg h-100 border-hover "
                                        style="cursor: pointer;">
                                        <!-- Header -->
                                        <div class="card-header bg-white d-flex justify-content-between align-items-center"
                                            style="border-bottom: none ">
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
                                                <a href="{{ url('blogs/?category=' . $blog->category->name) }}"
                                                    class="ms-2 text-muted text-decoration-none hover-color">
                                                    — {{ $blog->category->name ?? 'Uncategorized' }}
                                                </a>
                                            </div>
                                            @if (Auth::check())
                                                <button class="btn p-0 btn-bookmark-blogs" data-blog-id="{{ $blog->id }}"
                                                    onclick="toggleBookmark({{ $blog->id }})">
                                                    <i
                                                        class="{{ in_array($blog->id, $bookmarkedBlogs) ? 'fas fa-bookmark text-primary' : 'far fa-bookmark ' }} fs-2"></i>
                                                </button>
                                            @else
                                                <button class="btn p-0"
                                                    onclick="window.location.href='{{ route('login') }}'">
                                                    <i class="far fa-bookmark text-muted fs-2"></i>
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Image & Title -->
                                        <div class="card-body pt-2 ps-6 pe-6 pb-5">
                                            @php
                                                $thumbnail = $blog->thumbnail
                                                    ? (Str::startsWith($blog->thumbnail, 'http')
                                                        ? $blog->thumbnail
                                                        : asset('storage/' . $blog->thumbnail))
                                                    : asset('assets/media/icons/duotune/modul/File_Explorer_Icon.webp');
                                            @endphp
                                            <a href="{{ route('single.show', $blog->slug) }}">
                                                <img src="{{ $thumbnail }}" alt="{{ $blog->title }}"
                                                    class="img-fluid rounded w-100 mb-3"
                                                    style="height: 160px; object-fit: cover;">
                                                <h5 class="fw-bold fs-3">
                                                    {{ Str::limit($blog->title, 40) }}
                                                </h5>
                                            </a>
                                            <p class="text-muted small mb-3">
                                                {{ $blog->created_at->format('M d, Y • H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

    </div>
    <!-- Load More Button -->
    <div class="d-flex justify-content-center mt-4">
        @if ($blogs->hasMorePages())
            <button id="load-more-button" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2">
                <span class="button-text">Load More</span>
                <div class="spinner-border spinner-border-sm text-light d-none" id="loading-spinner" role="status"></div>
            </button>
        @endif
    </div>




    <script>
        document.getElementById("load-more-button").addEventListener("click", function() {
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
        $(document).ready(function() {
            $(".btn-bookmark-blogs").click(function() {
                let button = $(this);
                let icon = button.find("i");
                let blogId = button.data("blog-id");
                // Ubah ikon secara instan
                if (icon.hasClass("far")) {
                    icon.removeClass("far fa-bookmark text-muted").addClass(
                        "fas fa-bookmark text-primary");
                } else {
                    icon.removeClass("fas fa-bookmark text-primary").addClass(
                        "far fa-bookmark text-muted");
                }
                $.ajax({
                    url: "/page/bookmark",
                    type: "POST",
                    data: {
                        blog_id: blogId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status === "added") {
                            icon.removeClass("far fa-bookmark text-muted").addClass(
                                "fas fa-bookmark text-primary");
                        } else {
                            icon.removeClass("fas fa-bookmark text-primary").addClass(
                                "far fa-bookmark text-muted");
                        }
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            let page = 1; // Halaman awal

            $("#load-more-button").on("click", function() {
                page++; // Tambah halaman
                let searchQuery = document.querySelector('input[name="q"]').value;
                let currentUrl = window.location.pathname;

                // Cek apakah data sudah masuk
                console.log("🔍 userRoles dari backend:", userRoles);
                console.log("🔍 Apakah user punya role yang dibutuhkan?", userHasRole(['super-admin',
                    'administrator', 'mentor', 'koordinator'
                ]));

                $.ajax({
                    url: currentUrl + "/?page=" + page + "&q=" + encodeURIComponent(searchQuery),
                    type: "GET",
                    success: function(data) {
                        console.log("✅ Data sebelum filter:", data.data);

                        // Tambahkan blog baru ke kontainer
                        data.data.forEach(function(blog) {


                            if (blog.status != 1)
                                return;

                            let userRoleAllowed = blog.special_role = 1 || (
                                isAuthenticated && userHasRole(['super-admin',
                                    'administrator', 'mentor', 'koordinator'
                                ]));
                            if (!userRoleAllowed) return;
                            let thumbnail = blog.thumbnail ?
                                (blog.thumbnail.startsWith("http") ?
                                    blog.thumbnail :
                                    "/storage/" + blog.thumbnail) :
                                "/assets/media/icons/duotune/modul/File_Explorer_Icon.webp";


                            let bookmarkIcon = blog.is_bookmarked ?
                                'fas fa-bookmark text-primary' :
                                'far fa-bookmark';

                            $("#blog-container").append(`
                          <div class="col">
                            <div class="card border border-secondary-subtle shadow-sm rounded-lg h-100 border-hover" style="cursor: pointer;">
                                <!-- Header -->
                                <div class="card-header bg-white d-flex justify-content-between align-items-center" style="border-bottom: none">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center overflow-hidden bg-light position-relative" style="width: 40px; height: 40px;" data-bs-toggle="tooltip" data-bs-placement="top" title="${
                                                blog.user?.name ?? "Author"
                                            }">
                                                ${
                                                    blog.user?.avatar
                                                        ? `<img src="/media/avatars/${
                                                                  blog.user.avatar
                                                              }" alt="${
                                                                  blog.user.name ??
                                                                  "Author"
                                                              }" class="img-fluid w-100 h-100 object-fit-cover">`
                                                        : `<span class="d-flex align-items-center justify-content-center w-100 h-100 bg-danger text-white fw-bold">${
                                                                  blog.user?.name
                                                                      ? blog.user.name
                                                                            .charAt(
                                                                                0
                                                                            )
                                                                            .toUpperCase()
                                                                      : "?"
                                                              }</span>`
                                                }
                                            </div>
                                        </div>
                                        <a href="/blogs/category/${blog.category?.slug ?? '#'}" class="ms-2 text-muted text-decoration-none hover-color">— ${blog.category?.name ?? 'Uncategorized'}</a>
                                    </div>
                                    ${isAuthenticated 
                                        ? `<button class="btn p-0 btn-bookmark" data-blog-id="${blog.id}" onclick="toggleBookmark(${blog.id})">
                                                            <i class="${bookmarkIcon} fs-2"></i>
                                                        </button>`
                                        : `<button class="btn p-0" onclick="window.location.href='/page/login'">
                                                            <i class="far fa-bookmark text-muted fs-2"></i>
                                                        </button>`}
                                </div>

                                <!-- Image & Title -->
                                <div class="card-body pt-2 ps-6 pe-6 pb-5">
                                    <a href="/${blog.slug}">
                                        <img src="${thumbnail}" alt="${blog.title}" class="img-fluid rounded w-100 mb-3" style="height: 160px; object-fit: cover;">
                                        <h5 class="fw-bold fs-3">${blog.title.length > 40 ? blog.title.substring(0, 40) + '...' : blog.title}</h5>
                                    </a>
                                    <p class="text-muted small mb-3">${new Date(blog.created_at).toLocaleString("en-US", { month: "short", day: "numeric", year: "numeric", hour: "numeric", minute: "numeric" })}</p>
                                </div>
                            </div>
                            </div>
                         `);


                        });
                        $('[data-bs-toggle="tooltip"]').tooltip();

                        if (!data.next_page_url) {
                            $("#load-more-button").hide();
                        }
                    },
                    error: function() {
                        alert("Error loading more blogs.");
                    },
                });
            });

        });
    </script>
@endsection
