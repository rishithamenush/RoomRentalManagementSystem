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
    .status-active { background: #d4edda; color: #155724; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-inactive { background: #f8d7da; color: #721c24; }
    .status-rejected { background: #f8d7da; color: #721c24; }
    
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
    .listing-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 0.9rem;
        color: #666;
    }
    .action-buttons {
        display: flex;
        gap: 10px;
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
    .btn-edit {
        background: #007bff;
        color: white;
    }
    .btn-edit:hover {
        background: #0056b3;
        color: white;
    }
    .btn-delete {
        background: #dc3545;
        color: white;
    }
    .btn-delete:hover {
        background: #c82333;
        color: white;
    }
    .btn-view {
        background: #28a745;
        color: white;
    }
    .btn-view:hover {
        background: #218838;
        color: white;
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
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>My Listings</h2>
                <a href="index.php?page=create_listing" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add New Listing
                </a>
            </div>
        </div>
    </div>

    <div class="row" id="listings-container">
        <?php
        $owner_id = $_SESSION['login_id'];
        $listings = $conn->query("SELECT l.*, 
                                        (SELECT url FROM media WHERE listing_id = l.id ORDER BY position LIMIT 1) as main_image,
                                        (SELECT COUNT(*) FROM bookings WHERE listing_id = l.id) as total_bookings,
                                        (SELECT COUNT(*) FROM bookings WHERE listing_id = l.id AND status = 'approved') as active_bookings
                                 FROM listings l 
                                 WHERE l.owner_id = $owner_id 
                                 ORDER BY l.created_at DESC");

        if ($listings->num_rows > 0):
            while ($listing = $listings->fetch_assoc()):
                $facilities = json_decode($listing['facilities'], true) ?? [];
                $status_class = 'status-' . str_replace('_', '-', $listing['availability_status']);
        ?>
        <div class="col-lg-4 col-md-6">
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
                    
                    <div class="listing-stats">
                        <span><i class="fa fa-bookmark"></i> <?php echo $listing['total_bookings'] ?> bookings</span>
                        <span><i class="fa fa-star"></i> <?php echo number_format($listing['avg_rating'], 1) ?>/5</span>
                        <span><i class="fa fa-eye"></i> <?php echo $listing['total_reviews'] ?> reviews</span>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn-action btn-view" onclick="viewListing(<?php echo $listing['id'] ?>)">
                            <i class="fa fa-eye"></i> View
                        </button>
                        <button class="btn-action btn-edit" onclick="editListing(<?php echo $listing['id'] ?>)">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteListing(<?php echo $listing['id'] ?>)">
                            <i class="fa fa-trash"></i> Delete
                        </button>
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
                <h4>No listings yet</h4>
                <p>Start by creating your first property listing to attract students.</p>
                <a href="index.php?page=create_listing" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Create Your First Listing
                </a>
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
                <button type="button" class="btn btn-primary" id="editListingBtn">Edit Listing</button>
            </div>
        </div>
    </div>
</div>

<script>
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
                $('#editListingBtn').attr('onclick', 'editListing(' + listingId + ')');
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
                    <img src="${listing.main_image || 'assets/uploads/default.jpg'}" alt="${listing.title}" class="img-fluid">
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

function editListing(listingId) {
    window.location.href = 'index.php?page=edit_listing&id=' + listingId;
}

function deleteListing(listingId) {
    if (confirm('Are you sure you want to delete this listing? This action cannot be undone.')) {
        $.ajax({
            url: 'ajax.php?action=delete_listing',
            method: 'POST',
            data: {id: listingId},
            success: function(response) {
                if (response == 1) {
                    alert('Listing deleted successfully');
                    location.reload();
                } else {
                    alert('Failed to delete listing');
                }
            },
            error: function() {
                alert('Failed to delete listing');
            }
        });
    }
}
</script>
