<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editPostForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="edit-description">Description</label>
                        <input type="text" id="edit-description" name="description" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit-category">Category</label>
                        <select id="edit-category" name="category_id" class="form-control">
                            @foreach ($Categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Current Media Preview -->
                    <div id="current-media" class="form-group mb-3">
                        <label>Current Media</label>
                        <div id="mediaCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <!-- Carousel items will be dynamically inserted here -->
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#mediaCarousel"
                                data-bs-slide="prev" style="width: 40px; margin-top: 250px; margin-bottom: 250px;">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#mediaCarousel"
                                data-bs-slide="next" style="width: 40px; margin-top: 250px; margin-bottom: 250px;">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit-photos">Upload New Photos</label>
                        <input type="file" id="edit-photos" name="photos[]" class="form-control" multiple
                            accept="image/*">
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit-videos">Upload New Videos</label>
                        <input type="file" id="edit-videos" name="videos[]" class="form-control" multiple
                            accept="video/*">
                    </div>

                    <input type="hidden" id="post-id" name="post_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-post" data-dismiss="modal">Close</button>
                <button type="button" class="btn-post" id="saveChangesBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>
