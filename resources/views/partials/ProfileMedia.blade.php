<div id="profile-media">
    <!-- Cover Image -->
    @if ($cover_image)
        <img id="coverImage" name="cover_image" src="{{ asset('storage/photos/' . $cover_image->URL) }}" alt="Profile photo"
            class="img-fluid img-thumbnail mt-4 mb-2" style="width: 100%; height: 250px; z-index: 1">
    @else
        <img id="coverImage" src="{{ asset('/PostBlug/default cover image.jpeg') }}" alt="Default cover image"
            class="img-fluid img-thumbnail mt-4 mb-2" style="width: 100%; height: 250px; z-index: 1">
    @endif

    <!-- Cover Image Form -->
    <form id="backgroundPhotoForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="file" name="cover_image" id="backgroundPhotoInput" style="display: none;" accept="image/*">
        <label for="backgroundPhotoInput">
            <img src="{{ asset('PostBlug/takePhoto.png') }}" alt="{{ __('Take Photo') }}" class="icon2">
        </label>

    </form>

    <!-- Loading Spinner -->
    <div id="uploadSpinner"
        style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
        class="position-absolute">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>




    <!-- Profile Image -->
    <div class="rounded-top  d-flex flex-row" style=" height:80px;">
        <div class="position-relative" style="bottom: 130px; left: 10px; z-index: 1;">
            @if ($profile_image)
                <img id="profileImage" name="profile_image" src="{{ asset('storage/photos/' . $profile_image->URL) }}"
                    alt="Profile photo" class="img-fluid img-thumbnail mt-4 mb-2"
                    style="width: 150px; margin-left: -30px;">
            @else
                <img id="profileImage" src="{{ asset('/PostBlug/default-profile .png') }}" alt="Default profile photo"
                    class="img-fluid img-thumbnail mt-4 mb-2" style="width: 150px; margin-left: -30px;">
            @endif

            <!-- Profile Name -->
            <div
                style="position: relative; top: 10px; font-size: 45px; margin: unset; margin-right: -235px; margin-top: -95px; padding: inherit">
                {{ $name }}</div>


            <!-- Profile Image Form -->
            <form id="profilePhotoForm" action="{{ route('profile.upload-profile-image') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="file" name="profile_photo" id="profilePhotoInput" style="display: none;"
                    accept="image/*">
                <label for="profilePhotoInput">
                    <img src="{{ asset('PostBlug/takePhoto.png') }}" alt="{{ __('Take Photo') }}" class="icon">
                </label>
            </form>
        </div>


    </div>
</div>

<script>
    // Background Photo Upload
    var cover_image = document.getElementById('coverImage');
    $('#backgroundPhotoInput').on('change', function() {
        // Create FormData object
        const formData = new FormData();
        var file = $(this)[0].files[0];

        formData.append('cover_image', file);

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        // Show loading spinner
        $('#uploadSpinner').show();

        // Disable the upload button
        $(this).prop('disabled', true);

        // Make AJAX request
        $.ajax({
            url: '{{ route('profile.upload-background-image') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Update the cover image
                    console.log(response.data);
                    const imageUrl = response.data;
                    const fullPath = '{{ asset('storage/photos') }}/' + imageUrl;
                    $('#coverImage').attr('src', fullPath);
                    alert('Background image updated successfully!');
                } else {
                    alert(response.message || 'Error updating background image');
                }
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        alert(errors[key][0]);
                    });
                } else {
                    alert('An error occurred while uploading the image');
                }
            },
            complete: function() {
                // Hide loading spinner
                $('#uploadSpinner').hide();

                // Re-enable the upload button
                $('#backgroundPhotoInput').prop('disabled', false);
            }
        });
    });


    // Profile Photo Upload
    var profile_image = document.getElementById('profileImage');
    $('#profilePhotoInput').on('change', function() {
        // Create FormData object
        const formData = new FormData();
        var file = $(this)[0].files[0];

        formData.append('profile_image', file);

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        // Show loading spinner
        $('#uploadSpinner').show();

        // Disable the upload button
        $(this).prop('disabled', true);

        // Make AJAX request
        $.ajax({
            url: '{{ route('profile.upload-profile-image') }}',
            type: 'POST',
            data: formData,

            processData: false,
            contentType: false,

            success: function(response) {
                if (response.success) {

                    // Update the profile image
                    const imageUrl = response.data.photo_path;
                    const fullPath = '{{ asset('storage/photos') }}/' + imageUrl;
                    console.log(fullPath);
                    $('#profileImage').attr('src', fullPath);
                    alert('Profile image updated successfully!');
                    $('#post-list').html(response.data.html); // Update task list

                } else {
                    alert(response.message || 'Error updating profile image');
                }
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        alert(errors[key][0]);
                    });
                } else {
                    alert('An error occurred while uploading the image');
                }
            },
            complete: function() {
                // Hide loading spinner
                $('#uploadSpinner').hide();

                // Re-enable the upload button
                $('#profilePhotoInput').prop('disabled', false);

                // Reset the form
                $('#profilePhotoForm')[0].reset();
            }
        });
    });
</script>
