<x-app-layout>
    @section('title', 'File')
    @if ($errors->any())
        <div id="is_invalid__"></div>
    @endif
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pb-2" style="background-color: #f6f6f6;}">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <!--begin::Toolbar wrapper-->
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        {{ __('List Files') }}
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid" style="background-color: #f6f6f6;}">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid"
            style="padding-left: 0px!important; padding-right: 0px!important">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center gap-3">
                            <!-- Unified Filter Form -->
                            <form action="{{ route('module.list') }}" method="GET"
                                class="d-flex gap-3 align-items-center">
                                <!-- Search -->
                                <input type="text" name="search"
                                    class="form-control form-control-solid w-250px ps-13" placeholder="Search"
                                    value="{{ request('search') }}">

                                <!-- Filter by Sort -->
                                <select name="sort" id="sort" data-control="select2" data-hide-search="true"
                                    class="form-select form-select-solid">
                                    <option value="" selected hidden disabled>Order Filter</option>
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest
                                    </option>
                                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest
                                    </option>
                                </select>

                                <!-- Filter by Category -->
                                <select name="category" id="category" data-control="select2" data-hide-search="true"
                                    class="form-select form-select-solid">
                                    <option value="" selected hidden disabled>Select Category</option>
                                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All
                                        Category</option>
                                    @foreach ($categories as $resCategory)
                                        <option value="{{ $resCategory->id }}"
                                            {{ request('category') == $resCategory->id ? 'selected' : '' }}>
                                            {{ $resCategory->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table id="users-table" class="table align-middle table-row-dashed fs-6 gy-5"
                            id="kt_table_users">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-38px">No</th>
                                    <th class="min-w-125px">Thumbnail</th>
                                    <th class="min-w-125px">Title</th>
                                    <th class="min-w-125px">Description</th>
                                    <th class="min-w-125px">Category</th>
                                    <th class="min-w-125px">Author</th>
                                    <th class="text-end min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach ($modules as $module)
                                    <tr>
                                        <td>{{ $loop->iteration + $modules->firstItem() - 1 }}</td>
                                        <td>
                                            @php
                                                 $thumbnailUrl = null;
                                        
                                        // Cek apakah thumbnail lokal ada
                                        if ($module->thumbnail) {
                                            $thumbnailUrl = filter_var($module->thumbnail, FILTER_VALIDATE_URL)
                                                ? $module->thumbnail // Gunakan langsung jika sudah URL lengkap
                                                : asset('storage/' . $module->thumbnail); // Gunakan asset jika bukan URL
                                        }
                                    
                                        // Jika tidak ada thumbnail lokal, cek apakah ada link Google Drive
                                        if (!$thumbnailUrl && $module->link) {
                                            $fileId = preg_match('/\/d\/(.*?)\//', $module->link, $matches)
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
                                        $thumbnailUrl = $thumbnailUrl ?? asset('assets/media/icons/duotune/modul/File_Explorer_Icon.webp');
                                            @endphp
                                            <div
                                                style="display: inline-block; width: 100px; height: 100px; overflow: hidden; border-radius: 8px;">
                                                <img src="{{ $thumbnailUrl  }}" alt="Thumbnail"
                                                    style="width: 100%; height: 100%; object-fit: cover;"
                                                    onerror="this.onerror=null; this.src='{{ $thumbnailUrl }}';">
                                            </div>
                                        </td>
                                        <td class="text-start pe-0" data-order="38">
                                            {{ $module->title }}
                                        </td>
                                        <td>
                                            @if ($module->description)
                                                {{ \Illuminate\Support\Str::words($module->description, 4, '...') }}
                                            @else
                                                <span class="text-muted">No description available</span>
                                            @endif
                                        </td>
                                        <td>{{ $module?->categoryModule?->name }}</td>
                                        <td>{{ $module?->user?->name }}</td>

                                        <td class="text-end">
                                            <a href="{{ $module->link }}" target="_blank"
                                                class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Go To
                                                Link</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                    <div class="pagination float-end">
                        {{ $modules->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

</x-app-layout>
