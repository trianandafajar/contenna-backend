<div class="mb-4">
    <div class="w-100 bg-white p-4 rounded shadow-sm border">
        <!-- Search Form -->
        <div class="mb-6">
            <form action="" method="get">
                <div class="input-group">
                    <input 
                        type="text" 
                        name="q" 
                        id="user-search"
                        class="form-control form-control-solid flex-grow-1 ps-3" 
                        placeholder="Search by title..." 
                        value="{{ request('q') }}"
                    />
                    <button type="submit" class="btn btn-primary btn-sm text-center">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <form action="" method="get" id="filterForm">
            <!-- Categories Section -->
            <div class="mb-4">
                <h6 class="fw-bold text-uppercase border-bottom pb-2">
                    <i class="fas fa-bars me-2"></i> Category
                </h6>

                <!-- Search Category -->
                <div class="mb-3">
                    <input 
                        type="text" 
                        class="form-control" 
                        id="categorySearch" 
                        placeholder="Search category..."
                    >
                </div>

                <div style="max-height: 10rem; overflow: auto; padding-top: 1rem ; ">
                    @if (isset($categories) && $categories->count() > 0)
                        <ul class="list-unstyled" id="categoryList" >
                            @foreach ($categories as $category)
                                <li class="mb-2 category-item">
                                    <input 
                                        type="radio" 
                                        name="category" 
                                        value="{{ $category->slug }}" 
                                        id="category{{ $category->id }}" 
                                        class="form-check-input" 
                                        {{ request('category') == $category->slug ? 'checked' : '' }}
                                    >
                                    <label for="category{{ $category->id }}" class="form-check-label">
                                        {{ $category->name }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No categories</p>
                    @endif
                </div>
            </div>

            <!-- Tag filter -->
            <div class="mb-4">
                <h6 class="fw-bold text-uppercase border-bottom pb-2">
                    <i class="fas fa-tags me-2"></i> TAGS
                </h6>

                <!-- Search Tag -->
                <div class="mb-3">
                    <input 
                        type="text" 
                        class="form-control" 
                        id="tagSearch" 
                        placeholder="Search tag..."
                    >
                </div>

                @if (isset($tags) && $tags->count() > 0)
                    <div style="max-height: 15rem; overflow: auto; padding-top: 1rem" id="tagList">
                        @foreach ($tags as $tag)
                            <div class="form-check mb-3 tag-item">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="tags[]" 
                                    value="{{ $tag->slug }}" 
                                    id="tag{{ $tag->id }}" 
                                    {{ in_array($tag->slug, explode(',', request('tags', ''))) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="tag{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No tags</p>
                @endif
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary w-100 py-2">Apply Filter</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('filterForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        // Get selected category
        let selectedCategory = document.querySelector('input[name="category"]:checked');
        if (selectedCategory) {
            params.set('category', selectedCategory.value);
        } else {
            params.delete('category');
        }

        // Get selected tags
        let selectedTags = document.querySelectorAll('input[name="tags[]"]:checked');
        if (selectedTags.length > 0) {
            let tagValues = Array.from(selectedTags).map(tag => tag.value);
            params.set('tags', tagValues.join(','));
        } else {
            params.delete('tags');
        }

        window.location.href = url.pathname + '?' + params.toString();
    });

    // Search Categories
    document.getElementById('categorySearch').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let categories = document.querySelectorAll('.category-item');
        categories.forEach(category => {
            let text = category.textContent.toLowerCase();
            category.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Search Tags
    document.getElementById('tagSearch').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let tags = document.querySelectorAll('.tag-item');
        tags.forEach(tag => {
            let text = tag.textContent.toLowerCase();
            tag.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
