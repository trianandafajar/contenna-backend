<x-app-layout>
    @section('title', 'Blog')
    @if ($errors->any())
        <div id="is_invalid__"></div>
    @endif
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pb-2" style="background-color: #f6f6f6;">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <!--begin::Toolbar wrapper-->
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        {{ __('List Blog') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluida" style="background-color: #f6f6f6;">
        <div id="kt_app_content_container" class="app-container container-fluid"
            style="padding-left: 0px!important; padding-right: 0px!important">
            <div class="card">
                <div class="card-header border-0 pt-6 flex flex-col-reverse sm:flex-row">
                    <div class="card-title">
                        <form action="{{ route('blog.index') }}" method="GET"
                            class="d-flex gap-3 align-items-center">
                            <input type="text" name="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search" value="{{ request('search') }}">
                            <select name="sort" id="sort" class="form-select form-select-solid">
                                <option value="" disabled hidden>Order Filter</option>
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                            </select>
                            <select name="category" id="category" class="form-select form-select-solid">
                                <option value="" disabled hidden>Select Category</option>
                                <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>
                    <div class="card-toolbar">
                        <div class="flex justify-end sm:justify-start w-full max-w-full">
                            @can('blog-create')
                                <a href="{{ route('blog.create') }}" class="btn btn-primary !w-full text-center flex items-center justify-center">
                                    <i class="ki-outline ki-plus fs-2"></i> Add New
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th>No</th>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach ($blogs as $blog)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @php
                                                $thumbnailUrl = null;

                                                // Cek apakah thumbnail lokal ada
                                                if ($blog->thumbnail) {
                                                    $thumbnailUrl = filter_var($blog->thumbnail, FILTER_VALIDATE_URL)
                                                        ? $blog->thumbnail // Gunakan langsung jika sudah URL lengkap
                                                        : asset('storage/' . $blog->thumbnail); // Gunakan asset jika bukan URL
                                                }

                                                // Jika tidak ada thumbnail lokal, cek apakah ada link Google Drive
                                                if (!$thumbnailUrl && $blog->link) {
                                                    $fileId = preg_match('/\/d\/(.*?)\//', $blog->link, $matches)
                                                        ? $matches[1]
                                                        : null;

                                                    if ($fileId) {
                                                        // Format URL thumbnail Google Drive
                                                        $potentialThumbnail = "https://lh3.googleusercontent.com/d/{$fileId}=s220";

                                                        // Cek apakah URL thumbnail valid (tidak 404)
                                                        $headers = @get_headers($potentialThumbnail);
                                                        if ($headers && strpos($headers[0], '200')) {
                                                            $thumbnailUrl = $potentialThumbnail;
                                                        }
                                                    }
                                                }

                                                // Gunakan thumbnail default jika semua opsi gagal
                                                $thumbnailUrl =
                                                    $thumbnailUrl ??
                                                    asset('assets/media/icons/duotune/modul/File_Explorer_Icon.webp');
                                            @endphp

                                            <div
                                                style="display: inline-block; width: 100px; height: 100px; overflow: hidden; border-radius: 8px;">
                                                <img src="{{ $thumbnailUrl }}" alt="Thumbnail"
                                                    style="width: 100%; height: 100%; object-fit: cover;"
                                                    onerror="this.onerror=null; this.src='{{ $thumbnailUrl }}';">
                                            </div>
                                        </td>
                                        <td><a href="{{ route('blog.show', $blog->slug) }}" class="text-gray-800 fw-bold text-hover-primary">{{ $blog->title }}</a></td>
                                        <td>{{ Str::limit($blog->slug, 50) }}</td>
                                        <td>{{ $blog->category->name ?? '-' }}</td>
                                        <td>{{ $blog->user->name ?? '-' }}</td>
                                        <td>{{ $blog->status == 1 ? 'publish' : 'draft' }}</td>
                                        <td class="text-end">
                                            @canany(['blog-edit', 'blog-view', 'blog-delete'])
                                                <a href="#"
                                                    class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                    <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">

                                                    @can('blog-edit')
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('blog.edit', $blog->slug) }}"
                                                                class="menu-link px-3">Edit</a>
                                                        </div>
                                                    @endcan

                                                    @can('blog-view')
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('blog.show', $blog->slug) }}"
                                                                class="menu-link px-3">Show</a>
                                                        </div>
                                                    @endcan

                                                    @can('blog-delete')
                                                        <x-delete-confirmation-2 :id="$blog->id" :route="route('blog.destroy', $blog->slug)" />
                                                    @endcan

                                                </div>
                                                <!--end::Menu-->
                                            @endcanany
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination float-end">
                        {{ $blogs->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @media (max-width: 640px) {

            form {
            width: 90%;            
            }
            
            form .form-select {
                font-size: 0.55rem;
                padding-left: 0.25rem;
                border-radius: 0;
            }


            .app-content{
                padding: 1rem !important;
            }
            .card-toolbar a {
                max-width: 10rem !important;
                margin-right: 26rem !important;
            }
        }
    </style>
</x-app-layout>
