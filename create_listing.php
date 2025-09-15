<?php include 'db_connect.php' ?>
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
    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }
    .step {
        display: flex;
        align-items: center;
        margin: 0 20px;
    }
    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e9ecef;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 10px;
    }
    .step.active .step-number {
        background: #667eea;
        color: white;
    }
    .step.completed .step-number {
        background: #28a745;
        color: white;
    }
    .step-label {
        font-weight: 600;
        color: #666;
    }
    .step.active .step-label {
        color: #667eea;
    }
    .step.completed .step-label {
        color: #28a745;
    }
    .form-section {
        display: none;
    }
    .form-section.active {
        display: block;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Create New Listing</h2>
        </div>
    </div>

    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step active" data-step="1">
            <div class="step-number">1</div>
            <div class="step-label">Basic Info</div>
        </div>
        <div class="step" data-step="2">
            <div class="step-number">2</div>
            <div class="step-label">Details</div>
        </div>
        <div class="step" data-step="3">
            <div class="step-number">3</div>
            <div class="step-label">Images</div>
        </div>
        <div class="step" data-step="4">
            <div class="step-number">4</div>
            <div class="step-label">Review</div>
        </div>
    </div>

    <form id="create-listing-form" enctype="multipart/form-data">
        <!-- Step 1: Basic Information -->
        <div class="form-section active" id="step-1">
            <div class="form-container">
                <h4 class="mb-4">Basic Information</h4>
                
                <div class="form-group">
                    <label for="title" class="form-label">Property Title *</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address *</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price_lkr" class="form-label">Monthly Rent (LKR) *</label>
                            <input type="number" class="form-control" id="price_lkr" name="price_lkr" min="0" step="0.01" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="room_type" class="form-label">Room Type *</label>
                            <select class="form-select" id="room_type" name="room_type" required>
                                <option value="">Select Room Type</option>
                                <option value="single">Single Room</option>
                                <option value="shared">Shared Room</option>
                                <option value="studio">Studio</option>
                                <option value="apartment">Apartment</option>
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
                                <option value="male">Male Only</option>
                                <option value="female">Female Only</option>
                                <option value="any">Any Gender</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lat" class="form-label">Latitude (Optional)</label>
                            <input type="number" class="form-control" id="lat" name="lat" step="any" placeholder="e.g., 6.9271">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="lng" class="form-label">Longitude (Optional)</label>
                    <input type="number" class="form-control" id="lng" name="lng" step="any" placeholder="e.g., 79.8612">
                </div>
            </div>
        </div>

        <!-- Step 2: Details -->
        <div class="form-section" id="step-2">
            <div class="form-container">
                <h4 class="mb-4">Property Details</h4>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Facilities Available</label>
                    <div class="facility-checkbox">
                        <input type="checkbox" id="wifi" name="facilities[]" value="wifi">
                        <label for="wifi">Wi-Fi</label>
                    </div>
                    <div class="facility-checkbox">
                        <input type="checkbox" id="ac" name="facilities[]" value="ac">
                        <label for="ac">Air Conditioning</label>
                    </div>
                    <div class="facility-checkbox">
                        <input type="checkbox" id="parking" name="facilities[]" value="parking">
                        <label for="parking">Parking</label>
                    </div>
                    <div class="facility-checkbox">
                        <input type="checkbox" id="laundry" name="facilities[]" value="laundry">
                        <label for="laundry">Laundry</label>
                    </div>
                    <div class="facility-checkbox">
                        <input type="checkbox" id="kitchen" name="facilities[]" value="kitchen">
                        <label for="kitchen">Kitchen</label>
                    </div>
                    <div class="facility-checkbox">
                        <input type="checkbox" id="security" name="facilities[]" value="security">
                        <label for="security">Security</label>
                    </div>
                    <div class="facility-checkbox">
                        <input type="checkbox" id="gym" name="facilities[]" value="gym">
                        <label for="gym">Gym</label>
                    </div>
                    <div class="facility-checkbox">
                        <input type="checkbox" id="pool" name="facilities[]" value="pool">
                        <label for="pool">Swimming Pool</label>
                    </div>
                    <div class="facility-checkbox">
                        <input type="checkbox" id="balcony" name="facilities[]" value="balcony">
                        <label for="balcony">Balcony</label>
                    </div>
                    <div class="facility-checkbox">
                        <input type="checkbox" id="furnished" name="facilities[]" value="furnished">
                        <label for="furnished">Furnished</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Images -->
        <div class="form-section" id="step-3">
            <div class="form-container">
                <h4 class="mb-4">Property Images</h4>
                
                <div class="image-upload-area" id="image-upload-area">
                    <i class="fa fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                    <h5>Upload Property Images</h5>
                    <p class="text-muted">Drag and drop images here or click to browse</p>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" style="display: none;">
                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('images').click()">
                        Choose Images
                    </button>
                </div>

                <div class="image-preview" id="image-preview"></div>
            </div>
        </div>

        <!-- Step 4: Review -->
        <div class="form-section" id="step-4">
            <div class="form-container">
                <h4 class="mb-4">Review Your Listing</h4>
                <div id="listing-preview"></div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="form-container">
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" id="prev-btn" style="display: none;">Previous</button>
                <button type="button" class="btn btn-primary" id="next-btn">Next</button>
                <button type="submit" class="btn btn-submit" id="submit-btn" style="display: none;">Create Listing</button>
            </div>
        </div>
    </form>
</div>

<script>
let currentStep = 1;
const totalSteps = 4;

$(document).ready(function(){
    // Step navigation
    $('#next-btn').click(function(){
        if (validateCurrentStep()) {
            nextStep();
        }
    });

    $('#prev-btn').click(function(){
        prevStep();
    });

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
    $('#create-listing-form').submit(function(e){
        e.preventDefault();
        submitListing();
    });
});

function validateCurrentStep() {
    let isValid = true;
    
    // Clear previous validation errors
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    if (currentStep === 1) {
        const requiredFields = ['title', 'address', 'price_lkr', 'room_type', 'gender_pref'];
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
    } else if (currentStep === 2) {
        const description = $('#description').val().trim();
        if (!description) {
            $('#description').addClass('is-invalid');
            $('#description').siblings('.invalid-feedback').text('Description is required');
            isValid = false;
        }
    }

    return isValid;
}

function nextStep() {
    if (currentStep < totalSteps) {
        // Mark current step as completed
        $(`.step[data-step="${currentStep}"]`).removeClass('active').addClass('completed');
        
        // Hide current section
        $(`#step-${currentStep}`).removeClass('active');
        
        currentStep++;
        
        // Show next section
        $(`#step-${currentStep}`).addClass('active');
        $(`.step[data-step="${currentStep}"]`).addClass('active');
        
        // Update buttons
        updateButtons();
        
        // Generate preview if on last step
        if (currentStep === totalSteps) {
            generatePreview();
        }
    }
}

function prevStep() {
    if (currentStep > 1) {
        // Mark current step as inactive
        $(`.step[data-step="${currentStep}"]`).removeClass('active');
        
        // Hide current section
        $(`#step-${currentStep}`).removeClass('active');
        
        currentStep--;
        
        // Show previous section
        $(`#step-${currentStep}`).addClass('active');
        $(`.step[data-step="${currentStep}"]`).removeClass('completed').addClass('active');
        
        // Update buttons
        updateButtons();
    }
}

function updateButtons() {
    $('#prev-btn').toggle(currentStep > 1);
    $('#next-btn').toggle(currentStep < totalSteps);
    $('#submit-btn').toggle(currentStep === totalSteps);
}

function handleImageUpload(files) {
    const preview = $('#image-preview');
    
    Array.from(files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewItem = $(`
                    <div class="image-preview-item">
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-btn" onclick="removeImage(${index})">×</button>
                    </div>
                `);
                preview.append(previewItem);
            };
            reader.readAsDataURL(file);
        }
    });
}

function removeImage(index) {
    $(`.image-preview-item`).eq(index).remove();
}

function generatePreview() {
    const formData = new FormData($('#create-listing-form')[0]);
    const facilities = [];
    $('input[name="facilities[]"]:checked').each(function(){
        facilities.push($(this).val());
    });

    const preview = `
        <div class="row">
            <div class="col-md-6">
                <h5>Basic Information</h5>
                <p><strong>Title:</strong> ${formData.get('title')}</p>
                <p><strong>Address:</strong> ${formData.get('address')}</p>
                <p><strong>Price:</strong> LKR ${parseFloat(formData.get('price_lkr')).toLocaleString()}/month</p>
                <p><strong>Room Type:</strong> ${formData.get('room_type')}</p>
                <p><strong>Gender Preference:</strong> ${formData.get('gender_pref')}</p>
            </div>
            <div class="col-md-6">
                <h5>Details</h5>
                <p><strong>Description:</strong></p>
                <p>${formData.get('description')}</p>
                <p><strong>Facilities:</strong> ${facilities.join(', ') || 'None selected'}</p>
            </div>
        </div>
    `;
    
    $('#listing-preview').html(preview);
}

function submitListing() {
    if (!validateCurrentStep()) {
        return;
    }

    const formData = new FormData($('#create-listing-form')[0]);
    
    // Add facilities as JSON
    const facilities = [];
    $('input[name="facilities[]"]:checked').each(function(){
        facilities.push($(this).val());
    });
    formData.append('facilities_json', JSON.stringify(facilities));

    // Debug: Log form data
    console.log('Form data being submitted:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    $('#submit-btn').prop('disabled', true).text('Creating...');

    $.ajax({
        url: 'ajax.php?action=create_listing',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Response received:', response);
            try {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Listing created successfully! It will be reviewed by our team before going live.');
                    window.location.href = 'index.php?page=listings';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch(e) {
                console.log('JSON parse error:', e);
                alert('Listing created successfully!');
                window.location.href = 'index.php?page=listings';
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX error:', xhr.responseText);
            alert('Failed to create listing. Please try again.');
        },
        complete: function() {
            $('#submit-btn').prop('disabled', false).text('Create Listing');
        }
    });
}
</script>
