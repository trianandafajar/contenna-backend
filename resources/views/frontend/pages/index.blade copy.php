@extends('layouts.home', ['include' => ['hero', 'tag-menu']], ['title' => 'AllFill Documentation'])

@section('content')
    {{-- <input type="hidden" id="search-query" value="{{ request('q') }}"> --}}

    <div id="blog-container" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 mt-12">
        @foreach ($blogs as $blog)
            @if ($blog->status == 1)
                @if (
                    $blog->special_role == 1 ||
                        (Auth::check() && Auth::user()->hasAnyRole(['super-admin', 'administrator', 'mentor', 'koordinator'])))
                    <div class="max-w-72 mx-auto p-4 bg-white shadow-lg rounded-lg border border-gray-200 w-72 min-h-[24rem] hover:border-gray-400"
                        style="height: auto;">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="relative group">
                                    <div
                                        class="rounded-full w-10 h-10 flex items-center justify-center overflow-hidden bg-gray-200">
                                        @if ($blog->user->avatar)
                                            <img src="{{ URL::asset('media/avatars/' . $blog->user->avatar) }}"
                                                alt="{{ $blog->user->name ?? 'Author' }}"
                                                class="w-full h-full object-cover" />
                                        @else
                                            <span
                                                class="w-10 h-10 flex items-center justify-center bg-red-500 text-white font-bold text-sm">
                                                {{ strtoupper(substr($blog->user->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <span
                                        class="absolute bottom-[-30px] left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 w-max max-w-[120px] truncate">
                                        {{ Str::limit($blog->user->name ?? 'Unknown', 15) }}
                                    </span>
                                </div>

                                <a href="{{ route('category.filter', $blog->category->slug ?? '#') }}"
                                    class="ml-2 text-gray-500 hover:text-blue-600 transition duration-300">
                                    — {{ $blog->category->name ?? 'Uncategorized' }}
                                </a>

                            </div>
                            @if (Auth::check())
                                <button class="btn-bookmark" data-blog-id="{{ $blog->id }}"
                                    onclick="toggleBookmark({{ $blog->id }})">
                                    <i
                                        class="{{ in_array($blog->id, $bookmarkedBlogs) ? 'fas fa-bookmark text-blue-500' : 'far fa-bookmark text-gray-500' }} text-2xl"></i>
                                </button>
                            @else
                                <button class="btn-bookmark" onclick="window.location.href='{{ route('login') }}'">
                                    <i class="far fa-bookmark text-gray-500 text-2xl"></i>
                                </button>
                            @endif

                        </div>

                        <!-- Main Image and Title -->
                        <div class="mb-4">
                            @php
                                $thumbnail = $blog->thumbnail
                                    ? (Str::startsWith($blog->thumbnail, 'http')
                                        ? $blog->thumbnail
                                        : asset('storage/' . $blog->thumbnail))
                                    : asset('assets/media/icons/duotune/modul/File_Explorer_Icon.webp');
                            @endphp

                            <a href="{{ route('single.show', $blog->slug) }}">
                                <img src="{{ $thumbnail }}" alt="{{ $blog->title }}"
                                    class="w-full h-40 object-cover rounded-lg hover:opacity-90 transition duration-300">



                                <!-- Article Title -->
                                <h1 class="text-lg font-bold mb-2">
                                    {{ Str::limit($blog->title, 40) }}
                                    @if (strlen($blog->title) < 29)
                                        <br><br>
                                    @endif
                                </h1>

                                <!-- Article Meta -->
                                <div class="flex items-start text-sm text-gray-500 mb-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-gray-500 text-xs">{{ $blog->created_at->format('M d, Y • H:i') }}</span>
                                    </div>
                                </div>

                            </a>
                        </div>


                        <!-- Hashtags -->
                        <div class="flex flex-wrap">
                            @foreach ($blog->tags->take(5) as $tag)
                                <a href="{{ route('tag.show', $tag->slug) }}"
                                    class="bg-gray-200 text-gray-700 text-xs font-medium mr-2 mb-2 px-2.5 py-0.5 rounded hover:bg-gray-300 transition">
                                    #{{ Str::limit($tag->name, 10) }}
                                </a>
                            @endforeach

                            @if ($blog->tags->count() > 5)
                                <span
                                    class="tooltip-trigger relative bg-gray-200 text-gray-700 text-xs font-medium mr-2 mb-2 px-2.5 py-0.5 rounded cursor-pointer"
                                    data-tooltip-id="tooltip-{{ $blog->id }}">
                                    +{{ $blog->tags->count() - 5 }} more
                                    <div id="tooltip-{{ $blog->id }}"
                                        class="tooltip-content absolute left-1/2 transform -translate-x-1/2 -top-8 bg-black text-white text-xs px-3 py-1 rounded shadow-md w-max opacity-0 invisible transition-opacity duration-300">
                                        <div class="grid grid-cols-2 gap-1">
                                            @foreach ($blog->tags->slice(5) as $tag)
                                                <a href="{{ route('tag.show', $tag->slug) }}"
                                                    class="block text-blue-300 hover:text-blue-500 transition px-1">
                                                    #{{ $tag->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </span>
                            @endif

                        </div>

                    </div>
                @endif
            @endif
        @endforeach
    </div>

    <div class="flex justify-center mt-6">
        @if ($blogs->hasMorePages())
            <button id="load-more"
                class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg flex items-center justify-center gap-2 transition duration-300 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="button-text">Load More</span>
                <div
                    class="loading-spinner hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin">
                </div>
            </button>
        @endif
    </div>


    <script>
        // Load More JS Animate
        document.getElementById("load-more").addEventListener("click", function() {
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
        let isAuthenticated = @json(Auth::check());
    </script>
@endsection
