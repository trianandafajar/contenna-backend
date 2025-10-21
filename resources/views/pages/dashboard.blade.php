<x-app-layout>
    @section('title', 'Dashboard')
    @if ($errors->any())
        <div id="is_invalid__"></div>
    @endif
    <div class="row xl:gap-0 lg:gap-1 md:gap-3 ps-5 ">
        <div class="row mb-2 xl:mb-0 lg:mb-0 md:mb-0" style="">
            <div class="col-md-12 col-xl-12 col-xxl-12">
                <a href="#" class="card bgi-no-repeat h-xl-100"
                    style="height: 100px!important; background-image: url('{{ asset('assets/media/misc/bg-2.jpg') }}');background-color: black; background-size: contain; background-position: center; padding-bottom: 5px; background-position: calc(100% + 1rem) bottom; background-size: 50% auto;">
                    <div class="card-body d-flex flex-column align-items-start justify-content-center">
                        <h3 class="text-white fw-bold mb-3">DocuVerse</h3>
                        <p class="text-white fs-7">Aplikasi Dokumentasi Untuk Komunitas</p>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row g-xl-6 mt-0">
            @role('super-admin')
            <div class="col-xl mb-3">
                <!--begin::Statistics Widget 5-->
                <a href="{{ route('manage-blog.index') }}" class="card hoverable card-xl-stretch mb-xl-8"
                    style="background: linear-gradient(320deg, #FF7B02, #FFCB52);">
                    <!--begin::Body-->
                    <div class="card-body">
                        <i class="ki-outline ki-document text-gray-100 fs-2x ms-n1"></i>
                        <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $blogCount }}</div>
                        <div class="fw-semibold text-white">Total Blog</div>
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
            <div class="col-xl mb-3">
                <!--begin::Statistics Widget 5-->
                <a href="{{ route('resources.users.index') }}" class="card hoverable card-xl-stretch mb-5 mb-xl-8"
                    style="background: linear-gradient(320deg, #1153FC, #5581F1);">
                    <!--begin::Body-->
                    <div class="card-body">
                        <i class="ki-outline ki-user text-white fs-2x ms-n1"></i>
                        <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $userCount }}</div>
                        <div class="fw-semibold text-white">Total Member</div>
                    </div>
                    <!--end::Body-->
                </a>    
                <!--end::Statistics Widget 5-->
            </div>
            @endrole
        </div>


        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 mt-6 pe-10 ">
            @foreach ($bookmarkedBlogs as $blog)
                @if ($blog->status == 1)
                    @if ($blog->special_role == 1 || (Auth::check() && Auth::user()->hasAnyRole(['super-admin', 'administrator', 'mentor', 'koordinator'])))
                        <div class=" w-96 md:w-[22rem] mx-auto p-4 bg-white shadow-md rounded-lg border border-gray-200 min-h-[24rem] hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="relative group">
                                        <div class="rounded-full w-10 h-10 flex items-center justify-center overflow-hidden bg-gray-200">
                                            @if ($blog->user->avatar)
                                                <img src="{{ URL::asset('media/avatars/' . $blog->user->avatar) }}" alt="{{ $blog->user->name ?? 'Author' }}" class="w-full h-full object-cover" />
                                            @else
                                                <span class="w-10 h-10 flex items-center justify-center bg-red-500 text-white font-bold text-sm">
                                                    {{ strtoupper(substr($blog->user->name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="absolute bottom-[-30px] left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 w-max max-w-[120px] truncate">
                                            {{ Str::limit($blog->user->name ?? 'Unknown', 15) }}
                                        </span>
                                    </div>
                                    <a href="{{ url('blogs/?category='.$blog->category->slug) }}" class="ml-2 text-gray-500 hover:text-blue-600 hover:underline transition duration-300">
                                        — {{ $blog->category->name ?? 'Uncategorized' }}
                                    </a>
                                </div>
                                <!-- Tombol Delete Bookmark -->
                                <form action="{{ route('bookmark.delete') }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                    <button type="submit" class="btn-delete-bookmark">
                                        <i class="fas fa-trash text-red-500 hover:text-red-700 transition duration-300 text-lg"></i>
                                    </button>
                                </form>
                            </div>
        
                            <!-- Main Image and Title -->
                            <div class="mb-4">
                                @php
                                    $thumbnail = $blog->thumbnail
                                        ? (Str::startsWith($blog->thumbnail, 'http') ? $blog->thumbnail : asset('storage/' . $blog->thumbnail))
                                        : asset('assets/media/icons/duotune/modul/File_Explorer_Icon.webp');
                                @endphp
        
                                <a href="{{ route('single.show', $blog->slug) }}" class="block overflow-hidden rounded-lg">
                                    <img src="{{ $thumbnail }}" alt="{{ $blog->title }}" class="w-full h-40 object-cover rounded-lg hover:opacity-80 transition duration-300">
                                </a>
                            </div>
        
                            <!-- Article Title -->
                            <h1 class="text-lg font-bold mb-2 min-h-[4rem] text-gray-800">
                                <a href="{{ route('single.show', $blog->slug) }}" class="hover:text-blue-600 transition duration-300">
                                    {{ Str::limit($blog->title, 40) }}
                                </a>
                            </h1>
        
                            <!-- Article Meta -->
                            <div class="flex items-start text-sm text-gray-500 mb-4">
                                <span class="text-xs">{{ $blog->created_at->format('M d, Y • H:i') }}</span>
                            </div>
        
                            <!-- Hashtags -->
                            <div class="flex flex-wrap">
                                @foreach ($blog->tags->take(5) as $tag)
                                    <a href="{{ url('blogs/?tag='.$tag->slug) }}" class="bg-gray-200 text-gray-700 text-xs font-medium mr-2 mb-2 px-2.5 py-0.5 rounded hover:bg-gray-300 transition">
                                        #{{ Str::limit($tag->name, 10) }}
                                    </a>
                                @endforeach
        
                                @if ($blog->tags->count() > 5)
                                    @php
                                        $tooltipId = 'tooltip-' . $blog->id;
                                        $triggerId = 'trigger-' . $blog->id;
                                    @endphp
        
                                    <span id="{{ $triggerId }}" class="relative bg-gray-200 text-gray-700 text-xs font-medium mr-2 mb-2 px-2.5 py-0.5 rounded cursor-pointer hover:bg-gray-300 transition">
                                        +{{ $blog->tags->count() - 5 }} more
                                        <div id="{{ $tooltipId }}" class="absolute left-1/2 transform -translate-x-1/2 -top-8 bg-black text-white text-xs px-3 py-1 rounded shadow-md w-max opacity-0 invisible transition-opacity duration-300">
                                            <div class="grid grid-cols-2 gap-1">
                                                @foreach ($blog->tags->slice(5) as $tag)
                                                    <a href="{{ url('blogs/?tag='.$tag->slug) }}" class="block text-blue-300 hover:text-blue-500 transition px-1">
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
        
                                            trigger.addEventListener("mouseenter", function() {
                                                tooltip.classList.remove("opacity-0", "invisible");
                                            });
        
                                            trigger.addEventListener("mouseleave", function() {
                                                tooltip.classList.add("opacity-0", "invisible");
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
    </div>

</x-app-layout>
