<div class="row justify-content-center">
    <div class="col-md-8">
        <form id="search-form" class="d-flex">
            <input type="text" id="search-query" name="query" class="form-control me-2" placeholder="Search for users or posts...">
        </form>
        <div class="btn-group mt-2" role="group" aria-label="Search Filters" style="display: none;" id="search-filters">
            <button type="button" class="btn btn-secondary search-filter-btn" data-filter="all">All</button>
            <button type="button" class="btn btn-secondary search-filter-btn" data-filter="users">Users</button>
            <button type="button" class="btn btn-secondary search-filter-btn" data-filter="posts">Posts</button>
            <button type="button" class="btn btn-secondary search-filter-btn" data-filter="posts_with_photo">Posts with Photo</button>
            <button type="button" class="btn btn-secondary search-filter-btn" data-filter="posts_with_video">Posts with Video</button>
        </div>
    </div>
    <div id="search-results" class="row mt-4" style="display: none;">
        <div class="col-md-8">
            <h3>Search Results</h3>
            <ul id="results-list" class="list-group"></ul>
        </div>
    </div>
</div>

<script>
    $(document).on('focus', '#search-query', function() {
        $('#search-filters').show();
        $('#post-list').hide();
    });

    $(document).ready(function() {
        let currentFilter = 'all';

        $('#search-query').on('input', function() {
            performSearch();
        });

        $('.search-filter-btn').on('click', function() {
            currentFilter = $(this).data('filter');
            $('.search-filter-btn').removeClass('active');
            $(this).addClass('active');
            performSearch();
        });

        function performSearch() {
            const query = $('#search-query').val();
            const resultsList = $('#results-list');
            resultsList.empty();

            if (query.trim() === '') {
                $('#search-results').hide();
                $('#post-list').show();
                return;
            }

            let url;
            switch (currentFilter) {
                case 'users':
                    url = '{{ route('search.users') }}';
                    break;
                case 'posts':
                    url = '{{ route('search.all.posts') }}';
                    break;
                case 'posts_with_photo':
                    url = '{{ route('search.posts.with.photo') }}';
                    break;
                case 'posts_with_video':
                    url = '{{ route('search.posts.with.video') }}';
                    break;
                default:
                    url = '{{ route('search.all') }}';
            }

            $.ajax({
                url: url,
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    $('#results-list').empty();

                    if (response.data.users) {
                        response.data.users.forEach(function(user) {
                            let profileImage = user.media && user.media.length > 0 ?
                                user.media.find(media => media.type === 'user_profile_image') : null;
                            let imageUrl = profileImage ?
                                `{{ asset('storage/photos/') }}/${profileImage.URL}` :
                                '{{ asset('/PostBlug/default-profile .png') }}';

                            const boldedName = user.name.replace(new RegExp(query, 'gi'), (match) => `<strong>${match}</strong>`);
                            resultsList.append(`
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <img src="${imageUrl}" alt="Profile photo" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: fill; margin-right: 10px;">
                                        <span><strong></strong> ${boldedName}</span>
                                    </div>
                                </li>
                            `);
                        });
                    }
                    if (response.data.posts) {
                        response.data.posts.forEach(function(post) {
                            let profileImage = post.user.media && post.user.media.length > 0 ?
                                post.user.media.find(media => media.type === 'user_profile_image') : null;
                            let imageUrl = profileImage ?
                                `{{ asset('storage/photos/') }}/${profileImage.URL}` :
                                '{{ asset('/PostBlug/default-profile .png') }}';

                            let postHtml = `
                                <div class="post">
                                    <div class="post-header">
                                        <div class="d-flex align-items-center">
                                            <img src="${imageUrl}" alt="Profile photo" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: fill; margin-right: 10px;">
                                            <span>${post.user.name}</span>
                                        </div>
                                    </div>
                                    <div class="post-content">
                                        <p>${post.description.replace(new RegExp(query, 'gi'), (match) => `<strong>${match}</strong>`)}</p>
                                    </div>
                                    <div class="post-media">
                                        ${post.media.length > 1 ? `
                                            <div id="postMediaCarousel-${post.id}" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    ${post.media.map((media, index) => `
                                                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                                            ${media.type === 'post_image' ? `
                                                                <img src="{{ asset('storage/photos/') }}/${media.URL}" alt="Post Photo" class="d-block w-100" style="max-width: 250%; height: 500px; object-fit: contain;">
                                                            ` : `
                                                                <video class="d-block w-100" controls style="max-width: 250%; height: 500px; object-fit: contain;">
                                                                    <source src="{{ asset('storage/videos/') }}/${media.URL}" type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            `}
                                                        </div>
                                                    `).join('')}
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#postMediaCarousel-${post.id}" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: gray;"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#postMediaCarousel-${post.id}" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: gray;"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            </div>
                                        ` : post.media.map(media => `
                                            ${media.type === 'post_image' ? `
                                                <img src="{{ asset('storage/photos/') }}/${media.URL}" alt="Post Photo" class="d-block w-100" style="max-width: 150%; height: 500px; object-fit: contain;">
                                            ` : `
                                                <video class="d-block w-100" controls style="max-width: 150%; height: 500px; object-fit: contain;">
                                                    <source src="{{ asset('storage/videos/') }}/${media.URL}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            `}
                                        `).join('')}
                                    </div>
                                    <div class="button-group">
                                        <button id="toggleCommentForm" class="btn-post" data-id="${post.id}">
                                            Comment
                                        </button>
                                    </div>
                                </div>
                            `;
                            resultsList.append(`<li class="list-group-item">${postHtml}</li>`);
                        });
                    }
                    if (!response.data.users && !response.data.posts) {
                        resultsList.append('<li class="list-group-item">No results found.</li>');
                    }
                    $('#search-results').show();
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                }
            });
        }
    });
</script>
