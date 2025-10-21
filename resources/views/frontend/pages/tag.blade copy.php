@extends('layouts.home', ['include' => ['nav']], ['title' => 'AllFill Documentation'])

@section('content')
    <div class="container mx-auto max-w-screen-xl px-4 py-8">
        <header class="bg-gray-50 py-6 px-4 text-center rounded-lg shadow-sm">
            @if (isset($tag))
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800">#{{ $tag->name }}</h1>
                <p class="text-gray-500 mt-2 text-sm md:text-base">{{ $blogs->count() }} post dengan tag
                    "{{ $tag->name }}"</p>
            @else
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Hasil Filter</h1>
                <p class="text-gray-500 mt-2 text-sm md:text-base">{{ $blogs->count() }} post ditemukan</p>
            @endif
        </header>


        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-8">
            <!-- Sidebar Column -->
            @include('frontend.components.searchbar')

            <!-- Content Column -->
            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach ($blogs as $blog)
                    <div class="max-w-xs mx-auto p-3 bg-white shadow-md rounded-lg border border-gray-200 w-full hover:border-gray-400"
                        style="height: 20rem">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="relative group">
                                    <div
                                        class="rounded-full w-8 h-8 flex items-center justify-center overflow-hidden bg-gray-200">
                                        @if ($blog->user->avatar)
                                            <img src="{{ URL::asset('media/avatars/' . $blog->user->avatar) }}"
                                                alt="{{ $blog->user->name ?? 'Author' }}"
                                                class="w-full h-full object-cover" />
                                        @else
                                            <span
                                                class="w-8 h-8 flex items-center justify-center bg-red-500 text-white font-bold text-xs">
                                                {{ strtoupper(substr($blog->user->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <span
                                        class="absolute bottom-[-25px] left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 w-max max-w-[100px] truncate">
                                        {{ Str::limit($blog->user->name ?? 'Unknown', 15) }}
                                    </span>
                                </div>
                                <a href="{{ route('category.filter', $blog->category->slug ?? '#') }}"
                                    class="ml-2 text-gray-500" style="font-size: 0.75rem;">
                                    — {{ Str::limit($blog->category->name ?? 'Uncategorized', 16) }}
                                </a>
                            </div>
                            @if (Auth::check())
                                <button class="btn-bookmark" data-blog-id="{{ $blog->id }}"
                                    onclick="toggleBookmark({{ $blog->id }})">
                                    <i
                                        class="{{ in_array($blog->id, $bookmarkedBlogs) ? 'fas fa-bookmark text-blue-500' : 'far fa-bookmark text-gray-500' }} text-xl"></i>
                                </button>
                            @else
                                <button class="btn-bookmark" onclick="window.location.href='{{ route('login') }}'">
                                    <i class="far fa-bookmark text-gray-500 text-xl"></i>
                                </button>
                            @endif
                        </div>

                        <!-- Main Image and Title -->
                        <div class="mb-3">
                            @php
                                $thumbnail = $blog->thumbnail
                                    ? (Str::startsWith($blog->thumbnail, 'http')
                                        ? $blog->thumbnail
                                        : asset('storage/' . $blog->thumbnail))
                                    : asset('assets/media/icons/duotune/modul/File_Explorer_Icon.webp');
                            @endphp
                            <a href="{{ route('single.show', $blog->slug) }}">
                                <img src="{{ $thumbnail }}" alt="{{ $blog->title }}"
                                    class="object-cover rounded-lg hover:opacity-90 transition duration-300"
                                    style="width: 100%; height: 10rem;">

                        </div>

                        <!-- Article Title -->
                        <h1 class="text-xs font-bold min-h-[3rem]">
                            {{ Str::limit($blog->title, 60) }}
                        </h1>

                        <!-- Article Meta -->
                        <div class="flex items-start text-xs text-gray-500">
                            <span class="text-gray-500">{{ $blog->created_at->format('M d, Y • H:i') }}</span>
                        </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- <!-- Recommended Column (Optional) -->
            <div class="lg:col-span-1 hidden md:block">
                @include('frontend.components.recomended')
            </div> --}}
        </div>
    </div>
        
    
    <script>
        // Load More JS Animate
        document.getElementById("load-more-tag").addEventListener("click", function() {
            const button = this;
            const spinner = button.querySelector(".loading-spinner");
            button.disabled = true;
            spinner.classList.remove("hidden");

            setTimeout(() => {
                button.disabled = false;
                spinner.classList.add("hidden");
            }, 1000);
        });
    </script>
    <script>
        var PLUGIN_NAME = 'line-numbers';
    </script>
    {{-- menambahkan load more  --}}
    <script>
        $(document).ready(function() {
            let page = 1; // Halaman awal

            $("#load-more-tag").on("click", function() {
                page++;
                //cek url saat ini
                let currentUrl = window.location.pathname;
                $.ajax({
                    url: currentUrl + "/?page=" + page + "&q=",
                    type: "GET",
                    success: function(data) {


                        data.data.forEach(function(blog) {
                            let thumbnail = blog.thumbnail ?
                                (blog.thumbnail.startsWith("http") ? blog.thumbnail :
                                    "/storage/" + blog.thumbnail) :
                                "/assets/media/icons/duotune/modul/File_Explorer_Icon.webp";

                            $("#blog-container").append(`
                        <div class="max-w-auto mx-auto p-4 bg-white shadow-lg rounded-lg border border-gray-200 w-auto hover:border-gray-400" style="height: 26rem">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="relative group">
                                        <div class="rounded-full w-10 h-10 flex items-center justify-center overflow-hidden bg-gray-200">
                                            ${blog.user && blog.user.avatar
                                                ? `<img src="/media/avatars/${blog.user.avatar}" alt="${blog.user.name ?? "Author"}" class="w-full h-full object-cover">`
                                                : `<span class="w-10 h-10 flex items-center justify-center bg-red-500 text-white font-bold text-sm">${blog.user && blog.user.name ? blog.user.name.charAt(0).toUpperCase() : "?"}</span>`
                                            }
                                        </div>
                                        <span class="absolute bottom-[-30px] left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 w-max max-w-[120px] truncate">
                                            ${blog.user?.name ?? "Unknown"}
                                        </span>
                                    </div>
                                    <a href="/blogs/category/${blog.category?.slug ?? "#"}" class="ml-2 text-gray-500 hover:text-blue-600 hover:underline transition duration-300">
                                        — ${blog.category?.name ?? "Uncategorized"}
                                    </a>
                                </div>
                                <button class="btn-bookmark" data-blog-id="${blog.id}">
                                    <i class="${blog.is_bookmarked ? "fas fa-bookmark text-blue-500" : "far fa-bookmark text-gray-500"} text-2xl"></i>
                                </button>
                            </div>
                            <!-- Main Image and Title -->
                            <div class="mb-4">
                                <a href="/${blog.slug}">
                                    <img src="${thumbnail}" alt="${blog.title}" class="object-cover rounded-lg hover:opacity-90 transition duration-300" style="width: 23rem; height: 13rem;">
                                </a>
                            </div>
                            <!-- Article Title -->
                            <h1 class="text-lg font-bold mb-2 min-h-[4rem]">
                                ${blog.title.length > 70 ? blog.title.substring(0, 70) + "..." : blog.title}
                            </h1>
                            <!-- Article Meta -->
                            <div class="flex items-start text-sm text-gray-500 mb-4">
                                <div class="flex flex-col">
                                    <span class="text-gray-500 text-xs">${new Date(blog.created_at).toLocaleString("en-US", {
                                        month: "short",
                                        day: "numeric",
                                        year: "numeric",
                                        hour: "numeric",
                                        minute: "numeric",
                                    })}</span>
                                </div>
                            </div>
                        </div>
                    `);
                        });

                        // Jika tidak ada halaman lagi, sembunyikan tombol
                        if (!data.next_page_url) {
                            $("#load-more-tag").hide();
                        }
                    },
                    error: function() {
                        alert("Error loading more blogs.");
                    },
                });
            });
        });
    </script>
    <script>
        let isAuthenticated = @json(Auth::check());
    </script>
    </body>

    </html>
@endsection
