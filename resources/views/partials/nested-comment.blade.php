<div id="nested-comments-section-{{ $comment->id }}">
    @if ($nestedComments)
    @foreach ($nestedComments as $nestedComment)
        <div class="comments-section" id="nested-comment">
            <!-- Single Comment -->
            <div class="comment">
                <div class="comment-header">
                    <!-- Profile Image -->
                    @if ($nestedComment->user->media && $nestedComment->user->media->isNotEmpty())
                        @foreach ($nestedComment->user->media as $media)
                            @if ($media->type == 'user_profile_image')
                                <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Profile photo"
                                    class="img-fluid rounded-circle"
                                    style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">
                            @endif
                        @endforeach
                    @else
                        <img src="{{ asset('/PostBlug/default-profile .png') }}" alt="Profile photo"
                            class="img-fluid rounded-circle"
                            style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">
                    @endif

                    <div style="right: 750px; font-size: 15px; margin-top: -40px; position: relative;">
                        {{ $nestedComment->user->name ?? 'Unknown User' }}
                    </div>
                </div>

                <div class="comment-content">
                    {{ $nestedComment->text ?? '' }}
                </div>
            </div>
        </div>
    @endforeach
    @endif
</div>
