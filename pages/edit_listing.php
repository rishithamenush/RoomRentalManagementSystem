<?php 
session_start();
if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'owner') {
    header('Location: login.php');
    exit();
}

include 'config/db_connect.php';

$listing_id = $_GET['id'] ?? null;
if (!$listing_id) {
    header('Location: index.php?page=listings');
    exit();
}

// Get listing details
$listing_sql = "SELECT * FROM listings WHERE id = ? AND owner_id = ?";
$listing_stmt = $conn->prepare($listing_sql);
$listing_stmt->bind_param("ii", $listing_id, $_SESSION['login_id']);
$listing_stmt->execute();
$listing_result = $listing_stmt->get_result();

if ($listing_result->num_rows == 0) {
    header('Location: index.php?page=listings');
    exit();
}

$listing = $listing_result->fetch_assoc();

// Get existing images
$media_sql = "SELECT * FROM media WHERE listing_id = ? ORDER BY position";
$media_stmt = $conn->prepare($media_sql);
$media_stmt->bind_param("i", $listing_id);
$media_stmt->execute();
$media_result = $media_stmt->get_result();
$existing_images = $media_result->fetch_all(MYSQLI_ASSOC);
?>

<style>
    .form-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 30px;
        margin-bottom: 30px;
    }
    .form-group {
        margin-bottom: 25px;
    }
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }
    .form-control, .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .form-control.is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }
    .facility-checkbox {
        display: inline-block;
        margin-right: 15px;
        margin-bottom: 10px;
    }
    .facility-checkbox input[type="checkbox"] {
        margin-right: 5px;
    }
    .image-upload-area {
        border: 2px dashed #e9ecef;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        background: #f8f9fa;
        transition: border-color 0.3s ease;
        cursor: pointer;
    }
    .image-upload-area:hover {
        border-color: #667eea;
    }
    .image-upload-area.dragover {
        border-color: #667eea;
        background: #f0f4ff;
    }
    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }
    .image-preview-item {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
    }
    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .image-preview-item .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        cursor: pointer;
    }
    .existing-image-item {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
        margin: 5px;
    }
    .existing-image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .existing-image-item .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        cursor: pointer;
    }
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s ease;
        width: 100%;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
    }
    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Edit Listing</h2>
        </div>
    </div>

    <form id="edit-listing-form" enctype="multipart/form-data">
        <input type="hidden" id="listing_id" name="listing_id" value="<?php echo $listing['id'] ?>">
        
        <div class="form-container">
            <h4 class="mb-4">Basic Information</h4>
            
            <div class="form-group">
                <label for="title" class="form-label">Property Title *</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($listing['title']) ?>" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Address *</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($listing['address']) ?></textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="price_lkr" class="form-label">Monthly Rent (LKR) *</label>
                        <input type="number" class="form-control" id="price_lkr" name="price_lkr" min="0" step="0.01" value="<?php echo $listing['price_lkr'] ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="room_type" class="form-label">Room Type *</label>
                        <select class="form-select" id="room_type" name="room_type" required>
                            <option value="">Select Room Type</option>
                            <option value="single" <?php echo $listing['room_type'] == 'single' ? 'selected' : '' ?>>Single Room</option>
                            <option value="shared" <?php echo $listing['room_type'] == 'shared' ? 'selected' : '' ?>>Shared Room</option>
                            <option value="studio" <?php echo $listing['room_type'] == 'studio' ? 'selected' : '' ?>>Studio</option>
                            <option value="apartment" <?php echo $listing['room_type'] == 'apartment' ? 'selected' : '' ?>>Apartment</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gender_pref" class="form-label">Gender Preference *</label>
                        <select class="form-select" id="gender_pref" name="gender_pref" required>
                            <option value="">Select Preference</option>
                            <option value="male" <?php echo $listing['gender_pref'] == 'male' ? 'selected' : '' ?>>Male Only</option>
                            <option value="female" <?php echo $listing['gender_pref'] == 'female' ? 'selected' : '' ?>>Female Only</option>
                            <option value="any" <?php echo $listing['gender_pref'] == 'any' ? 'selected' : '' ?>>Any Gender</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lat" class="form-label">Latitude (Optional)</label>
                        <input type="number" class="form-control" id="lat" name="lat" step="any" value="<?php echo $listing['lat'] ?>" placeholder="e.g., 6.9271">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="lng" class="form-label">Longitude (Optional)</label>
                <input type="number" class="form-control" id="lng" name="lng" step="any" value="<?php echo $listing['lng'] ?>" placeholder="e.g., 79.8612">
            </div>
        </div>

        <div class="form-container">
            <h4 class="mb-4">Property Details</h4>
            
            <div class="form-group">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($listing['description']) ?></textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Facilities Available</label>
                <?php 
                $facilities = json_decode($listing['facilities'], true) ?? [];
                $facility_options = ['wifi', 'ac', 'parking', 'laundry', 'kitchen', 'security', 'gym', 'pool', 'balcony', 'furnished'];
                foreach ($facility_options as $facility): 
                ?>
                <div class="facility-checkbox">
                    <input type="checkbox" id="<?php echo $facility ?>" name="facilities[]" value="<?php echo $facility ?>" <?php echo in_array($facility, $facilities) ? 'checked' : '' ?>>
                    <label for="<?php echo $facility ?>"><?php echo ucfirst(str_replace('_', ' ', $facility)) ?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-container">
            <h4 class="mb-4">Property Images</h4>
            
            <!-- Existing Images -->
            <?php if (!empty($existing_images)): ?>
            <div class="mb-4">
                <h6>Current Images:</h6>
                <div class="image-preview">
                    <?php foreach ($existing_images as $image): ?>
                    <div class="existing-image-item" data-image-id="<?php echo $image['id'] ?>">
                        <img src="<?php echo $image['url'] ?>" alt="Existing image">
                        <button type="button" class="remove-btn" onclick="removeExistingImage(<?php echo $image['id'] ?>)">×</button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- New Image Upload -->
            <div class="image-upload-area" id="image-upload-area">
                <i class="fa fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                <h5>Add More Images</h5>
                <p class="text-muted">Drag and drop images here or click to browse</p>
                <input type="file" id="images" name="images[]" multiple accept="image/*" style="display: none;">
                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('images').click()">
                    Choose Images
                </button>
            </div>

            <div class="image-preview" id="image-preview"></div>
        </div>

        <div class="form-container">
            <div class="d-flex justify-content-between">
                <a href="index.php?page=listings" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn-submit">Update Listing</button>
            </div>
        </div>
    </form>
</div>

<script>
let removedImages = [];

$(document).ready(function(){
    // Image upload handling
    $('#image-upload-area').on('click', function(){
        $('#images').click();
    });

    $('#images').on('change', function(){
        handleImageUpload(this.files);
    });

    // Drag and drop
    $('#image-upload-area').on('dragover', function(e){
        e.preventDefault();
        $(this).addClass('dragover');
    });

    $('#image-upload-area').on('dragleave', function(e){
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    $('#image-upload-area').on('drop', function(e){
        e.preventDefault();
        $(this).removeClass('dragover');
        handleImageUpload(e.originalEvent.dataTransfer.files);
    });

    // Form submission
    $('#edit-listing-form').submit(function(e){
        e.preventDefault();
        submitListing();
    });
});

function handleImageUpload(files) {
    const preview = $('#image-preview');
    
    Array.from(files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewItem = $(`
                    <div class="image-preview-item">
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-btn" onclick="removeNewImage(${index})">×</button>
                    </div>
                `);
                preview.append(previewItem);
            };
            reader.readAsDataURL(file);
        }
    });
}

function removeNewImage(index) {
    $(`.image-preview-item`).eq(index).remove();
}

function removeExistingImage(imageId) {
    if (confirm('Are you sure you want to remove this image?')) {
        removedImages.push(imageId);
        $(`.existing-image-item[data-image-id="${imageId}"]`).remove();
    }
}

function validateForm() {
    let isValid = true;
    
    // Clear previous validation errors
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    const requiredFields = ['title', 'address', 'price_lkr', 'room_type', 'gender_pref', 'description'];
    requiredFields.forEach(field => {
        const value = $(`#${field}`).val().trim();
        if (!value) {
            $(`#${field}`).addClass('is-invalid');
            $(`#${field}`).siblings('.invalid-feedback').text('This field is required');
            isValid = false;
        }
    });

    // Validate price
    const price = parseFloat($('#price_lkr').val());
    if (price <= 0) {
        $('#price_lkr').addClass('is-invalid');
        $('#price_lkr').siblings('.invalid-feedback').text('Price must be greater than 0');
        isValid = false;
    }

    return isValid;
}

function submitListing() {
    if (!validateForm()) {
        return;
    }

    const formData = new FormData($('#edit-listing-form')[0]);
    
    // Add facilities as JSON
    const facilities = [];
    $('input[name="facilities[]"]:checked').each(function(){
        facilities.push($(this).val());
    });
    formData.append('facilities_json', JSON.stringify(facilities));
    
    // Add removed images
    formData.append('removed_images', JSON.stringify(removedImages));

    // Debug: Log form data
    console.log('Form data being submitted:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    $('.btn-submit').prop('disabled', true).text('Updating...');

    $.ajax({
        url: 'api/ajax.php?action=update_listing',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Response received:', response);
            try {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Listing updated successfully!');
                    window.location.href = 'index.php?page=listings';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch(e) {
                console.log('JSON parse error:', e);
                alert('Listing updated successfully!');
                window.location.href = 'index.php?page=listings';
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX error:', xhr.responseText);
            alert('Failed to update listing. Please try again.');
        },
        complete: function() {
            $('.btn-submit').prop('disabled', false).text('Update Listing');
        }
    });
}
</script>

