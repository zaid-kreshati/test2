<div class="center">

    <div id="post-list">

        @if ($post_list->isEmpty())
            <p>No Posts found for this user.</p>
        @else
            @foreach ($post_list as $post)
                <tr id="post-{{ $post->id }}">

                    @if (!$home)
                        @php
                            $isUserPost = false;
                            foreach ($post->tag as $tag) {
                                if ($tag->user_id == $user_id) {
                                    $isUserPost = true;
                                    break;
                                }
                            }
                        @endphp

                        @if (!$isUserPost)
                            @continue
                        @endif
                    @endif


                    <div class="board">
                        @foreach ($post->tag as $tag)
                            @if ($tag->user_id == $post->owner_id)
                                <!-- Profile Image -->
                                @if ($tag->user->media->isNotEmpty())
                                    @foreach ($tag->user->media as $media)
                                        @if ($media->type == 'user_profile_image')
                                            <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Profile photo"
                                                class="img-fluid rounded-circle"
                                                style="width: 90px; height: 90px; object-fit: fill; margin-right: 750px;">
                                        @endif
                                    @endforeach
                                @else
                                    <img src="{{ asset('/PostBlug/default-profile .png') }}" alt="Profile photo"
                                        class="img-fluid rounded-circle"
                                        style="width: 90px; height: 90px; object-fit: fill; margin-right: 750px;">
                                @endif

                                <div
                                    style="right: 10px; bottom: 50px; font-size: 35px; margin: unset; margin-right: 560px; margin-top: -53px; padding: inherit; position: relative;">
                                    {{ $tag->user->name }}
                                </div>
                            @endif
                        @endforeach


                        <!-- Post Description -->
                        <div class="text-start mt-2 mb-3">
                            <h2 style="margin: 0; text-align: left; padding-left: 10px;">{{ $post->description }}
                            </h2>
                        </div>

                        <!-- Post Media -->
                        @if ($post->media->isNotEmpty())
                            @if ($post->media->count() > 1)
                                <!-- Show carousel for multiple media items -->
                                <div id="postMediaCarousel-{{ $post->id }}" class="carousel slide"
                                    data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach ($post->media as $key => $media)
                                            <!-- Image Slide -->
                                            @if ($media->type == 'post_image')
                                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/photos/' . $media->URL) }}"
                                                        alt="Post Photo" class="d-block w-100"
                                                        style="max-width: 250%; height: 500px; object-fit: contain;">
                                                </div>
                                            @endif

                                            <!-- Video Slide -->
                                            @if ($media->type == 'post_video')
                                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                                    <video class="d-block w-100" controls
                                                        style="max-width: 250%; height: 500px; object-fit: contain;">
                                                        <source src="{{ asset('storage/videos/' . $media->URL) }}"
                                                            type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- Carousel Controls -->
                                    <button class="carousel-control-prev" type="button"
                                        style="left: -40px; margin-top: 250px; margin-bottom: 250px;"
                                        data-bs-target="#postMediaCarousel-{{ $post->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"
                                            style="background-color: gray;"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        style="right: -40px; margin-top: 250px; margin-bottom: 250px; "
                                        data-bs-target="#postMediaCarousel-{{ $post->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"
                                            style="background-color: gray;"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            @else
                                <!-- Show single media item without carousel -->
                                @foreach ($post->media as $media)
                                    @if ($media->type == 'post_image')
                                        <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Post Photo"
                                            class="d-block w-100"
                                            style="max-width: 150%; height: 500px; object-fit: contain;">
                                    @endif

                                    @if ($media->type == 'post_video')
                                        <video class="d-block w-100" controls
                                            style="max-width: 150%; height: 500px; object-fit: contain;">
                                            <source src="{{ asset('storage/videos/' . $media->URL) }}"
                                                type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                @endforeach
                            @endif
                        @endif

                        <div class="button-group">
                            @if (!$home)
                                @if ($post->status == 'published')
                                    <!-- Edit Button -->
                                    <button class="btn-post edit-post-btn" data-id="{{ $post->id }}"
                                        data-description="{{ $post->description }}"
                                        data-category="{{ $post->category_id }}"
                                        data-media='@json($post->media)' data-toggle="modal"
                                        data-target="#editPostModal">
                                        Edit Post
                                    </button>
                                    <!-- Archive Button -->
                                    <button class="btn-post archive-post-btn" data-id="{{ $post->id }}">
                                        Archive Post
                                    </button>
                                @elseif($post->status == 'draft')

                                    <!-- Edit Button -->
                                    <button class="btn-post edit-post-btn" data-id="{{ $post->id }}"
                                        data-description="{{ $post->description }}"
                                        data-category="{{ $post->category_id }}"
                                        data-media='@json($post->media)' data-toggle="modal"
                                        data-target="#editPostModal">
                                        Edit Post
                                    </button>


                                    <!-- Publish Button -->
                                    <button class="btn-post publish-post-btn" data-id="{{ $post->id }}"
                                        data-description="{{ $post->description }}"
                                        data-category="{{ $post->category_id }}"
                                        data-media='@json($post->media)' data-toggle="modal"
                                        data-target="#editPostModal">
                                        Publish Post
                                    </button>
                                    <!-- Delete Button -->
                                    <button class="btn-post delete-post-btn" data-id="{{ $post->id }}">
                                        Delete Post
                                    </button>
                                @endif
                            @elseif($post->status == 'archived')
                                <!-- Delete Button -->
                                <button class="btn-post delete-post-btn" data-id="{{ $post->id }}">
                                    Delete Post
                                </button>
                            @endif

                            <!-- Comment Button -->
                            <button id="toggleCommentForm" class="btn-post " data-id="{{ $post->id }}">
                                Comment
                            </button>
                            @include('partials.comment')

                            {{-- <div class="commentFormContainer" id="commentForm-{{ $post->id }}">
                                @include('partials.comment', ['post' => $post])
                            </div> --}}
                        </div>


                        <hr style="margin-top: 20px;">

                    </div>
                </tr>
            @endforeach


        @endif
        <!-- Loading Spinner -->
        <div id="loading-spinner" class="text-center" style="display: content;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

    </div>
</div>

