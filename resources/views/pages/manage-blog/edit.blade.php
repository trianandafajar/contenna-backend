<x-app-layout>
    @section('title', 'Edit Blog')
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
                        {{ __('Edit Blog') }}
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
                <form class="form" action="{{ route('manage-blog.update', $blog->slug) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body py-4">
                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Category</label>
                            <select name="category_id" id="category_id" data-control="select2"
                                data-placeholder="Select Category..."
                                class="form-select form-select-solid mb-3 mb-lg-0 {{ $errors->get('category_id') ? 'is-invalid border border-1 border-danger' : '' }}">
                                <option value="" hidden selected disabled>Select Category</option>
                                @foreach ($categories as $category)
                                    @if ($category->status == 1)
                                        <option value="{{ $category->id }}"
                                            {{ $blog->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_module_id')" />
                        </div>

                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Author</label>
                            <select name="author_id" id="author_id" data-control="select2"
                                data-placeholder="Select User..." class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="" hidden selected disabled>Select Author</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}"
                                        {{ $blog->author_id == $author->id ? 'selected' : '' }}>{{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('author_id')" />
                        </div>

                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Title</label>
                            <input type="text" name="title" id="title"
                                class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get('title') ? 'is-invalid border border-1 border-danger' : '' }}"
                                placeholder="Name" value="{{ $blog->title }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Slug</label>
                            <input type="text" name="slug" id="slug"
                                class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get('slug') ? 'is-invalid border border-1 border-danger' : '' }}"
                                id="slug" placeholder="Slug" value="{{ $blog->slug }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="content">Content</label>
                            <div id="summernote"><?=old('content') ? old('content') : $blog->content?></div>
                            <input type="hidden" name="content" id="content" value="{{ old('content') }}">
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                        </div>

                        <div class="d-flex mb-7 gap-3 align-items-center">
                            @php
                                $thumbnailUrl = $blog->thumbnail ? asset('storage/' . $blog->thumbnail) : null;

                                if (!$thumbnailUrl && $blog->link) {
                                    $fileId = preg_match('/\/d\/(.*?)\//', $blog->link, $matches) ? $matches[1] : null;
                                    $thumbnailUrl = $fileId ? "https://drive.google.com/thumbnail?id={$fileId}" : null;
                                }

                                $defaultThumbnailUrl = asset(
                                    'assets/media/icons/duotune/modul/File_Explorer_Icon.webp',
                                );
                            @endphp
                            <div
                                style="display: inline-block; width: 100px; height: 100px; overflow: hidden; border-radius: 8px;">
                                <img src="{{ $thumbnailUrl ?: $defaultThumbnailUrl }}" alt="Thumbnail"
                                    style="width: 100%; height: 100%; object-fit: cover;"
                                    onerror="this.onerror=null; this.src='{{ $defaultThumbnailUrl }}';">
                            </div>
                            <div class="mb-7">
                                <label class="fw-semibold fs-6 mb-2">Featured Image</label>
                                <input type="file" name="thumbnail" id="thumbnail"
                                    class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get('thumbnail') ? 'is-invalid border border-1 border-danger' : '' }}" accept="image/jpeg, image/png, image/jpg"  />
                                <x-input-error class="mt-2" :messages="$errors->get('thumbnail')" />
                            </div>
                        </div>

                        {{-- <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Tags</label>
                        <select name="tags[]" id="tags" multiple="multiple" style="width: 100%">
                            @foreach ($blog->tags as $tag)
                                <option value="{{ $tag->name }}" selected>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                    </div> --}}

                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Tags</label>
                            <select name="tags[]" id="tags" data-control="select2"
                            data-placeholder="Select Tags..."
                            class="form-select form-select-solid mb-3 mb-lg-0 {{ $errors->get('tags') ? 'is-invalid border border-1 border-danger' : '' }}"
                            multiple>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->name }}"
                                    {{ in_array($tag->name, old('tags', $blog->tags->pluck('name')->toArray() ?? [])) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                            <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                        </div>
                        @role('super-admin|administrator|mentor|koordinator')
                        <div class="mb-7">
                            <div class="d-flex fv-row mt-3">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input type="hidden" name="special_role" value="1">
                        
                                    <input class="form-check-input me-1 border border-1 border-dark cursor-pointer"
                                        name="special_role"
                                        type="checkbox"
                                        value="2"
                                        id="role_"
                                        style="width: 2rem; height: 2rem;"
                                        {{ $blog->special_role  == '1' ? '' : 'checked' }}>
                        
                                    <label class="form-check-label cursor-pointer" for="role_">
                                        <div class="fw-bold text-gray-800 fs-5" >Special Roles</div>
                                    </label>
                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('special_role')" />
                        </div>                        
                        @endrole
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('manage-blog.index') }}">
                            <button type="button" class="btn btn-light me-3">Cancel</button>
                        </a>
                        <button type="submit" class="btn btn-primary" name="save">
                            <span class="indicator-label" id="submit">{{ $blog->status == 1 ? 'Submit' : 'Publish' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

</x-app-layout>
<script src="{{ asset('assets/js/custom-summernote.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#tags').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Tambahkan atau edit tag...",
            ajax: {
                url: "{{ route('tags.search') }}",
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.name,
                                text: item.name
                            };
                        })
                    };
                },
                cache: true
            }
        });
    });

    $(document).ready(function() {
        if ($('#slug').val() == '') {
            $('#slug').val($('#title').val().toLowerCase().replace(/ /g, '-'));
        }
        $('#title').on('input', function() {
            $('#slug').val($(this).val().toLowerCase().replace(/ /g, '-'));
        });
    });
</script>
