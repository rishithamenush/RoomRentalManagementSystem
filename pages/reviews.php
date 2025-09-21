<?php 
session_start();
if (!isset($_SESSION['login_id'])) {
    header('Location: login.php');
    exit();
}

include 'config/db_connect.php';

$user_id = $_SESSION['login_id'];
$user_role = $_SESSION['login_role'];
?>

<style>
    .review-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 25px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .review-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 15px;
    }
    .reviewer-info {
        display: flex;
        align-items: center;
    }
    .reviewer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
        margin-right: 15px;
    }
    .reviewer-details h6 {
        margin: 0;
        font-weight: 600;
        color: #333;
    }
    .reviewer-details small {
        color: #666;
    }
    .rating-stars {
        display: flex;
        align-items: center;
        margin-left: auto;
    }
    .star {
        color: #ffc107;
        font-size: 18px;
        margin-right: 2px;
    }
    .star.empty {
        color: #e9ecef;
    }
    .review-content {
        margin-bottom: 15px;
    }
    .review-text {
        color: #555;
        line-height: 1.6;
        margin-bottom: 10px;
    }
    .review-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        color: #666;
    }
    .review-date {
        font-style: italic;
    }
    .review-actions {
        display: flex;
        gap: 10px;
    }
    .btn-action {
        padding: 5px 12px;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-edit {
        background: #28a745;
        color: white;
    }
    .btn-edit:hover {
        background: #218838;
    }
    .btn-delete {
        background: #dc3545;
        color: white;
    }
    .btn-delete:hover {
        background: #c82333;
    }
    .stats-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 25px;
        text-align: center;
        margin-bottom: 20px;
    }
    .stats-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 5px;
    }
    .stats-label {
        color: #666;
        font-size: 0.9rem;
    }
    .filter-tabs {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 20px;
    }
    .filter-tab {
        padding: 10px 20px;
        border: none;
        background: #f8f9fa;
        color: #666;
        border-radius: 8px;
        margin-right: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .filter-tab.active {
        background: #667eea;
        color: white;
    }
    .filter-tab:hover {
        background: #5a6fd8;
        color: white;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    .empty-state i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }
    .empty-state h4 {
        color: #333;
        margin-bottom: 10px;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    .btn-lg {
        padding: 15px 30px;
        font-size: 1.1rem;
    }
    .review-form {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 25px;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 20px;
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
    .rating-input {
        display: flex;
        gap: 5px;
        margin-bottom: 15px;
    }
    .rating-input .star {
        font-size: 24px;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    .rating-input .star:hover,
    .rating-input .star.active {
        color: #ffc107;
    }
    .rating-input .star.empty {
        color: #e9ecef;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Reviews</h2>
                <a href="#" class="btn btn-primary btn-lg" onclick="showReviewForm(); return false;">
                    <i class="fa fa-star"></i> Write Review
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number" id="total-reviews">0</div>
                <div class="stats-label">Total Reviews</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number" id="avg-rating">0.0</div>
                <div class="stats-label">Average Rating</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number" id="given-reviews">0</div>
                <div class="stats-label">Reviews Given</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number" id="received-reviews">0</div>
                <div class="stats-label">Reviews Received</div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active" onclick="filterReviews('all')">All Reviews</button>
        <button class="filter-tab" onclick="filterReviews('given')">Reviews I Gave</button>
        <button class="filter-tab" onclick="filterReviews('received')">Reviews I Received</button>
        <?php if ($user_role == 'student'): ?>
        <button class="filter-tab" onclick="filterReviews('properties')">Property Reviews</button>
        <?php endif; ?>
    </div>

    <!-- Review Form (Hidden by default) -->
    <div class="review-form" id="review-form" style="display: none;">
        <h4>Write a Review</h4>
        <form id="new-review-form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Review Type</label>
                        <select class="form-select" id="review-type" name="review_type" required>
                            <option value="">Select Review Type</option>
                            <?php if ($user_role == 'student'): ?>
                            <option value="property">Review Property</option>
                            <option value="owner">Review Property Owner</option>
                            <?php elseif ($user_role == 'owner'): ?>
                            <option value="student">Review Student</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Select Target</label>
                        <select class="form-select" id="review-target" name="target_id" required>
                            <option value="">Select Target</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Rating</label>
                <div class="rating-input" id="rating-input">
                    <span class="star empty" data-rating="1">★</span>
                    <span class="star empty" data-rating="2">★</span>
                    <span class="star empty" data-rating="3">★</span>
                    <span class="star empty" data-rating="4">★</span>
                    <span class="star empty" data-rating="5">★</span>
                </div>
                <input type="hidden" id="rating" name="rating" value="0">
            </div>
            
            <div class="form-group">
                <label class="form-label">Review Comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Share your experience..." required></textarea>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" onclick="hideReviewForm()">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </div>
        </form>
    </div>

    <!-- Reviews Container -->
    <div id="reviews-container">
        <!-- Reviews will be loaded here -->
    </div>
</div>

<script>
let currentFilter = 'all';
let selectedRating = 0;

$(document).ready(function(){
    loadReviews();
    loadStats();
    
    // Rating input handling
    $('#rating-input .star').click(function(){
        const rating = parseInt($(this).data('rating'));
        selectedRating = rating;
        $('#rating').val(rating);
        
        $('#rating-input .star').each(function(index){
            if (index < rating) {
                $(this).removeClass('empty').addClass('active');
            } else {
                $(this).removeClass('active').addClass('empty');
            }
        });
    });
    
    // Review type change
    $('#review-type').change(function(){
        loadReviewTargets();
    });
    
    // Form submission
    $('#new-review-form').submit(function(e){
        e.preventDefault();
        submitReview();
    });
});

function loadStats() {
    $.ajax({
        url: 'api/ajax.php?action=get_review_stats',
        method: 'POST',
        data: {user_id: <?php echo $user_id ?>},
        success: function(response) {
            try {
                const stats = JSON.parse(response);
                $('#total-reviews').text(stats.total_reviews || 0);
                $('#avg-rating').text((stats.avg_rating || 0).toFixed(1));
                $('#given-reviews').text(stats.given_reviews || 0);
                $('#received-reviews').text(stats.received_reviews || 0);
            } catch(e) {
                console.log('Error loading stats:', e);
            }
        }
    });
}

function loadReviews() {
    $.ajax({
        url: 'api/ajax.php?action=get_reviews',
        method: 'POST',
        data: {
            user_id: <?php echo $user_id ?>,
            filter: currentFilter
        },
        success: function(response) {
            try {
                const reviews = JSON.parse(response);
                displayReviews(reviews);
            } catch(e) {
                console.log('Error loading reviews:', e);
                $('#reviews-container').html('<div class="empty-state"><i class="fa fa-star"></i><h4>No reviews found</h4><p>No reviews match your current filter.</p></div>');
            }
        }
    });
}

function displayReviews(reviews) {
    if (reviews.length === 0) {
        let emptyStateHtml = '<div class="empty-state"><i class="fa fa-star"></i>';
        
        if (currentFilter === 'all') {
            emptyStateHtml += '<h4>No reviews yet</h4>';
            emptyStateHtml += '<p>You haven\'t written or received any reviews yet. Share your experience by writing a review!</p>';
            emptyStateHtml += '<div class="mt-3">';
            emptyStateHtml += '<button class="btn btn-primary btn-lg" onclick="showReviewForm()">';
            emptyStateHtml += '<i class="fa fa-star"></i> Write Your First Review';
            emptyStateHtml += '</button>';
            emptyStateHtml += '</div>';
        } else {
            emptyStateHtml += '<h4>No reviews found</h4>';
            emptyStateHtml += '<p>No reviews match your current filter. Try selecting a different filter or write a new review.</p>';
        }
        
        emptyStateHtml += '</div>';
        $('#reviews-container').html(emptyStateHtml);
        return;
    }
    
    let html = '';
    reviews.forEach(review => {
        const stars = generateStars(review.rating);
        const date = new Date(review.created_at).toLocaleDateString();
        const canEdit = review.by_user_id == <?php echo $user_id ?>;
        
        html += `
            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar">
                            ${review.reviewer_name.charAt(0).toUpperCase()}
                        </div>
                        <div class="reviewer-details">
                            <h6>${review.reviewer_name}</h6>
                            <small>${review.listing_title ? 'Property' : 'User'} review</small>
                        </div>
                    </div>
                    <div class="rating-stars">
                        ${stars}
                    </div>
                </div>
                <div class="review-content">
                    <div class="review-text">${review.comment}</div>
                    <div class="review-meta">
                        <span class="review-date">${date}</span>
                        ${canEdit ? `
                            <div class="review-actions">
                                <button class="btn-action btn-edit" onclick="editReview(${review.id})">Edit</button>
                                <button class="btn-action btn-delete" onclick="deleteReview(${review.id})">Delete</button>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#reviews-container').html(html);
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<span class="star">★</span>';
        } else {
            stars += '<span class="star empty">★</span>';
        }
    }
    return stars;
}

function filterReviews(filter) {
    currentFilter = filter;
    $('.filter-tab').removeClass('active');
    $(`.filter-tab[onclick="filterReviews('${filter}')"]`).addClass('active');
    loadReviews();
}

function showReviewForm() {
    $('#review-form').show();
    $('html, body').animate({
        scrollTop: $('#review-form').offset().top - 100
    }, 500);
}

function hideReviewForm() {
    $('#review-form').hide();
    $('#new-review-form')[0].reset();
    selectedRating = 0;
    $('#rating').val(0);
    $('#rating-input .star').removeClass('active').addClass('empty');
}

function loadReviewTargets() {
    const reviewType = $('#review-type').val();
    const targetSelect = $('#review-target');
    
    targetSelect.html('<option value="">Loading...</option>');
    
    $.ajax({
        url: 'api/ajax.php?action=get_review_targets',
        method: 'POST',
        data: {
            review_type: reviewType,
            user_id: <?php echo $user_id ?>
        },
        success: function(response) {
            try {
                const targets = JSON.parse(response);
                targetSelect.html('<option value="">Select Target</option>');
                
                targets.forEach(target => {
                    targetSelect.append(`<option value="${target.id}">${target.name}</option>`);
                });
            } catch(e) {
                console.log('Error loading targets:', e);
                targetSelect.html('<option value="">Error loading targets</option>');
            }
        }
    });
}

function submitReview() {
    const formData = new FormData($('#new-review-form')[0]);
    
    if (selectedRating === 0) {
        alert('Please select a rating');
        return;
    }
    
    $.ajax({
        url: 'api/ajax.php?action=submit_review',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Review submitted successfully!');
                    hideReviewForm();
                    loadReviews();
                    loadStats();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch(e) {
                alert('Review submitted successfully!');
                hideReviewForm();
                loadReviews();
                loadStats();
            }
        },
        error: function() {
            alert('Failed to submit review. Please try again.');
        }
    });
}

function editReview(reviewId) {
    // Implementation for editing reviews
    alert('Edit functionality coming soon!');
}

function deleteReview(reviewId) {
    if (confirm('Are you sure you want to delete this review?')) {
        $.ajax({
            url: 'api/ajax.php?action=delete_review',
            method: 'POST',
            data: {review_id: reviewId},
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        alert('Review deleted successfully!');
                        loadReviews();
                        loadStats();
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch(e) {
                    alert('Review deleted successfully!');
                    loadReviews();
                    loadStats();
                }
            },
            error: function() {
                alert('Failed to delete review. Please try again.');
            }
        });
    }
}
</script>

