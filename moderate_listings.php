<?php include 'db_connect.php' ?>
<style>
    .listing-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 30px;
    }
    .listing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .listing-image {
        height: 200px;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        position: relative;
    }
    .listing-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .status-under-review { background: #fff3cd; color: #856404; }
    .status-active { background: #d4edda; color: #155724; }
    .status-rejected { background: #f8d7da; color: #721c24; }
    .status-inactive { background: #e2e3e5; color: #383d41; }
    
    .listing-content {
        padding: 20px;
    }
    .listing-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 10px;
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
        margin-bottom: 15px;
    }
    .listing-owner {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    .btn-action {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-approve {
        background: #28a745;
        color: white;
    }
    .btn-approve:hover {
        background: #218838;
        color: white;
    }
    .btn-reject {
        background: #dc3545;
        color: white;
    }
    .btn-reject:hover {
        background: #c82333;
        color: white;
    }
    .btn-view {
        background: #007bff;
        color: white;
    }
    .btn-view:hover {
        background: #0056b3;
        color: white;
    }
    .btn-deactivate {
        background: #ffc107;
        color: #212529;
    }
    .btn-deactivate:hover {
        background: #e0a800;
        color: #212529;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: #ddd;
    }
    .filter-tabs {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 30px;
    }
    .filter-tab {
        padding: 10px 20px;
        border: none;
        background: #f8f9fa;
        color: #666;
        border-radius: 8px;
        margin-right: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .filter-tab.active {
        background: #667eea;
        color: white;
    }
    .filter-tab:hover {
        background: #667eea;
        color: white;
    }
    .stats-cards {
        margin-bottom: 30px;
    }
    .stat-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }
    .stat-pending { color: #ffc107; }
    .stat-approved { color: #28a745; }
    .stat-rejected { color: #dc3545; }
    .stat-total { color: #007bff; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Moderate Listings</h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row stats-cards">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-total" id="total-listings">
                    <?php 
                    $total = $conn->query("SELECT COUNT(*) as count FROM listings")->fetch_assoc()['count'];
                    echo $total;
                    ?>
                </div>
                <div class="stat-label">Total Listings</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-pending" id="pending-listings">
                    <?php 
                    $pending = $conn->query("SELECT COUNT(*) as count FROM listings WHERE availability_status = 'under_review'")->fetch_assoc()['count'];
                    echo $pending;
                    ?>
                </div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-approved" id="approved-listings">
                    <?php 
                    $approved = $conn->query("SELECT COUNT(*) as count FROM listings WHERE availability_status = 'active'")->fetch_assoc()['count'];
                    echo $approved;
                    ?>
                </div>
                <div class="stat-label">Approved</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-rejected" id="rejected-listings">
                    <?php 
                    $rejected = $conn->query("SELECT COUNT(*) as count FROM listings WHERE availability_status = 'rejected'")->fetch_assoc()['count'];
                    echo $rejected;
                    ?>
                </div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active" data-status="all">All Listings</button>
        <button class="filter-tab" data-status="under_review">Pending Review</button>
        <button class="filter-tab" data-status="active">Approved</button>
        <button class="filter-tab" data-status="rejected">Rejected</button>
        <button class="filter-tab" data-status="inactive">Inactive</button>
    </div>

    <div class="row" id="listings-container">
        <?php
        $listings = $conn->query("SELECT l.*, u.email as owner_email, op.full_name as owner_name,
                                        (SELECT url FROM media WHERE listing_id = l.id ORDER BY position LIMIT 1) as main_image
                                 FROM listings l 
                                 INNER JOIN users u ON l.owner_id = u.id 
                                 LEFT JOIN owner_profiles op ON u.id = op.user_id
                                 ORDER BY l.created_at DESC");

        if ($listings->num_rows > 0):
            while ($listing = $listings->fetch_assoc()):
                $facilities = json_decode($listing['facilities'], true) ?? [];
                $status_class = 'status-' . str_replace('_', '-', $listing['availability_status']);
        ?>
        <div class="col-lg-4 col-md-6 listing-item" data-status="<?php echo $listing['availability_status'] ?>">
            <div class="listing-card">
                <div class="listing-image">
                    <?php if ($listing['main_image']): ?>
                        <img src="<?php echo $listing['main_image'] ?>" alt="<?php echo htmlspecialchars($listing['title']) ?>">
                    <?php else: ?>
                        <i class="fa fa-home fa-3x"></i>
                    <?php endif; ?>
                    <div class="status-badge <?php echo $status_class ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $listing['availability_status'])) ?>
                    </div>
                </div>
                <div class="listing-content">
                    <div class="listing-title"><?php echo htmlspecialchars($listing['title']) ?></div>
                    <div class="listing-price">LKR <?php echo number_format($listing['price_lkr'], 2) ?>/month</div>
                    <div class="listing-location">
                        <i class="fa fa-map-marker-alt"></i> <?php echo htmlspecialchars($listing['address']) ?>
                    </div>
                    
                    <div class="listing-owner">
                        <strong>Owner:</strong> <?php echo htmlspecialchars($listing['owner_name'] ?: $listing['owner_email']) ?><br>
                        <strong>Email:</strong> <?php echo htmlspecialchars($listing['owner_email']) ?><br>
                        <strong>Room Type:</strong> <?php echo ucfirst($listing['room_type']) ?><br>
                        <strong>Gender Pref:</strong> <?php echo ucfirst($listing['gender_pref']) ?>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Facilities:</strong><br>
                        <?php foreach ($facilities as $facility): ?>
                            <span class="badge badge-secondary mr-1"><?php echo ucfirst($facility) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-muted mb-3">
                        <small>
                            <i class="fa fa-calendar"></i> Created: <?php echo date('M d, Y', strtotime($listing['created_at'])) ?><br>
                            <i class="fa fa-star"></i> Rating: <?php echo number_format($listing['avg_rating'], 1) ?>/5 (<?php echo $listing['total_reviews'] ?> reviews)
                        </small>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn-action btn-view" onclick="viewListing(<?php echo $listing['id'] ?>)">
                            <i class="fa fa-eye"></i> View
                        </button>
                        
                        <?php if($listing['availability_status'] == 'under_review'): ?>
                            <button class="btn-action btn-approve" onclick="moderateListing(<?php echo $listing['id'] ?>, 'active')">
                                <i class="fa fa-check"></i> Approve
                            </button>
                            <button class="btn-action btn-reject" onclick="moderateListing(<?php echo $listing['id'] ?>, 'rejected')">
                                <i class="fa fa-times"></i> Reject
                            </button>
                        <?php elseif($listing['availability_status'] == 'active'): ?>
                            <button class="btn-action btn-deactivate" onclick="moderateListing(<?php echo $listing['id'] ?>, 'inactive')">
                                <i class="fa fa-pause"></i> Deactivate
                            </button>
                        <?php elseif($listing['availability_status'] == 'inactive'): ?>
                            <button class="btn-action btn-approve" onclick="moderateListing(<?php echo $listing['id'] ?>, 'active')">
                                <i class="fa fa-play"></i> Activate
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            endwhile;
        else:
        ?>
        <div class="col-12">
            <div class="empty-state">
                <i class="fa fa-home"></i>
                <h4>No listings found</h4>
                <p>There are no property listings to moderate at the moment.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Listing Details Modal -->
<div class="modal fade" id="listingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listingTitle"></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="listingDetails">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="approveBtn" style="display: none;">Approve</button>
                <button type="button" class="btn btn-danger" id="rejectBtn" style="display: none;">Reject</button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Listing</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rejection-form">
                    <input type="hidden" id="reject-listing-id">
                    <div class="form-group">
                        <label for="rejection-reason">Reason for Rejection</label>
                        <textarea class="form-control" id="rejection-reason" rows="4" placeholder="Please provide a reason for rejecting this listing..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmRejection()">Reject Listing</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Filter tabs
    $('.filter-tab').click(function(){
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        
        const status = $(this).data('status');
        filterListings(status);
    });
});

function filterListings(status) {
    if (status === 'all') {
        $('.listing-item').show();
    } else {
        $('.listing-item').hide();
        $(`.listing-item[data-status="${status}"]`).show();
    }
}

function viewListing(listingId) {
    $.ajax({
        url: 'ajax.php?action=get_listing_details',
        method: 'POST',
        data: {id: listingId},
        success: function(response) {
            try {
                var listing = JSON.parse(response);
                $('#listingTitle').text(listing.title);
                $('#listingDetails').html(generateListingDetails(listing));
                
                // Show action buttons based on status
                if (listing.availability_status === 'under_review') {
                    $('#approveBtn').show().attr('onclick', `moderateListing(${listingId}, 'active')`);
                    $('#rejectBtn').show().attr('onclick', `showRejectionModal(${listingId})`);
                } else {
                    $('#approveBtn').hide();
                    $('#rejectBtn').hide();
                }
                
                $('#listingModal').modal('show');
            } catch(e) {
                alert('Failed to load listing details');
            }
        },
        error: function() {
            alert('Failed to load listing details');
        }
    });
}

function generateListingDetails(listing) {
    var facilities = JSON.parse(listing.facilities || '[]');
    var facilitiesHtml = facilities.map(f => '<span class="badge badge-secondary mr-1">' + f + '</span>').join('');
    
    return `
        <div class="row">
            <div class="col-md-6">
                <div class="listing-image mb-3">
                    <img src="assets/uploads/${listing.main_image || 'default.jpg'}" alt="${listing.title}" class="img-fluid">
                </div>
            </div>
            <div class="col-md-6">
                <h4>LKR ${parseFloat(listing.price_lkr).toLocaleString()}/month</h4>
                <p><i class="fa fa-map-marker-alt"></i> ${listing.address}</p>
                <p><strong>Room Type:</strong> ${listing.room_type}</p>
                <p><strong>Gender Preference:</strong> ${listing.gender_pref}</p>
                <p><strong>Status:</strong> <span class="badge badge-${getStatusClass(listing.availability_status)}">${listing.availability_status.replace('_', ' ')}</span></p>
                <p><strong>Rating:</strong> ${listing.avg_rating}/5 (${listing.total_reviews} reviews)</p>
                <div class="facilities mb-3">
                    <strong>Facilities:</strong><br>
                    ${facilitiesHtml}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h5>Description</h5>
                <p>${listing.description}</p>
            </div>
        </div>
    `;
}

function getStatusClass(status) {
    switch(status) {
        case 'active': return 'success';
        case 'under_review': return 'warning';
        case 'inactive': return 'secondary';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}

function moderateListing(listingId, status) {
    const action = status === 'active' ? 'approve' : (status === 'rejected' ? 'reject' : 'deactivate');
    const message = status === 'active' ? 'approve' : (status === 'rejected' ? 'reject' : 'deactivate');
    
    if (confirm(`Are you sure you want to ${message} this listing?`)) {
        $.ajax({
            url: 'ajax.php?action=moderate_listing',
            method: 'POST',
            data: {id: listingId, status: status},
            success: function(response) {
                if (response == 1) {
                    alert(`Listing ${message}d successfully`);
                    location.reload();
                } else {
                    alert(`Failed to ${message} listing`);
                }
            },
            error: function() {
                alert(`Failed to ${message} listing`);
            }
        });
    }
}

function showRejectionModal(listingId) {
    $('#reject-listing-id').val(listingId);
    $('#rejection-reason').val('');
    $('#rejectionModal').modal('show');
}

function confirmRejection() {
    const listingId = $('#reject-listing-id').val();
    const reason = $('#rejection-reason').val();
    
    if (!reason.trim()) {
        alert('Please provide a reason for rejection');
        return;
    }
    
    $.ajax({
        url: 'ajax.php?action=moderate_listing',
        method: 'POST',
        data: {id: listingId, status: 'rejected', reason: reason},
        success: function(response) {
            if (response == 1) {
                alert('Listing rejected successfully');
                $('#rejectionModal').modal('hide');
                location.reload();
            } else {
                alert('Failed to reject listing');
            }
        },
        error: function() {
            alert('Failed to reject listing');
        }
    });
}
</script>
