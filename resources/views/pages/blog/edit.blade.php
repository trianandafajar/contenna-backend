<x-app-layout>
    @section('title', 'Edit Blog')
    @if ($errors->any())
        <div id="is_invalid__"></div>
    @endif
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pb-2" style="background-color: #f6f6f6;}">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        {{ __('Edit Blog') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid" style="background-color: #f6f6f6;}">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid"
            style="padding-left: 0px!important; padding-right: 0px!important">
            <!--begin::Card-->
            <div class="card">
                <form class="form" action="{{ route('blog.update', $blog->slug) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body py-4">
                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Category</label>
                            <select name="category_id" id="category_id"  data-control="select2" data-placeholder="Select Category..." class="form-select form-select-solid mb-3 mb-lg-0 {{ $errors->get("category_id") ? "is-invalid border border-1 border-danger" : "" }}">
                                <option value="" hidden selected disabled>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $blog->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_module_id')" />
                        </div>

                        {{-- <div class="mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Author</label>
                        <select name="author_id" id="author_id" data-control="select2" data-placeholder="Select User..." class="form-select form-select-solid mb-3 mb-lg-0">
                            <option value="" hidden selected disabled>Select Author</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" {{ $blog->author_id  == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('author_id')" />
                    </div> --}}
                        
                            <input type="hidden" name="author_id" value="{{ Auth::user()->id }}">

                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Title</label>
                            <input type="text" name="title" id="title"
                                class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get('title') ? 'is-invalid border border-1 border-danger' : '' }}"
                                placeholder="Name"
                                value="{{ old('title', $blog->title) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>
                        
                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Slug</label>
                            <input type="text" name="slug" id="slug"
                                class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get('slug') ? 'is-invalid border border-1 border-danger' : '' }}"
                                placeholder="Slug"
                                value="{{ old('slug', $blog->slug) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        </div>
                        

                        {{-- <div class="mb-7">
                            <label class="fw-semibold fs-6 mb-2">Description</label>
                            <textarea name="content" id="content"
                                class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get('content') ? 'is-invalid border border-1 border-danger' : '' }}"
                                rows="4" placeholder="Insert content...">{{ old('content') ? old('content') : $blog->content }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                        </div> --}}

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="block text-sm font-bold text-gray-700 mb-0" for="content">Content</label>
                                <div class="d-flex gap-2">
                                    <button type="button" id="btn-ai-advanced-toggle" class="btn btn-sm btn-light">
                                        <i class="bi bi-sliders me-1"></i> Advanced
                                    </button>
                                    <button type="button" id="btn-ai-generate-content" class="btn btn-sm btn-primary">
                                        <i class="bi bi-stars me-1"></i>
                                        <span class="btn-label">Generate Content with AI</span>
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted d-block mb-2">AI will scan <strong>Title</strong> &amp; <strong>Category</strong> and generate the content automatically (existing content will be overwritten).</small>

                            <div id="ai-advanced-panel" class="mb-3 d-none">
                                <div class="card card-bordered bg-light-primary">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <label class="fw-semibold fs-7 mb-2">
                                                Key points <span class="text-muted fw-normal">(optional, one point per line)</span>
                                            </label>
                                            <textarea name="ai_points" id="ai-points" rows="5"
                                                class="form-control form-control-solid form-control-sm"
                                                placeholder="Example:&#10;- Definition and why it matters&#10;- Main benefits for beginners&#10;- 3 practical steps to get started&#10;- Common mistakes to avoid&#10;- Advanced tips and recommended tools"></textarea>
                                            <small class="text-muted">AI will use these points as the outline / sub-headings of the article.</small>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="fw-semibold fs-7 mb-2">Writing Tone</label>
                                                <select id="ai-tone" class="form-select form-select-solid form-select-sm">
                                                    <option value="professional" selected>Professional (informative)</option>
                                                    <option value="casual">Casual (relaxed)</option>
                                                    <option value="friendly">Friendly (warm &amp; personal)</option>
                                                    <option value="formal">Formal (official)</option>
                                                    <option value="storytelling">Storytelling (narrative)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-semibold fs-7 mb-2">Article Length</label>
                                                <select id="ai-length" class="form-select form-select-solid form-select-sm">
                                                    <option value="short">Short (400-600 words)</option>
                                                    <option value="medium" selected>Medium (600-900 words)</option>
                                                    <option value="long">Long (900-1400 words)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                    class="form-control form-control-solid mb-3 mb-lg-0 {{ $errors->get('thumbnail') ? 'is-invalid border border-1 border-danger' : '' }}" accept="image/jpeg, image/png, image/jpg"/>
                                <x-input-error class="mt-2" :messages="$errors->get('thumbnail')" />
                            </div>
                        </div>

                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Tags</label>
                            <select name="tags[]" id="tags" data-control="select2" data-placeholder="Select Tags..." 
                                    class="form-select form-select-solid mb-3 mb-lg-0 {{ $errors->get('tags') ? 'is-invalid border border-1 border-danger' : '' }}" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->name }}" 
                                        {{ collect(old('tags', $blog->tags->pluck('name')->toArray()))->contains($tag->name) ? 'selected' : '' }}>
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
                                        <div class="fw-bold text-gray-800 fs-5">Special Roles</div>
                                    </label>
                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('special_role')" />
                        </div>                        
                        @endrole
                             
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('blog.index') }}">
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

    // ====== AGC AI (Gemini) ======
    (function() {
        const csrf       = '{{ csrf_token() }}';
        const urlContent = '{{ route('ai.generate-content') }}';

        function showAlert(msg, type) {
            type = type || 'error';
            if (window.Swal) {
                Swal.fire({ icon: type, text: msg, timer: type === 'success' ? 2000 : undefined, showConfirmButton: type !== 'success' });
            } else {
                alert(msg);
            }
        }

        // ====== Advanced panel toggle ======
        $('#btn-ai-advanced-toggle').on('click', function() {
            $('#ai-advanced-panel').toggleClass('d-none');
            $(this).toggleClass('active');
        });

        // ====== Generate Content (with advanced options) ======
        $('#btn-ai-generate-content').on('click', function() {
            const $btn       = $(this);
            const title      = ($('#title').val() || '').trim();
            const categoryId = $('#category_id').val();
            const points     = ($('#ai-points').val() || '').trim();
            const tone       = $('#ai-tone').val();
            const length     = $('#ai-length').val();

            if (!title) {
                showAlert('Please enter a Title before generating content.', 'warning');
                $('#title').focus();
                return;
            }

            const proceed = function() {
                $btn.prop('disabled', true);
                $btn.find('.btn-label').text('Generating...');
                $btn.find('.spinner-border').removeClass('d-none');

                $.ajax({
                    url: urlContent,
                    method: 'POST',
                    data: {
                        _token: csrf,
                        title: title,
                        category_id: categoryId,
                        language: 'en',
                        points: points,
                        tone: tone,
                        length: length
                    },
                    timeout: 120000
                }).done(function(res) {
                    if (res.status === 'success' && res.content) {
                        $('#summernote').summernote('code', res.content);
                        $('#content').val(res.content);
                        showAlert('Content generated successfully.', 'success');
                    } else {
                        showAlert(res.message || 'Failed to generate content.', 'error');
                    }
                }).fail(function(xhr) {
                    const msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Failed to reach the AI server.';
                    showAlert(msg, 'error');
                }).always(function() {
                    $btn.prop('disabled', false);
                    $btn.find('.btn-label').text('Generate Content with AI');
                    $btn.find('.spinner-border').addClass('d-none');
                });
            };

            if (($('#summernote').summernote('code') || '').replace(/<[^>]*>/g, '').trim() !== '') {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'question',
                        title: 'Overwrite existing content?',
                        text: 'The current editor content will be replaced with the AI result.',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, generate',
                        cancelButtonText: 'Cancel'
                    }).then(function(r) { if (r.isConfirmed) proceed(); });
                } else if (confirm('Overwrite existing content with AI result?')) {
                    proceed();
                }
            } else {
                proceed();
            }
        });
    })();
</script>