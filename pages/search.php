<?php include 'config/db_connect.php' ?>
<style>
    .search-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 30px;
        margin-bottom: 30px;
    }
    .filter-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
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
    }
    .listing-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
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
    .listing-facilities {
        margin-bottom: 15px;
    }
    .facility-tag {
        display: inline-block;
        background: #e9ecef;
        color: #495057;
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 0.8rem;
        margin-right: 5px;
        margin-bottom: 5px;
    }
    .listing-rating {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    .stars {
        color: #ffc107;
        margin-right: 10px;
    }
    .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: transform 0.2s ease;
    }
    .btn-view:hover {
        transform: translateY(-2px);
        color: white;
    }
    .search-filters {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 25px;
        margin-bottom: 30px;
    }
    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 10px 15px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
    }
    .no-results {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    .no-results i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: #ddd;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Search Student Accommodation</h2>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="search-filters">
        <form id="search-form">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" placeholder="e.g., Malabe, Colombo">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="price_min" class="form-label">Min Price (LKR)</label>
                    <input type="number" class="form-control" id="price_min" name="price_min" placeholder="0">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="price_max" class="form-label">Max Price (LKR)</label>
                    <input type="number" class="form-control" id="price_max" name="price_max" placeholder="50000">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="room_type" class="form-label">Room Type</label>
                    <select class="form-select" id="room_type" name="room_type">
                        <option value="">Any Type</option>
                        <option value="single">Single Room</option>
                        <option value="shared">Shared Room</option>
                        <option value="studio">Studio</option>
                        <option value="apartment">Apartment</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="gender_pref" class="form-label">Gender Preference</label>
                    <select class="form-select" id="gender_pref" name="gender_pref">
                        <option value="">Any</option>
                        <option value="male">Male Only</option>
                        <option value="female">Female Only</option>
                        <option value="any">Mixed</option>
                    </select>
                </div>
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-search">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
            
            <!-- Advanced Filters -->
            <div class="row">
                <div class="col-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="wifi" name="facilities[]" value="wifi">
                        <label class="form-check-label" for="wifi">Wi-Fi</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="ac" name="facilities[]" value="ac">
                        <label class="form-check-label" for="ac">Air Conditioning</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="parking" name="facilities[]" value="parking">
                        <label class="form-check-label" for="parking">Parking</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="laundry" name="facilities[]" value="laundry">
                        <label class="form-check-label" for="laundry">Laundry</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="kitchen" name="facilities[]" value="kitchen">
                        <label class="form-check-label" for="kitchen">Kitchen</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="security" name="facilities[]" value="security">
                        <label class="form-check-label" for="security">Security</label>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Search Results -->
    <div class="row" id="search-results">
        <?php
        // Get search parameters
        $location = $_GET['location'] ?? '';
        $price_min = $_GET['price_min'] ?? '';
        $price_max = $_GET['price_max'] ?? '';
        $room_type = $_GET['room_type'] ?? '';
        $gender_pref = $_GET['gender_pref'] ?? '';
        $facilities = $_GET['facilities'] ?? [];

        // Build query
        $where_conditions = ["l.availability_status = 'active'"];
        $params = [];

        if (!empty($location)) {
            $where_conditions[] = "l.address LIKE ?";
            $params[] = "%$location%";
        }

        if (!empty($price_min)) {
            $where_conditions[] = "l.price_lkr >= ?";
            $params[] = $price_min;
        }

        if (!empty($price_max)) {
            $where_conditions[] = "l.price_lkr <= ?";
            $params[] = $price_max;
        }

        if (!empty($room_type)) {
            $where_conditions[] = "l.room_type = ?";
            $params[] = $room_type;
        }

        if (!empty($gender_pref)) {
            $where_conditions[] = "l.gender_pref = ?";
            $params[] = $gender_pref;
        }

        $where_clause = implode(' AND ', $where_conditions);
        $order_by = "l.avg_rating DESC, l.created_at DESC";

        // Execute query
        $query = "SELECT l.*, u.email as owner_email, 
                         (SELECT url FROM media WHERE listing_id = l.id ORDER BY position LIMIT 1) as main_image
                  FROM listings l 
                  INNER JOIN users u ON l.owner_id = u.id 
                  WHERE $where_clause 
                  ORDER BY $order_by 
                  LIMIT 20";

        $stmt = $conn->prepare($query);
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):
            while ($listing = $result->fetch_assoc()):
                $facilities_array = json_decode($listing['facilities'], true) ?? [];
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="listing-card">
                <div class="listing-image">
                    <?php if ($listing['main_image']): ?>
                        <img src="<?php echo $listing['main_image'] ?>" alt="<?php echo htmlspecialchars($listing['title']) ?>">
                    <?php else: ?>
                        <i class="fa fa-home fa-3x"></i>
                    <?php endif; ?>
                </div>
                <div class="listing-content">
                    <div class="listing-title"><?php echo htmlspecialchars($listing['title']) ?></div>
                    <div class="listing-price">LKR <?php echo number_format($listing['price_lkr'], 2) ?>/month</div>
                    <div class="listing-location">
                        <i class="fa fa-map-marker-alt"></i> <?php echo htmlspecialchars($listing['address']) ?>
                    </div>
                    
                    <div class="listing-facilities">
                        <?php foreach ($facilities_array as $facility): ?>
                            <span class="facility-tag"><?php echo ucfirst($facility) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="listing-rating">
                        <div class="stars">
                            <?php
                            $rating = $listing['avg_rating'];
                            for ($i = 1; $i <= 5; $i++):
                                if ($i <= $rating):
                            ?>
                                <i class="fa fa-star"></i>
                            <?php elseif ($i - 0.5 <= $rating): ?>
                                <i class="fa fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="fa fa-star-o"></i>
                            <?php endif; endfor; ?>
                        </div>
                        <span class="text-muted">(<?php echo $listing['total_reviews'] ?> reviews)</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fa fa-user"></i> <?php echo ucfirst($listing['room_type']) ?> • 
                            <i class="fa fa-venus-mars"></i> <?php echo ucfirst($listing['gender_pref']) ?>
                        </small>
                        <button class="btn btn-view" onclick="viewListing(<?php echo $listing['id'] ?>)">
                            View Details
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
            <div class="no-results">
                <i class="fa fa-search"></i>
                <h4>No listings found</h4>
                <p>Try adjusting your search criteria or browse all available rooms.</p>
                <button class="btn btn-primary" onclick="clearFilters()">Clear Filters</button>
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
                <button type="button" class="btn btn-primary" id="bookNowBtn">Request Booking</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Search form submission
    $('#search-form').submit(function(e){
        e.preventDefault();
        performSearch();
    });

    // Auto-search on filter change
    $('input, select').on('change', function(){
        performSearch();
    });
});

function performSearch() {
    var formData = $('#search-form').serialize();
    var url = 'index.php?page=search&' + formData;
    window.location.href = url;
}

function viewListing(listingId) {
    // Load listing details via AJAX
    $.ajax({
        url: 'api/ajax.php?action=get_listing_details',
        method: 'POST',
        data: {id: listingId},
        success: function(response) {
            try {
                var listing = JSON.parse(response);
                $('#listingTitle').text(listing.title);
                $('#listingDetails').html(generateListingDetails(listing));
                $('#bookNowBtn').attr('onclick', 'requestBooking(' + listingId + ')');
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

function requestBooking(listingId) {
    // Check if user is logged in
    <?php if (!isset($_SESSION['login_id'])): ?>
        alert('Please login to request a booking');
        window.location.href = 'login.php';
        return;
    <?php endif; ?>
    
    // Redirect to booking page
    window.location.href = 'index.php?page=request_booking&listing_id=' + listingId;
}

function clearFilters() {
    $('#search-form')[0].reset();
    window.location.href = 'index.php?page=search';
}
</script>
