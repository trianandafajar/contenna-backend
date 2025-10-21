@extends('layouts.home', ['include' => ['nav']], ['title' => 'AllFill Documentation'])

@section('content')
    <style>
        pre {

            padding-bottom: 0px !important;
            overflow-x: auto;
            border-radius: 15px;
            white-space: pre;
            word-wrap: normal;
        }

        pre,
        code {
            display: block;
            line-height: 1.4;
        }


    .line-numbers .line-numbers-rows {
        margin-left: 10px;
        width: 2em;
        text-align: right;
        pointer-events: none;
        user-select: none;
    }
    a.badge {
    font-weight: 400 !important;
    font-size: 1.20rem !important;
    padding: 8px !important;
    border-radius: 0.25rem !important;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2) !important; /* Efek bayangan agar lebih menonjol */
    }
    .bg-badge .btn {
    color: white
    background-color: rgb(158, 162, 165) !important;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2) !important;
    }

</style>
    <div class="container px-4 py-5">
        <div class="row justify-content-center">
            <!-- Main Content -->
            <div class="col-md-8">
                <div class="mx-auto" style="font-family: 'Spectral', serif;">
                    <h3 class="display-6  mb-5">
                        {{ $blog->title ?? 'Judul Blog' }}
                    </h3>

                    <div class="d-flex align-items-center mb-5" style="font-family: Arial, sans-serif;">
                        <div class="rounded-circle bg-secondary me-3 d-flex align-items-center justify-content-center overflow-hidden bg-danger"
                            style="width: 40px; height: 40px;">
                            @if ($blog->user->avatar)
                                <img src="{{ asset('media/avatars/' . $blog->user->avatar) }}" alt="Avatar"
                                    class="w-100 h-100 object-fit-cover">
                            @else
                                <span class="text-white fw-bold fs-5">
                                    {{ strtoupper(substr($blog->user->name ?? 'A', 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="fw-bold mb-0 fs-5">{{ $blog->user->name ?? 'Unknown Author' }}</p>
                            <p class="text-muted mb-0 fs-6">{{ $blog->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>


                @if ($blog->featured_image)
                    <img class="img-fluid rounded cursor-pointer d-none d-lg-block mb-6 clickable-image"
                        alt="{{ Str::limit($blog->title, 50) }}" src="{{ asset('storage/' . $blog->featured_image) }}"
                        style="width: 100% !important;" />

                    <img class="img-fluid rounded cursor-pointer d-block d-lg-none clickable-image"
                        alt="{{ Str::limit($blog->title, 50) }}" src="{{ asset('storage/' . $blog->featured_image) }}" />
                    <!-- Modal -->
                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content bg-transparent border-0">
                                <div class="modal-body text-center p-0">
                                    <img id="modalImg" class="img-fluid cursor-pointer" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @foreach ($splitContent as $content)
                    <div class="container">
                        @if (preg_match('/^<pre.*?>.*?<\/pre>$/s', $content))
                            <pre class="line-numbers prism-container">
                                <code class="language-javascript">{!! $content !!}</code>
                            </pre>
                        @else
                            <div class="text-body fs-5 fw-normal lh-lg" id="content-text">
                                {!! $content !!}
                            </div>
                        @endif
                    </div>
                @endforeach


                <!-- Tags -->
                <div class="mt-4">
                    @foreach ($blog->tags as $tag)
                        <a href="{{ url('blogs/?tag='.$tag->slug) }}"
                            class="badge btn btn-secondary fs-5 btn-secondary me-2 mb-3">#{{ $tag->name }}</a>
                    @endforeach
                </div>

                <!-- Share Section -->
                <div class="bg-badge mt-3 d-flex gap-2">
                    <button onclick="shareToFacebook()" class="btn btn-secondary">
                        <i class="fab fa-facebook-f fs-3"></i>
                    </button>
                    <button onclick="shareToTwitter()" class="btn btn-secondary">
                        <i class="fab fa-twitter fs-3"></i>
                    </button>
                    <button onclick="shareToWhatsApp()" class="btn btn-secondary">
                        <i class="fab fa-whatsapp fs-3"></i>
                    </button>
                    <button onclick="shareToDiscord()" class="btn btn-secondary">
                        <i class="fab fa-discord fs-3"></i>
                    </button>
                    <button onclick="sharePage()" class="btn btn-secondary">
                        <i class="fas fa-share fs-3"></i> Share
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle-none" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="tabler-icon tabler-icon-dots-vertical transform rotate-90">
                                <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                            </svg>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#" onclick="copyUrl()">Copy URL</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function shareToFacebook() {
            window.open(`https://www.facebook.com/sharer/sharer.php?u={{ config('app.url') }}{{ $blog->slug }}`,
                '_blank');
        }

        function shareToTwitter() {
            window.open(`https://twitter.com/intent/tweet?url={{ config('app.url') }}{{ $blog->slug }}`, '_blank');
        }

        function shareToWhatsApp() {
            window.open(`https://api.whatsapp.com/send?text={{ config('app.url') }}{{ $blog->slug }}`, '_blank');
        }

        function shareToDiscord() {
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

        function copyUrl() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert("URL copied to clipboard!");
            }).catch(err => {
                console.error("Failed to copy: ", err);
            });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("pre code").forEach((block) => {
                // Tambahkan class Prism.js secara otomatis jika belum ada
                if (!block.classList.contains("language-javascript")) {
                    block.classList.add("language-javascript"); // Ubah sesuai bahasa yang diperlukan
                }
            });

            // Jalankan Prism.js setelah halaman selesai dimuat
            Prism.highlightAll();
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil semua gambar yang bisa diklik
            const images = document.querySelectorAll(
                ".clickable-image"); // Tambahkan class ini ke gambar yang bisa diperbesar
            const modalImg = document.getElementById("modalImg");
            const imageModal = new bootstrap.Modal(document.getElementById("imageModal"));

            // Tambahkan event listener ke setiap gambar
            images.forEach(img => {
                img.addEventListener("click", function() {
                    modalImg.src = this.src; // Set gambar modal sesuai dengan yang diklik
                    imageModal.show(); // Tampilkan modal
                });
            });
        });
    </script>
@endsection
