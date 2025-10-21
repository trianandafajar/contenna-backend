@extends('layouts.home', ['include' => ['nav']], ['title' => 'AllFill Documentation'])

@section('content')
    <div class="container mx-auto max-w-screen-xl px-4 py-8">
        <header class="bg-gray-50 py-6 px-4 text-center rounded-lg shadow-sm">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">#{{ $category->name }}</h1>
            <p class="text-gray-500 mt-2 text-sm md:text-base">{{ $blogs->total() }} post dalam kategori
                "{{ $category->name }}"</p>
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

            {{-- Jika diperlukan, tambahkan kolom rekomendasi di sini (opsional) --}}
            {{-- <div class="lg:col-span-1 hidden md:block">
            @include('frontend.components.recomended')
        </div> --}}
        </div>
    </div>

    <script>
        var PLUGIN_NAME = 'line-numbers';
    </script>
    <script>
        $(document).ready(function() {
            $(".btn-bookmark").click(function() {
                let button = $(this);
                let icon = button.find("i");
                let blogId = button.data("blog-id");

                // Ubah ikon secara instan
                if (icon.hasClass("far")) {
                    icon.removeClass("far fa-bookmark text-gray-500").addClass(
                        "fas fa-bookmark text-blue-500");
                } else {
                    icon.removeClass("fas fa-bookmark text-blue-500").addClass(
                        "far fa-bookmark text-gray-500");
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
                            icon.removeClass("far fa-bookmark text-gray-500").addClass(
                                "fas fa-bookmark text-blue-500");
                        } else {
                            icon.removeClass("fas fa-bookmark text-blue-500").addClass(
                                "far fa-bookmark text-gray-500");
                        }
                    }
                });
            });
        });
    </script>
@endsection
