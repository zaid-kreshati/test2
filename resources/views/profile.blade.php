@extends('layouts.PostBlug_header')
@section('content')
@section('title', __('Post Blug'))


<body>
    <main>
        <section>
            <div class="container  " style="width: 45%;">
                <div class="row  justify-content-center" style="width: 220%; margin-left: -60%; margin-top: -10%;">
                    <div class="col col-lg-9 col-xl-8 board">

                        <!-- Profile Media -->
                        @include('partials.ProfileMedia')

                        @include('partials.Descriptions')

                        <!-- Create Post -->
                        @include('partials.createPost')

                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="filter-btn">
                    <div class="post-filters">
                        <button class="btn " id="status" data-status="published">Published</button>
                        <button class="btn  " id="status" data-status="draft">Draft</button>
                        <button class="btn  " id="status" data-status="archived">Archived</button>
                    </div>
                </div>

                <!-- Posts -->
                @include('partials.posts')

                <!-- Edit Post -->
                @include('partials.editPost')


            </div>
        </section>
    </main>

    <script>
        // Filter posts by status
        $('.post-filters button').on('click', function() {
            // Remove active class from all buttons
            $('.post-filters button').removeClass('active');
            // Add active class to clicked button
            $(this).addClass('active');

            var status = $(this).data('status');


            $.ajax({
                url: '{{ route('posts.filter') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        // Update the posts list with new content
                        $('#post-list').html(response.html);
                        initializePostHandlers();
                    } else {
                        alert('Error filtering posts: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error filtering posts');
                    console.error(xhr.responseText);
                },
            });
        });


        // Function to initialize post handlers
        function initializePostHandlers() {
            // Reinitialize edit buttons
            $('.edit-post-btn').each(function() {
                initializeEditButton(this);
            });

            // Reinitialize archive buttons
            $('.archive-post-btn').each(function() {
                initializeArchiveButton(this);
            });

            // Reinitialize carousels
            $('.carousel').each(function() {
                new bootstrap.Carousel(this);
            });
        }

        // Helper function for asset URL
        function asset(path) {
            return '{{ url('/') }}' + path;
        }

        // Delete Post
        $(document).on('click', '.delete-post-btn', function(e) {
            e.preventDefault();
            var postId = $(this).data('id');

            if (confirm('Are you sure you want to delete this post?')) {
                $.ajax({
                    url: `/posts/${postId}/delete`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        if (response.success) {
                            $('#post-list').html(response.data.html);
                            alert('Post deleted successfully');
                        }
                    },
                    error: function(xhr) {
                        alert('Error deleting post');
                        console.error(xhr.responseText);
                    }
                });
            } else {
                alert('Post not deleted');
            }
        });

        // Publish Post
        $(document).on('click', '.publish-post-btn', function(e) {
            e.preventDefault();
            var postId = $(this).data('id');
            var status = $(this).data('status');
            console.log(postId);

            if (confirm('Are you sure you want to publish this post?')) {
                $.ajax({
                    url: `/posts/${postId}/publish`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {

                            $('#post-list').html(response.data.html);
                            alert('Post published successfully');
                        }
                    },
                    error: function(xhr) {
                        alert('Error publishing post');
                        console.error(xhr.responseText);
                    }
                });
            } else {
                alert('Post not published');
            }
        });



        window.postLoader = {
            currentPage: 1,
            isLoading: false,
            hasMorePages: true,

            init: function() {
                if (!window.loadEndInitialized) {
                    this.attachScrollHandler();
                    this.initializePostHandlers();
                    window.loadEndInitialized = true;
                }
            },

            loadMorePosts: function(retryCount = 0) {
                if (this.isLoading || !this.hasMorePages) {
                    if (!this.hasMorePages) {
                        console.log('No more posts');
                    }
                    return;
                }

                this.isLoading = true;

                const home = @json($home);
                const status = @json($status)

                $.ajax({
                    url: '{{ route('posts.load-more') }}',
                    type: 'GET',
                    data: {
                        page: this.currentPage + 1,
                        home: home,
                        status
                    },
                    success: (response) => {
                        if (response.data.html.trim() === '') {
                            this.hasMorePages = false;
                            $('#loading-spinner').hide();

                        } else {
                            this.currentPage++;

                            $('#post-list').append(response.data.html);
                            this.hasMorePages = response.data.hasMorePages;
                            this.initializePostHandlers();

                        }

                        if (!this.hasMorePages) {
                            $('#loading-spinner').hide();
                        }
                    },
                    error: (xhr) => {
                        console.error('Error loading posts:', xhr);

                        if (retryCount < 3) {
                            setTimeout(() => {
                                this.isLoading = false;
                                this.loadMorePosts(retryCount + 1);
                            }, 1000 * (retryCount + 1));
                        } else {
                            alert('Failed to load more posts. Please try again later.');
                        }
                    },
                    complete: () => {
                        $('#loading-spinner').remove();
                        this.isLoading = false;
                    }
                });
            },

            debounce: function(func, wait) {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(context, args);
                    }, wait);
                };
            },

            attachScrollHandler: function() {
                $(window).scroll(this.debounce(() => {
                    if (!this.hasMorePages) {
                        $('#loading-spinner').hide();
                        return;
                    }

                    if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                        this.loadMorePosts();
                    }
                }, 250));
            },

            initializePostHandlers: function() {
                // Initialize edit buttons
                $('.edit-post-btn').each(function() {
                    if (!$(this).data('initialized')) {
                        initializeEditButton(this);
                        $(this).data('initialized', true);
                    }
                });

                // Initialize archive buttons
                $('.archive-post-btn').each(function() {
                    if (!$(this).data('initialized')) {
                        initializeArchiveButton(this);
                        $(this).data('initialized', true);
                    }
                });
            }


        };



        function initializeEditButton(button) {
            $(button).on('click', function() {
                var postId = $(this).data('id');
                var description = $(this).data('description');
                var category = $(this).data('category');
                var media = $(this).data('media');

                // Set values in the edit modal
                $('#post-id').val(postId);
                $('#edit-description').val(description);
                $('#edit-category').val(category);

                // Clear existing carousel items
                $('.carousel-inner').empty();

                // Update carousel with media
                if (media && media.length > 0) {
                    media.forEach(function(item, index) {
                        var slideHtml = `
                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                        <div class="position-relative">`;

                        if (item.type === 'post_image') {
                            slideHtml += `
                        <img src="{{ asset('storage/photos/') }}/${item.URL}"
                             alt="Post Photo"
                             class="d-block w-100"
                             style="max-width: 150%; height: 500px; object-fit: cover;">`;
                        } else if (item.type === 'post_video') {
                            slideHtml += `
                        <video class="d-block w-100" controls
                               style="max-width: 150%; height: 500px; object-fit: cover;">
                            <source src="{{ asset('storage/videos/') }}/${item.URL}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>`;
                        }

                        slideHtml += `
                        <button type="button"
                                class="btn btn-danger delete-media-btn position-absolute"
                                data-media-id="${item.id}"
                                style="top: 10px; right: 50px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>`;

                        $('.carousel-inner').append(slideHtml);
                    });

                    // Show/hide carousel controls based on media count
                    if (media.length > 1) {
                        $('.carousel-control-prev, .carousel-control-next').show();
                    } else {
                        $('.carousel-control-prev, .carousel-control-next').hide();
                    }
                } else {
                    // If no media, hide controls and show placeholder
                    $('.carousel-control-prev, .carousel-control-next').hide();
                    $('.carousel-inner').html('<div class="text-center p-3">No media available</div>');
                }

                // Initialize carousel
                var carousel = new bootstrap.Carousel(document.querySelector('#mediaCarousel'), {
                    interval: false // Prevent auto-sliding
                });

                // Show the modal
                $('#editPostModal').modal('show');
            });
        }

        function initializeArchiveButton(button) {
            $(button).on('click', function(e) {
                e.preventDefault();
                var postId = $(this).data('id');

                if (confirm('Are you sure you want to archive this post?')) {
                    $.ajax({
                        url: `/posts/${postId}/archive`,
                        type: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update the posts list with new HTML
                                $('#post-list').html(response.data.html);

                                // Reinitialize buttons for the updated posts
                                initializePostHandlers();

                                alert('Post archived successfully');
                            } else {
                                alert(response.message || 'Error archiving post');
                            }
                        },
                        error: function(xhr) {
                            alert('Error archiving post');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        }


        // Initialize when document is ready
        $(document).ready(function() {
            window.postLoader.init();
        });




        $('#UsersDropdown').select2({
            placeholder: 'Select a user',
            allowClear: true, // Adds a clear button
            width: '100%' // Adjust width as per your layout
        });


        // Toggle form visibility
        $('#toggleFormButton').click(function() {
            $('#postForm').toggle();
        });

        // Capture the clicked button's status and validate category
        var clickedStatus = null;
        var categoryId = $('#selectedCategoryId').val();
        $('#postForm button[type="submit"]').click(function(e) {
            e.preventDefault(); // Prevent form submission initially

            // Check if category is selected
            var categoryId = $('#selectedCategoryId').val();
            if (!categoryId) {
                alert('Please select a category before submitting');
                return false;
            }

            clickedStatus = $(this).data('status'); // Get the status from the button clicked
            $('#postForm').submit(); // Submit the form if validation passes
        });


        // Handle form submission via AJAX
        $('#postForm').on('submit', function(e) {
            e.preventDefault();

            // Validate category again during form submission
            var categoryId = $('#selectedCategoryId').val();
            if (!categoryId) {
                alert('Please select a category before submitting');
                return false;
            }

            if (!validateMediaCount()) {
                return false;
            }

            var formData = new FormData(this);

            // Append multiple photos
            var photos = $('#photo')[0].files;
            for (let i = 0; i < photos.length; i++) {
                formData.append('photos[]', photos[i]);
            }

            // Append multiple videos
            var videos = $('#video')[0].files;
            for (let i = 0; i < videos.length; i++) {
                formData.append('videos[]', videos[i]);
            }

            formData.append('status', clickedStatus);
            formData.append('category_id', categoryId);

            $.ajax({
                type: 'POST',
                url: '{{ route('post.store') }}',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        alert('Post created successfully!');
                        $('#post-list').html(response.data.html);
                        $('#postForm')[0].reset();
                        $('#postForm').hide();
                        initializePostHandlers();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error creating post');
                },
                complete: function() {
                    $('#postForm').modal('hide');
                }
            });
        });

        function validateMediaCount() {
            const photos = $('#photo')[0].files;
            const videos = $('#video')[0].files;
            const totalCount = photos.length + videos.length;

            if (totalCount > 5) {
                alert('You cannot upload more than 5 media files in total.');
                // Clear file inputs
                $('#photo').val('');
                $('#video').val('');
                return false;
            }
            return true;
        }

        // Add validation to file inputs change
        $('#photo, #video').on('change', validateMediaCount);




        // Handle the "Save changes" button click
        $('#saveChangesBtn').on('click', function() {
            var formData = new FormData($('#editPostForm')[0]);
            var postId = $('#post-id').val();
            var status = $('#status').val();

            if (!validateUpdateMediaCount(postId)) {
                $('#edit-photos').val('');
                $('#edit-videos').val('');
                return false;
            }



            $.ajax({
                url: '/posts/' + postId + '/update',
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#post-list').html(response.data.html);

                        // Close modal and show success message
                        $('#editPostModal').modal('hide');
                        alert('Post updated successfully');
                    } else {
                        alert(response.message || 'Error updating post');
                    }
                },
                error: function(xhr) {
                    alert('Error updating post');
                    //console.error(xhr.responseText);
                }
            });
        });


        // Add validation to file inputs change
        $('#edit-photo, #edit-video').on('change', function() {
            const postId = $('#post-id').val();
            validateUpdateMediaCount(postId);
        });


        // Delete Media
        $(document).on('click', '.delete-media-btn', function(e) {
            e.preventDefault();
            var mediaId = $(this).data('media-id');
            var editButton = $(this).closest('.board').find('.edit-post-btn');

            if (confirm('Are you sure you want to delete this media?')) {
                $.ajax({
                    url: `/media/${mediaId}/delete`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the edit button's data attributes with new data
                            editButton
                                .data('id', response.data.id)
                                .data('description', response.data.description)
                                .data('category', response.data.category_id)
                                .data('media', response.data.media);

                            // Update the modal form fields
                            $('#post-id').val(response.data.id);
                            $('#edit-description').val(response.data.description);
                            $('#edit-category').val(response.data.category_id);

                            // Update carousel with new media data
                            updateCarouselMedia(response.data.media);

                            alert('Media deleted successfully');
                        }
                    },
                    error: function(xhr) {
                        alert('Error deleting media');
                        console.error(xhr.responseText);
                    }
                });
            }
        });


        // Update carousel with media
        function updateCarouselMedia(media) {

            // Clear existing carousel items
            $('.carousel-inner').empty();

            if (media && media.length > 0) {
                media.forEach(function(item, index) {
                    var slideHtml = `<div class="carousel-item ${index === 0 ? 'active' : ''}">
                <div class="position-relative">`;

                    if (item.type === 'post_image') {
                        slideHtml += `<img src="${asset('/storage/photos/')}/${item.URL}"
                 alt="Post Photo"
                 class="d-block w-100"
                 style="max-width: 150%; height: 500px; object-fit: cover;">`;
                    } else if (item.type === 'post_video') {
                        slideHtml += `<video class="d-block w-100" controls
                       style="max-width: 150%; height: 500px; object-fit: cover;">
                    <source src="${asset('/storage/videos/')}/${item.URL}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>`;
                    }

                    slideHtml += `<button type="button"
                    class="btn-post delete-media-btn position-absolute"
                    data-media-id="${item.id}"
                    style="top: 10px; right: 50px;">
                <i class="fas fa-trash"></i>
            </button>
            </div>
            </div>`;

                    $('.carousel-inner').append(slideHtml);
                });

                // Show/hide carousel controls based on media count
                if (media.length > 1) {
                    $('.carousel-control-prev, .carousel-control-next').show();
                } else {
                    $('.carousel-control-prev, .carousel-control-next').hide();
                }

                // Reinitialize carousel
                var carousel = new bootstrap.Carousel(document.querySelector('#mediaCarousel'), {
                    interval: false // Prevent auto-sliding
                });
            } else {
                // If no media, hide controls and show placeholder
                $('.carousel-control-prev, .carousel-control-next').hide();
                $('.carousel-inner').html('<div class="text-center p-3">No media available</div>');
            }
        }

        function validateUpdateMediaCount(postId) {
            const photos = $('#edit-photos')[0]?.files || [];
            const videos = $('#edit-videos')[0]?.files || [];
            const existingMediaCount = $('#mediaCarousel .carousel-item').length;
            const newMediaCount = photos.length + videos.length;
            const totalCount = existingMediaCount + newMediaCount;

            if (totalCount > 5) {
                alert('You cannot have more than 5 media files in total.');
                // Clear file inputs
                $('#edit-photo').val('');
                $('#edit-video').val('');
                return false;
            }
            return true;
        }



        $(document).ready(function() {
            let selectedCategoryId = null;
            let categoryPath = [];

            function loadCategories(parentId = null) {
                $.ajax({
                    url: parentId ?
                        `{{ route('categories.children', ['parentId' => ':parentId']) }}`.replace(
                            ':parentId', parentId) :
                        '{{ route('categories.index') }}',
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let categoryHtml = '';
                        // Check if response exists and has data
                        if (response && Array.isArray(response)) {
                            response.forEach(function(category) {
                                categoryHtml += `
                        <li class="list-group-item category-item"
                            data-category-id="${category.id}"
                            data-has-children="${category.has_children ? true : false}">
                            ${category.name}
                            ${category.has_children ? '<span class="float-end">></span>' : ''}
                        </li>
                    `;
                            });
                        } else {
                            categoryHtml = '<li class="list-group-item">No categories found</li>';
                        }

                        $('#categoryList').html(categoryHtml);

                        if (parentId) {
                            $('#categoryList').prepend(`
                    <li class="list-group-item category-item back-button">
                        < Back
                    </li>
                `);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading categories:', xhr);
                        $('#categoryList').html(
                            '<li class="list-group-item">Error loading categories</li>');
                    }
                });
            }

            $(document).on('click', '.category-item', function() {
                const categoryId = $(this).data('category-id');
                const hasChildren = $(this).data('has-children');
                const categoryName = $(this).text().trim();

                if ($(this).hasClass('back-button')) {
                    categoryPath.pop();
                    loadCategories(categoryPath[categoryPath.length - 1]);
                } else if (hasChildren == false) {
                    selectedCategoryId = categoryId;
                    $('#selectedCategoryId').val(selectedCategoryId);
                    $('#selectedCategoryName').text(categoryName);
                } else if (hasChildren) {
                    categoryPath.push(categoryId);
                    loadCategories(categoryId);
                }
                // Highlight selected category
                $('.category-item').removeClass('selected');
                $(this).addClass('selected');
            });

            $('#selectCategory').click(function() {
                if (selectedCategoryId) {
                    $('#selectedCategoryId').val(selectedCategoryId);
                    $('#categoryButton').text($('#selectedCategoryName').text());
                    $('#categoryModal').modal('hide');
                    // Remove modal backdrop manually
                    $('.modal-backdrop').remove();
                    // Restore body scrolling
                    $('body').removeClass('modal-open').css('overflow', '');
                    $('body').css('padding-right', '');
                }
            });

            $('#categoryModal').on('show.bs.modal', function() {
                loadCategories();
                categoryPath = [];
            });

            $('#categoryModal').on('hidden.bs.modal', function() {
                $('#categoryList').html('');
                $('#categoryButton').removeClass('btn-success').addClass('btn-primary');
                // Remove modal backdrop and restore body state
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
                $('body').css('padding-right', '');
            });
        });


        // Update category button text when category is selected
        $(document).on('click', '.category-item', function() {
            if (!$(this).data('has-children')) {
                const categoryName = $(this).text().trim();
                $('#selectedCategoryName').text(categoryName).show();
                $('#categoryButton').addClass('btn-success').removeClass('btn-primary');
            }
        });

        // Toggle form visibility
        $(document).on('click', '#toggleCommentForm', function() {
            var postId = $(this).data('id');
            $('#commentForm-' + postId).toggle();
            $('#commentForm2-' + postId).toggle();


            // Hide other comment forms
            $('.commentForm').not('#commentForm-' + postId).hide();
            $('.commentForm').not('#commentForm2-' + postId).hide();
        });

        $(document).on('click', '.comment-btn', function() {
            var postId = $(this).data('id');

        });



        $(document).on('keydown', 'textarea[name="text"]', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                var postId = $(this).closest('form').find('input[name="post_id"]').val();
                var text = $(this).val();

                let formData = {
                    _token: '{{ csrf_token() }}',
                    post_id: postId,
                    text: text
                };

                $.ajax({
                    url: '{{ route('comment.store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Add new comment to the comments section
                            let html = `
                            <div class="comments-section">
                                <div class="comment">
                                    <div class="comment-header">
                                        ${response.data.personal_image ?
                                            `<img src="{{ asset('storage/photos/') }}/${response.data.personal_image.URL}"
                                                 alt="Profile photo"
                                                 class="img-fluid rounded-circle"
                                                 style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">` :
                                            `<img src="{{ asset('/PostBlug/default-profile .png') }}"
                                                 alt="Profile photo"
                                                 class="img-fluid rounded-circle"
                                                 style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">`
                                        }
                                        <div style="right: 750px; font-size: 15px; margin-top: -40px; position: relative;">
                                            ${response.data.name}
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        ${response.data.comment.text}
                                    </div>
                                    <div class="comment-actions">
                                        <button class="comment-btn reply-btn" id="replyCommentBtn" data-comment-id="${response.data.comment.id}">
                                            <i class="fas fa-reply"></i>
                                            <div>Reply</div>
                                        </button>
                                    </div>
                                    <div class="comment-replies"></div>
                                </div>
                            </div>`;

                            // Insert the new comment after the comment form
                            $('#commentForm-' + postId).append(html);

                            $('textarea[id="comment-textarea->' + postId + '"]').val('');


                            // Clear the input
                            $(this).val('');

                        } else {
                            alert('Error submitting comment: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error submitting comment. Please try again.');
                    }
                });
            }
        });

        $(document).on('click', '#replyCommentBtn', function(e) {
            e.preventDefault();
            var parentId = $(this).data('comment-id');
            var commentSection = $(this).closest('.comments-section');
            var replyForm = commentSection.find('#replyCommentForm');

            // Toggle the reply form visibility
            replyForm.toggle();

            // Only make AJAX call if form is being shown
            if (replyForm.is(':visible')) {
                $.ajax({
                    url: "{{ route('comment.get.nested') }}",
                    type: "GET",
                    data: {
                        _token: '{{ csrf_token() }}',
                        parent_id: parentId,
                        comment: parentId // Add comment parameter since it's undefined in error
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the nested comments section in the reply form
                            var nestedCommentsSection = replyForm.find('.nested-comment-form');
                            nestedCommentsSection.html(response.data);
                        }
                    },
                    error: function(xhr) {
                        console.log('Error loading nested comments');
                        replyForm.hide();
                    }
                });
            }
        });

        $(document).on('keydown', 'textarea[name="nested_text"]', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                var commentId = $(this).closest('form').find('input[name="parent_id"]').val();
                var postId = $(this).closest('form').find('input[name="post_id"]').val();
                var text = $(this).val();

                var formData = {
                    _token: '{{ csrf_token() }}',
                    parent_id: commentId,
                    post_id: postId,
                    text: text
                };
                console.log(formData);

                $.ajax({
                    url: '{{ route('comment.store.nested') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                    if (response.success) {
                        console.log(response.data);

                        let html = `
                            <div class="comments-section" id="nested-comment">
                                <div class="comment">
                                    <div class="comment-header">
                                        ${response.data.personal_image ?
                                            `<img src="{{ asset('storage/photos/') }}/${response.data.personal_image.URL}"
                                                    alt="Profile photo"
                                                    class="img-fluid rounded-circle"
                                                    style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">` :
                                            `<img src="{{ asset('/PostBlug/default-profile .png') }}"
                                                alt="Profile photo"
                                                class="img-fluid rounded-circle"
                                                style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">`
                                        }
                                        <div style="right: 750px; font-size: 15px; margin-top: -40px; position: relative;">
                                            ${response.data.name}
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        ${response.data.comment.text}
                                    </div>
                                </div>
                            </div>
                        `;

                        // Insert comment into nested-comment-form
                        $('#nested-comments-section-' + commentId).append(html);


                        // Clear the input
                        $('textarea[id="nested-comment-' + commentId  + '"]').val('');

                        // Hide the reply form
                        $(this).closest('form').hide();
                    }

                },
                error: function(xhr) {
                    console.error('Error submitting reply comment:', xhr);
                }
            });
            }
        });

    </script>
@endsection
</body>
