  <!-- New Post Button -->
  <button id="toggleFormButton" class="btn-post">{{ __(' New Post') }}</button>
  <form id="postForm" action="{{ route('post.store') }}" method="POST"
      style="display:none" enctype="multipart/form-data">
      @csrf

      <div class="post-list">
          <div class="schedule-item">
              <label for="description">{{ __(' Description') }}:</label>
              <input type="text" id="description" name="description" class="form-control"
                  value="{{ old('description') }}">
          </div>

         <!-- Category Selection Button -->
    <label class="schedule-item" for="categoryButton">{{ __('Assign Category') }}:</label>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary" id="categoryButton" data-bs-toggle="modal" data-bs-target="#categoryModal">
            Select Category
        </button>
        <span id="selectedCategoryName" class="badge bg-success"></span>
    </div>
    <input type="hidden" name="category_id" id="selectedCategoryId">

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="categoryModalLabel">Select Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="categoryList" class="list-group">
                    @foreach ($Categories as $category)
                        <li class="list-group-item category-item d-flex justify-content-between align-items-center hover-highlight"
                            id="category-item-{{ $category->id }}"
                            value="{{ $category->id }}"
                            data-category-id="{{ $category->id }}"
                            data-has-children="{{ $category->children->count() > 0 }}">
                            {{ $category->name }}
                            @if ($category->children->count() > 0)
                                <i class="fas fa-chevron-right"></i>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectCategory">Select</button>
            </div>
        </div>
    </div>
</div>

          <div class="form-group mt-3">
              <label for="photo">Upload Photo</label>
              <input type="file" class="form-control" name="photo" id="photo" multiple>
          </div>

          <div class="form-group mt-3">
              <label for="video">Upload Video:</label>
              <input type="file" name="video" id="video" accept="video/*" multiple>
          </div>

          <div class="mt-3">
             <!-- Tag Someone -->
             <label for="UsersDropdown">{{ __('Tag Someone') }}:</label>
             <select id="UsersDropdown" class="schedule-item form-control" name="user_ids[]" multiple>
                 @foreach ($Users as $user)
                     <option class="schedule-item" value="{{ $user->id }}">{{ $user->name }}</option>
                 @endforeach
             </select>
          </div>

          <!-- Submit Buttons -->
          <div class="mt-4">
              <button type="submit" class="btn-post" name="status" id="status"
                  value="published" data-status="published">Publish</button>
              <button type="submit" class="btn-post" name="status" value="draft"
                  data-status="draft">Save as Draft</button>
          </div>

      </div>

  </form>

  <style>
    .hover-highlight:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .category-item.selected {
        background-color: #e9ecef;
    }

    #selectedCategoryName {
        display: inline-block;
        padding: 0.5em 1em;
    }
  </style>
  