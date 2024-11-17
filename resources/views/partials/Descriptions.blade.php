<!-- Description Section -->
<div class="descriptions-section" id="descriptionsSection">
    <div class="description">
        <button id="descriptionButton"  type="button" class="btn-post" data-bs-toggle="modal" data-bs-target="#descriptionModal" style="cursor: pointer; margin-top: 45px; ">
            Manage Descriptions
        </button>
    </div>
    <!-- Display existing descriptions -->
    <div class="description" id="descriptionsList">
        <div class="descriptions-list">
            @foreach ($descriptions as $description)
                <p class="font-italic mb-1">{{ $description->text }}</p>
            @endforeach
        </div>
    </div>

</div>
<!-- Description Modal -->
<div class="modal fade" id="descriptionModal" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">Manage Descriptions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add New Description Form -->
                <form id="addDescriptionForm">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="text" class="form-control" placeholder="Add new description">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>

                <!-- Existing Descriptions List -->
                <div id="modalDescriptionsList">
                    @foreach ($descriptions as $description)
                        <div class="description-item">
                            <span class="description-text">{{ $description->text }}</span>
                            <div class="description-actions">
                                <button class="btn-edit-description" data-id="{{ $description->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete-description" data-id="{{ $description->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveDescriptionChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>


<script>


// When modal opens, store original state
$('#descriptionModal').on('show.bs.modal', function () {
    originalDescriptions = $('#modalDescriptionsList').html();
    tempDescriptions = [];
});

// Add new description (temporary)
$('#addDescriptionForm').on('submit', function(e) {
    e.preventDefault();
    const newText = $(this).find('input[name="text"]').val();

    if (!newText.trim()) {
        alert('Please enter a description');
        return;
    }

    // Create temporary description with temporary ID
    const tempId = 'temp_' + Date.now();
    const newDescription = `
        <div class="description-item">
            <span class="description-text">${newText}</span>
            <div class="description-actions">
                <button class="btn-edit-description" data-id="${tempId}">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-delete-description" data-id="${tempId}">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    // Add to temporary list
    tempDescriptions.push({
        id: tempId,
        text: newText,
        action: 'add'
    });

    $('#modalDescriptionsList').append(newDescription);
    $(this)[0].reset();
});

// Edit description (temporary)
$(document).on('click', '.btn-edit-description', function() {
    var id = $(this).data('id');
    var descriptionItem = $(this).closest('.description-item');
    var currentText = descriptionItem.find('.description-text').text().trim();

    descriptionItem.html(`
        <div class="input-group">
            <input type="text" class="form-control edit-description-input" value="${currentText}">
            <button class="btn btn-success btn-save-edit" data-id="${id}">Save</button>
            <button class="btn btn-danger btn-cancel-edit">Cancel</button>
        </div>
    `);
});

// Save edit (temporary)
$(document).on('click', '.btn-save-edit', function() {
    var id = $(this).data('id');
    var newText = $(this).siblings('.edit-description-input').val().trim();

    if (!newText) {
        alert('Description cannot be empty');
        return;
    }

    tempDescriptions.push({
        id: id,
        text: newText,
        action: 'edit'
    });

    var descriptionItem = $(this).closest('.description-item');
    descriptionItem.html(`
        <span class="description-text">${newText}</span>
        <div class="description-actions">
            <button class="btn-edit-description" data-id="${id}">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn-delete-description" data-id="${id}">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `);
});

// Cancel edit
$(document).on('click', '.btn-cancel-edit', function() {
    var descriptionItem = $(this).closest('.description-item');
    descriptionItem.html(originalDescriptions);
});

// Delete description (temporary)
$(document).on('click', '.btn-delete-description', function() {
    var id = $(this).data('id');
    var descriptionItem = $(this).closest('.description-item');

    tempDescriptions.push({
        id: id,
        action: 'delete'
    });

    descriptionItem.fadeOut(400, function() {
        $(this).remove();
    });
});

// Handle cancel button (restore original state)
$('.btn-secondary[data-bs-dismiss="modal"]').on('click', function() {
    $('#modalDescriptionsList').html(originalDescriptions);
    tempDescriptions = [];
});

// Handle save all changes
$('#saveDescriptionChanges').on('click', function() {
    if (tempDescriptions.length === 0) {
        alert('No changes to save');
        return;
    }

    // Disable the save button to prevent double submission
    $(this).prop('disabled', true);

    $.ajax({
        url: '{{ route("profile.save-descriptions") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            changes: tempDescriptions
        },
        success: function(response) {
            if (response.success) {
                // Update the descriptions section with the new HTML
                $('#descriptionsSection').html(response.html);

                // Show success message
                alert('Changes saved successfully!');

                // Hide modal and cleanup


                // Reset temporary changes
                tempDescriptions = [];

            } else {
                alert(response.message || 'Error saving changes');
            }
        },
        error: function(xhr) {
            alert('Error saving changes');
            console.error(xhr.responseText);
        },
        complete: function() {
            // Re-enable the save button

            $('#descriptionModal').modal('hide');
            $('#descriptionModal').remove();
            $('#saveDescriptionChanges').prop('disabled', false);
        }
    });
});

</script>
