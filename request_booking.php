<?php include 'db_connect.php' ?>
<style>
    .booking-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 30px;
        margin-bottom: 30px;
    }
    .listing-preview {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .listing-image {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        object-fit: cover;
        float: left;
        margin-right: 20px;
    }
    .listing-info h4 {
        margin: 0 0 10px 0;
        color: #333;
    }
    .listing-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #28a745;
        margin-bottom: 10px;
    }
    .listing-location {
        color: #666;
        margin-bottom: 10px;
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
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    .form-control:focus {
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
    .booking-summary {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .summary-row.total {
        border-top: 2px solid #dee2e6;
        padding-top: 10px;
        font-weight: 700;
        font-size: 1.1rem;
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
    .alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Request Booking</h2>
        </div>
    </div>

    <?php
    // Check if user is logged in
    if (!isset($_SESSION['login_id'])) {
        echo '<div class="alert alert-danger">Please login to request a booking.</div>';
        echo '<script>setTimeout(function(){ window.location.href = "login.php"; }, 2000);</script>';
        exit;
    }

    // Check if user is a student
    if ($_SESSION['login_role'] != 'student') {
        echo '<div class="alert alert-danger">Only students can request bookings.</div>';
        echo '<script>setTimeout(function(){ window.location.href = "index.php?page=dashboard"; }, 2000);</script>';
        exit;
    }

    $listing_id = $_GET['listing_id'] ?? null;
    if (!$listing_id) {
        echo '<div class="alert alert-danger">No listing specified.</div>';
        echo '<script>setTimeout(function(){ window.location.href = "index.php?page=search"; }, 2000);</script>';
        exit;
    }

    // Get listing details
    $listing_sql = "SELECT l.*, u.email as owner_email,
                           (SELECT url FROM media WHERE listing_id = l.id ORDER BY position LIMIT 1) as main_image
                    FROM listings l 
                    INNER JOIN users u ON l.owner_id = u.id 
                    WHERE l.id = ? AND l.availability_status = 'active'";
    
    $stmt = $conn->prepare($listing_sql);
    $stmt->bind_param("i", $listing_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo '<div class="alert alert-danger">Listing not found or not available.</div>';
        echo '<script>setTimeout(function(){ window.location.href = "index.php?page=search"; }, 2000);</script>';
        exit;
    }
    
    $listing = $result->fetch_assoc();
    ?>

    <!-- Listing Preview -->
    <div class="listing-preview">
        <div class="clearfix">
            <?php if ($listing['main_image']): ?>
                <img src="assets/uploads/<?php echo $listing['main_image'] ?>" alt="<?php echo htmlspecialchars($listing['title']) ?>" class="listing-image">
            <?php else: ?>
                <div class="listing-image" style="background: #e9ecef; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-home fa-2x text-muted"></i>
                </div>
            <?php endif; ?>
            
            <div class="listing-info">
                <h4><?php echo htmlspecialchars($listing['title']) ?></h4>
                <div class="listing-price">LKR <?php echo number_format($listing['price_lkr'], 2) ?>/month</div>
                <div class="listing-location">
                    <i class="fa fa-map-marker-alt"></i> <?php echo htmlspecialchars($listing['address']) ?>
                </div>
                <div class="text-muted">
                    <i class="fa fa-home"></i> <?php echo ucfirst($listing['room_type']) ?> • 
                    <i class="fa fa-venus-mars"></i> <?php echo ucfirst($listing['gender_pref']) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="booking-container">
        <h4 class="mb-4">Booking Details</h4>
        
        <div id="alert-container"></div>
        
        <form id="booking-form">
            <input type="hidden" name="listing_id" value="<?php echo $listing_id ?>">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="start_date" class="form-label">Move-in Date *</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="end_date" class="form-label">Move-out Date (Optional)</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                        <small class="text-muted">Leave blank for indefinite stay</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="student_note" class="form-label">Message to Owner</label>
                <textarea class="form-control" id="student_note" name="student_note" rows="4" placeholder="Tell the owner about yourself, your study plans, or any special requirements..."></textarea>
            </div>

            <!-- Booking Summary -->
            <div class="booking-summary">
                <h5>Booking Summary</h5>
                <div class="summary-row">
                    <span>Monthly Rent:</span>
                    <span>LKR <?php echo number_format($listing['price_lkr'], 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Duration:</span>
                    <span id="duration-display">-</span>
                </div>
                <div class="summary-row total">
                    <span>Total Amount:</span>
                    <span id="total-amount">LKR 0.00</span>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                <strong>Important:</strong> Your booking request will be sent to the property owner for approval. 
                You will be notified once they respond. The owner may contact you for additional information.
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa fa-paper-plane"></i> Send Booking Request
            </button>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    $('#start_date').attr('min', today);
    $('#end_date').attr('min', today);

    // Calculate total when dates change
    $('#start_date, #end_date').on('change', function(){
        calculateTotal();
    });

    // Form submission
    $('#booking-form').submit(function(e){
        e.preventDefault();
        submitBooking();
    });
});

function calculateTotal() {
    const startDate = $('#start_date').val();
    const endDate = $('#end_date').val();
    const monthlyRent = <?php echo $listing['price_lkr'] ?>;

    if (!startDate) {
        $('#duration-display').text('-');
        $('#total-amount').text('LKR 0.00');
        return;
    }

    if (!endDate) {
        // Indefinite stay - show monthly rent only
        $('#duration-display').text('Indefinite');
        $('#total-amount').text('LKR ' + monthlyRent.toLocaleString() + '/month');
        return;
    }

    const start = new Date(startDate);
    const end = new Date(endDate);

    if (end <= start) {
        $('#end_date').addClass('is-invalid');
        $('#end_date').siblings('.invalid-feedback').text('End date must be after start date');
        return;
    } else {
        $('#end_date').removeClass('is-invalid');
        $('#end_date').siblings('.invalid-feedback').text('');
    }

    // Calculate months
    const months = Math.ceil((end - start) / (1000 * 60 * 60 * 24 * 30));
    const totalAmount = months * monthlyRent;

    $('#duration-display').text(months + ' month(s)');
    $('#total-amount').text('LKR ' + totalAmount.toLocaleString());
}

function submitBooking() {
    // Validate form
    if (!validateForm()) {
        return;
    }

    const formData = new FormData($('#booking-form')[0]);
    const $btn = $('.btn-submit');
    const originalText = $btn.html();

    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending Request...');

    $.ajax({
        url: 'ajax.php?action=request_booking',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            try {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    showAlert('Booking request sent successfully! The owner will be notified.', 'success');
                    setTimeout(function(){
                        window.location.href = 'index.php?page=bookings';
                    }, 2000);
                } else {
                    showAlert('Error: ' + result.message, 'danger');
                }
            } catch(e) {
                showAlert('Booking request sent successfully!', 'success');
                setTimeout(function(){
                    window.location.href = 'index.php?page=bookings';
                }, 2000);
            }
        },
        error: function() {
            showAlert('Failed to send booking request. Please try again.', 'danger');
        },
        complete: function() {
            $btn.prop('disabled', false).html(originalText);
        }
    });
}

function validateForm() {
    let isValid = true;
    
    // Clear previous validation errors
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    const startDate = $('#start_date').val();
    const endDate = $('#end_date').val();

    if (!startDate) {
        $('#start_date').addClass('is-invalid');
        $('#start_date').siblings('.invalid-feedback').text('Start date is required');
        isValid = false;
    }

    if (endDate && endDate <= startDate) {
        $('#end_date').addClass('is-invalid');
        $('#end_date').siblings('.invalid-feedback').text('End date must be after start date');
        isValid = false;
    }

    return isValid;
}

function showAlert(message, type) {
    var alertHtml = '<div class="alert alert-' + type + '">' + message + '</div>';
    $('#alert-container').html(alertHtml);
}
</script>
