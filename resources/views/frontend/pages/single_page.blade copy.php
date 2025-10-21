@extends('layouts.home', ['include' => ['nav']], ['title' => 'AllFill Documentation'])

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="gap-6">
            <!-- Main Content -->
            <div class="w-full md:w-3/5 mx-auto">
                <div class="max-w-4xl mx-auto" style="font-family: 'SF Pro Display', sans-serif;">
                    <h1 class="text-4xl font-bold mb-8">
                        {{ $blog->title ?? 'Judul Blog' }}
                    </h1>

                    <div class="flex items-center mb-6">
                        @if ($blog->user->avatar)
                            <div class="w-9 h-9 rounded-full bg-gray-300 mr-3"></div>
                        @else
                            <div
                                class="flex items-center justify-center w-9 h-9 rounded-full mr-3 bg-red-500 text-white text-xl font-semibold">
                                {{ strtoupper(substr($blog->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-sm">
                                {{ $blog->user->name ?? 'Unknown Author' }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                {{ $blog->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                </div>

                <img alt="{{ Str::limit($blog->title, 50) }}"
                    class="badag mx-auto mb-6 rounded-lg cursor-pointer hidden lg:block"
                    src="{{ $blog->featured_image
                        ? asset('storage/' . $blog->featured_image)
                        : asset('assets/media/icons/duotune/modul/File_Explorer_Icon.webp') }}"
                    width="100%" height="100%" />

                <img alt="{{ Str::limit($blog->title, 50) }}"
                    class="badag w-full h-auto mx-auto mb-2 rounded-lg cursor-pointer block lg:hidden"
                    src="{{ $blog->featured_image
                        ? asset('storage/' . $blog->featured_image)
                        : asset('assets/media/icons/duotune/modul/File_Explorer_Icon.webp') }}" />

                <!-- Modal -->
                <div id="imageModal" style="z-index: 1000;"
                    class="fixed inset-0 bg-black bg-opacity-75 hidden flex justify-center items-center">
                    <div class="relative w-11/12 max-w-2xl h-11/12 max-h-full">
                        <div class="hidden-scrollbar max-h-full" style="width: 100%;">
                            <img class="modal-content max-w-full max-h-full object-contain cursor-pointer" id="modalImg" />
                        </div>
                    </div>
                </div>

                @foreach ($splitContent as $content)
                    @if (preg_match('/^<pre.*?>.*?<\/pre>$/s', $content))
                        <pre class="line-numbers prism-container">
                            <code class="language-javascript">
                                {!! $content !!}
                            </code>
                        </pre>
                    @else
                        <div class="max-w text-[19px] font-normal leading-[30px] font-[Spectral" id="content-text">
                            {!! $content !!}
                        </div>
                    @endif
                @endforeach


                <div class="mt-6">
                    <!-- Tags Section -->
                    <div class="flex items-center flex-wrap mb-5">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($blog->tags as $tag)
                                <a href="{{ route('tag.show', $tag->slug) }}"
                                    class="bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200 transition">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Share Section -->
                    <div class="flex items-center flex-wrap">
                        <div class="flex flex-wrap gap-2">
                            <!-- Facebook Share -->
                            <button onclick="shareToFacebook()"
                                class="bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 transition flex items-center justify-center w-14 h-10">
                                <i class="fab fa-facebook-f text-xl flex items-center justify-center"></i>
                            </button>

                            <!-- Twitter (X) Share -->
                            <button onclick="shareToTwitter()"
                                class="bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 transition flex items-center justify-center w-14 h-10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="tabler-icon tabler-icon-brand-x">
                                    <path d="M4 4l11.733 16h4.267l-11.733 -16z"></path>
                                    <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path>
                                </svg>
                            </button>

                            <!-- WhatsApp Share -->
                            <button onclick="shareToWhatsApp()"
                                class="bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 transition flex items-center justify-center w-14 h-10">
                                <i class="fab fa-whatsapp text-xl flex items-center justify-center"></i>
                            </button>

                            <!-- Discord Share -->
                            <button onclick="shareToDiscord()"
                                class="bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 transition flex items-center justify-center w-14 h-10">
                                <i class="fab fa-discord text-xl flex items-center justify-center"></i>
                            </button>

                            <!-- Share Button -->
                            <button onclick="sharePage()"
                                class="bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 transition flex items-center justify-center w-30 h-10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="tabler-icon tabler-icon-share-3">
                                    <path
                                        d="M13 4v4c-6.575 1.028 -9.02 6.788 -10 12c-.037 .206 5.384 -5.962 10 -6v4l8 -7l-8 -7z">
                                    </path>
                                </svg> <span class="ml-2">Share</span>
                            </button>

                            <div class="relative flex items-center justify-center">

                                <button onclick="toggleMenu(event)"
                                    class="bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 transition flex items-center justify-center w-14 h-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="tabler-icon tabler-icon-dots-vertical transform rotate-90">
                                        <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                        <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                        <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                    </svg>
                                </button>
                                <div id="menu"
                                    class="hidden absolute right-0 bg-white shadow-md p-1 rounded-md mt-20 min-w-[120px]">
                                    <p class="cursor-pointer px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded"
                                        onclick="copyUrl()">Copy URL</p>
                                    <p class="cursor-pointer px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                        Report</p>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>



    </div>



    <script>
        function shareToFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.facebook.com/sharer/sharer.php?u={{ config('app.url') }}{{ $blog->slug }}`,
                '_blank');
        }

        function shareToTwitter() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://twitter.com/intent/tweet?url=${url}`, '_blank');
        }

        function shareToWhatsApp() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://api.whatsapp.com/send?text={{ config('app.url') }}{{ $blog->slug }}`, '_blank');
        }

        function shareToDiscord() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://discord.com/share?url={{ config('app.url') }}{{ $blog->slug }}`, '_blank');
        }

        function sharePage() {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                }).catch(console.error);
            } else {
                alert("Browser tidak mendukung fitur share.");
            }
        }

        function toggleMenu(event) {
            event.stopPropagation(); // Mencegah klik dari menutup menu langsung
            const menu = document.getElementById("menu");
            menu.classList.toggle("hidden");

            // Jika menu ditampilkan, tambahkan event listener untuk menutupnya saat klik di luar
            if (!menu.classList.contains("hidden")) {
                document.addEventListener("click", closeMenuOutside);
            } else {
                document.removeEventListener("click", closeMenuOutside);
            }
        }

        function closeMenuOutside(event) {
            const menu = document.getElementById("menu");
            if (!menu.contains(event.target) && !event.target.closest("button")) {
                menu.classList.add("hidden");
                document.removeEventListener("click", closeMenuOutside);
            }
        }

        function copyUrl() {
            navigator.clipboard.writeText(window.location.href);
            alert("URL copied!");
        }
    </script>

    <script>
        const images = document.querySelectorAll('img.cursor-pointer');

        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImg');

        images.forEach(image => {
            image.addEventListener('click', function() {
                modal.classList.remove('hidden');
                modalImg.src = this.src;
                disableScrolling();
            });
        });

        modalImg.addEventListener('click', function() {
            modal.classList.add('hidden');
            enableScrolling();
        });

        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
                enableScrolling();
            }
        });

        function disableScrolling() {
            document.body.style.overflow = 'hidden';
        }

        function enableScrolling() {
            document.body.style.overflow = 'auto';
        }


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

    <script>
        var PLUGIN_NAME = 'line-numbers';
    </script>
    <script type="text/javascript">
        Prism.plugins.NormalizeWhitespace.setDefaults({
            'remove-trailing': true,
            'remove-indent': true,
            'left-trim': true,
            'right-trim': true,
        });
    </script>
@endsection
