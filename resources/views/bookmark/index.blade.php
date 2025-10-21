<x-app-layout>
    @section('title', 'Bookmark')
    @if ($errors->any())
        <div id="is_invalid__"></div>
    @endif
    <div>
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pb-2" style="background-color: #f6f6f6;">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            {{ __('List Bookmark') }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        @if ($bookmarkedBlogs->isNotEmpty())
        <div id="blog-container" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 mt-12 ">
            @foreach ($bookmarkedBlogs as $blog)
                @if ($blog->status == 1)
                    @if (
                        $blog->special_role == 1 ||
                            (Auth::check() && Auth::user()->hasAnyRole(['super-admin', 'administrator', 'mentor', 'koordinator'])))
                        <div class="max-w-80 mx-auto p-4 bg-white shadow-md rounded-lg border border-gray-200 w-80 min-h-[24rem]"
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
    
                                    <a href="{{ url('blogs/?category='.$blog->category->slug) }}"
                                        class="ml-2 text-gray-500 hover:text-blue-600 hover:underline transition duration-300">
                                        — {{ $blog->category->name ?? 'Uncategorized' }}
                                    </a>
    
                                </div>
                                <!-- Tombol Delete Bookmark -->
                                <form action="{{ route('bookmark.delete') }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                    <button type="submit" class="btn-delete-bookmark">
                                        <i class="fas fa-trash text-red-500 hover:text-red-700 transition duration-300"></i>
                                    </button>
                                </form>
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
                                </a>
                            </div>
    
    
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
    
                            <!-- Hashtags -->
                            <div class="flex flex-wrap">
                                @foreach ($blog->tags->take(5) as $tag)
                                    <a href="{{ url('blogs/?tag='.$tag->slug) }}"
                                        class="bg-gray-200 text-gray-700 text-xs font-medium mr-2 mb-2 px-2.5 py-0.5 rounded hover:bg-gray-300 transition">
                                        #{{ Str::limit($tag->name, 10) }}
                                    </a>
                                @endforeach
    
                                @if ($blog->tags->count() > 5)
                                    @php
                                        $tooltipId = 'tooltip-' . $blog->id;
                                        $triggerId = 'trigger-' . $blog->id;
                                    @endphp
    
                                    <span id="{{ $triggerId }}"
                                        class="relative bg-gray-200 text-gray-700 text-xs font-medium mr-2 mb-2 px-2.5 py-0.5 rounded cursor-pointer">
                                        +{{ $blog->tags->count() - 5 }} more
                                        <div id="{{ $tooltipId }}"
                                            class="absolute left-1/2 transform -translate-x-1/2 -top-8 bg-black text-white text-xs px-3 py-1 rounded shadow-md 
                w-max opacity-0 invisible transition-opacity duration-300">
                                            <div class="grid grid-cols-2 gap-1">
                                                @foreach ($blog->tags->slice(5) as $tag)
                                                    <a href="{{ route('single.show', $blog->slug) }}"
                                                        class="block text-blue-300 hover:text-blue-500 transition px-1">
                                                        #{{ $tag->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
    
                                    </span>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            const trigger = document.getElementById("{{ $triggerId }}");
                                            const tooltip = document.getElementById("{{ $tooltipId }}");
    
                                            trigger.addEventListener("click", function(event) {
                                                event.stopPropagation(); // Mencegah event bubbling
                                                document.querySelectorAll('.tooltip-content').forEach(el => {
                                                    if (el !== tooltip) {
                                                        el.classList.add("opacity-0", "invisible");
                                                    }
                                                });
    
                                                tooltip.classList.toggle("opacity-0");
                                                tooltip.classList.toggle("invisible");
                                            });
    
                                            document.addEventListener("click", function(event) {
                                                if (!trigger.contains(event.target) && !tooltip.contains(event.target)) {
                                                    tooltip.classList.add("opacity-0", "invisible");
                                                }
                                            });
                                        });
                                    </script>
                                @endif
    
    
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-lg text-center">No bookmarked blogs found</p>
        @endif
        <div class="pagination float-end mt-8 me-3">
            {{ $bookmarkedBlogs->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('load-more')?.addEventListener('click', function() {
                let button = this;
                let nextPageUrl = button.getAttribute('data-next-page');

                if (!nextPageUrl) return;

                button.innerText = 'Loading...';
                fetch(nextPageUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(data => {
                        let newBlogs = (new DOMParser()).parseFromString(data, 'text/html')
                            .querySelector('#blog-list').innerHTML;
                        document.getElementById('blog-list').insertAdjacentHTML('beforeend', newBlogs);

                        // Update Load More button
                        let newButton = (new DOMParser()).parseFromString(data, 'text/html')
                            .querySelector('#load-more');
                        if (newButton) {
                            button.setAttribute('data-next-page', newButton.getAttribute(
                                'data-next-page'));
                            button.innerText = 'Load More';
                        } else {
                            button.remove();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.innerText = 'Load More';
                    });
            });

            const trigger = document.getElementById("tooltip-trigger");
            const tooltip = document.getElementById("tooltip-content");

            trigger.addEventListener("click", function(event) {
                event.stopPropagation(); // Mencegah event bubbling
                if (tooltip.classList.contains("opacity-0")) {
                    tooltip.classList.remove("opacity-0", "invisible");
                } else {
                    tooltip.classList.add("opacity-0", "invisible");
                }
            });

            // Klik di luar elemen akan menutup tooltip
            document.addEventListener("click", function(event) {
                if (!trigger.contains(event.target)) {
                    tooltip.classList.add("opacity-0", "invisible");
                }
            });

        });
    </script>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const trigger = document.getElementById("{{ $triggerId }}");
            const tooltip = document.getElementById("{{ $tooltipId }}");

            trigger.addEventListener("click", function(event) {
                event.stopPropagation(); // Mencegah event bubbling
                document.querySelectorAll('.tooltip-content').forEach(el => {
                    if (el !== tooltip) {
                        el.classList.add("opacity-0", "invisible");
                    }
                });

                tooltip.classList.toggle("opacity-0");
                tooltip.classList.toggle("invisible");
            });

            document.addEventListener("click", function(event) {
                if (!trigger.contains(event.target) && !tooltip.contains(event.target)) {
                    tooltip.classList.add("opacity-0", "invisible");
                }
            });
        });
    </script> --}}

</x-app-layout>
