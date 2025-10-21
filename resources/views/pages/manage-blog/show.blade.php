<x-app-layout>
    @section('title', 'Detail blog')
    @if ($errors->any())
        <div id="is_invalid__"></div>
    @endif
    <!--begin::Toolbar-->
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
            font-weight: 200 !important;
            font-size: 1.20rem !important;
            padding: 8px !important;
        }
    </style>

    <div id="kt_app_toolbar" class="app-toolbar pb-2" style="background-color: #f6f6f6;">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        {{ __('Detail Blog') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid" style="background-color: #f6f6f6;">
        <div id="kt_app_content_container" class="container-fluid px-0 px-md-3"> <!-- Tambah padding saat kecil -->
            <div class="card table-responsive border-0 shadow-sm">
                <div class="container-fluid px-0 px-md-3 px-sm-2"> <!-- Tambah padding saat kecil -->
                    <div class="row justify-content-center mx-0 pt-8">
                        <!-- Main Content -->
                        <div class="col-md-10 px-0 px-md-3 px-sm-2"> <!-- Tambah padding saat kecil -->
                            <div class="mx-auto" style="font-family: 'Spectral', serif;">
                                <h3 class="display-6 mb-5">
                                    {{ $blog->title ?? 'Judul Blog' }}
                                </h3>
                                <div class="d-flex align-items-center mb-5" style="font-family: Arial, sans-serif;">
                                    <div class="rounded-circle bg-secondary me-3 d-flex align-items-center justify-content-center overflow-hidden bg-danger"
                                        style="width: 40px; height: 40px;">
                                        @if ($blog->user->avatar)
                                            <img src="{{ asset('media/avatars/' . $blog->user->avatar) }}"
                                                alt="Avatar" class="w-100 h-100 object-fit-cover">
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
                                    alt="{{ Str::limit($blog->title, 50) }}"
                                    src="{{ asset('storage/' . $blog->featured_image) }}"
                                    style="width: 100% !important;" />

                                <img class="img-fluid rounded cursor-pointer d-block d-lg-none clickable-image"
                                    alt="{{ Str::limit($blog->title, 50) }}"
                                    src="{{ asset('storage/' . $blog->featured_image) }}" />
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
                                @if (preg_match('/^<pre.*?>.*?<\/pre>$/s', $content))
                                    <pre class="line-numbers prism-container mx-0 ">
                                <code id="code" class="language-javascript">{!! $content !!}</code>
                            </pre>
                                @else
                                    <div class="text-body fs-5 fw-normal lh-lg mx-0 px-3 px-md-0 prose" id="content-text">
                                        <!-- Tambah padding saat kecil -->
                                        {!! $content !!}
                                    </div>
                                @endif
                            @endforeach
                            <div class="mt-4">
                                @foreach ($blog->tags as $tag)
                                    <span
                                        class="badge fs-5 bg-light text-dark me-2 mb-3">#{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    
</x-app-layout>
