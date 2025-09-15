<?php include 'db_connect.php' ?>
<style>
    .booking-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 30px;
    }
    .booking-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .booking-header {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
    }
    .booking-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }
    .booking-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #28a745;
        margin-bottom: 10px;
    }
    .booking-location {
        color: #666;
        margin-bottom: 15px;
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-approved { background: #d4edda; color: #155724; }
    .status-rejected { background: #f8d7da; color: #721c24; }
    .status-cancelled { background: #e2e3e5; color: #383d41; }
    .status-completed { background: #cce5ff; color: #004085; }
    
    .booking-content {
        padding: 20px;
    }
    .booking-details {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 0.9rem;
        color: #666;
    }
    .booking-actions {
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
        text-decoration: none;
        display: inline-block;
    }
    .btn-view {
        background: #007bff;
        color: white;
    }
    .btn-view:hover {
        background: #0056b3;
        color: white;
    }
    .btn-cancel {
        background: #dc3545;
        color: white;
    }
    .btn-cancel:hover {
        background: #c82333;
        color: white;
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
        background: #ffc107;
        color: #212529;
    }
    .btn-reject:hover {
        background: #e0a800;
        color: #212529;
    }
    .btn-message {
        background: #17a2b8;
        color: white;
    }
    .btn-message:hover {
        background: #138496;
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
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <?php if($_SESSION['login_role'] == 'student'): ?>
                    My Bookings
                <?php else: ?>
                    Booking Requests
                <?php endif; ?>
            </h2>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active" data-status="all">All</button>
        <button class="filter-tab" data-status="pending">Pending</button>
        <button class="filter-tab" data-status="approved">Approved</button>
        <button class="filter-tab" data-status="rejected">Rejected</button>
        <button class="filter-tab" data-status="cancelled">Cancelled</button>
        <?php if($_SESSION['login_role'] == 'owner'): ?>
        <button class="filter-tab" data-status="completed">Completed</button>
        <?php endif; ?>
    </div>

    <div class="row" id="bookings-container">
        <?php
        $user_id = $_SESSION['login_id'];
        $user_role = $_SESSION['login_role'];
        
        if ($user_role == 'student') {
            $bookings_sql = "SELECT b.*, l.title, l.address, l.price_lkr, l.owner_id, u.email as owner_email,
                                   (SELECT url FROM media WHERE listing_id = l.id ORDER BY position LIMIT 1) as main_image
                            FROM bookings b 
                            INNER JOIN listings l ON b.listing_id = l.id 
                            INNER JOIN users u ON l.owner_id = u.id 
                            WHERE b.student_id = ? 
                            ORDER BY b.created_at DESC";
        } else {
            $bookings_sql = "SELECT b.*, l.title, l.address, l.price_lkr, b.student_id, u.email as student_email,
                                   (SELECT url FROM media WHERE listing_id = l.id ORDER BY position LIMIT 1) as main_image
                            FROM bookings b 
                            INNER JOIN listings l ON b.listing_id = l.id 
                            INNER JOIN users u ON b.student_id = u.id 
                            WHERE l.owner_id = ? 
                            ORDER BY b.created_at DESC";
        }
        
        $bookings_stmt = $conn->prepare($bookings_sql);
        $bookings_stmt->bind_param("i", $user_id);
        $bookings_stmt->execute();
        $bookings = $bookings_stmt->get_result();

        if ($bookings->num_rows > 0):
            while ($booking = $bookings->fetch_assoc()):
                $status_class = 'status-' . $booking['status'];
        ?>
        <div class="col-lg-6 col-md-12 booking-item" data-status="<?php echo $booking['status'] ?>">
            <div class="booking-card">
                <div class="booking-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="booking-title"><?php echo htmlspecialchars($booking['title']) ?></div>
                            <div class="booking-price">LKR <?php echo number_format($booking['total_amount'], 2) ?></div>
                            <div class="booking-location">
                                <i class="fa fa-map-marker-alt"></i> <?php echo htmlspecialchars($booking['address']) ?>
                            </div>
                        </div>
                        <div class="status-badge <?php echo $status_class ?>">
                            <?php echo ucfirst($booking['status']) ?>
                        </div>
                    </div>
                </div>
                
                <div class="booking-content">
                    <div class="booking-details">
                        <div>
                            <strong>Move-in:</strong> <?php echo date('M d, Y', strtotime($booking['start_date'])) ?>
                        </div>
                        <div>
                            <strong>Move-out:</strong> <?php echo $booking['end_date'] ? date('M d, Y', strtotime($booking['end_date'])) : 'Indefinite' ?>
                        </div>
                    </div>
                    
                    <div class="booking-details">
                        <div>
                            <strong>Requested:</strong> <?php echo date('M d, Y H:i', strtotime($booking['created_at'])) ?>
                        </div>
                        <div>
                            <?php if($user_role == 'student'): ?>
                                <strong>Owner:</strong> <?php echo $booking['owner_email'] ?>
                            <?php else: ?>
                                <strong>Student:</strong> <?php echo $booking['student_email'] ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if($booking['student_note']): ?>
                    <div class="mb-3">
                        <strong>Student Note:</strong>
                        <p class="text-muted mb-0"><?php echo htmlspecialchars($booking['student_note']) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($booking['owner_note']): ?>
                    <div class="mb-3">
                        <strong>Owner Note:</strong>
                        <p class="text-muted mb-0"><?php echo htmlspecialchars($booking['owner_note']) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="booking-actions">
                        <button class="btn-action btn-view" onclick="viewBooking(<?php echo $booking['id'] ?>)">
                            <i class="fa fa-eye"></i> View Details
                        </button>
                        
                        <?php if($user_role == 'student'): ?>
                            <?php if($booking['status'] == 'pending'): ?>
                                <button class="btn-action btn-cancel" onclick="cancelBooking(<?php echo $booking['id'] ?>)">
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if($booking['status'] == 'pending'): ?>
                                <button class="btn-action btn-approve" onclick="updateBookingStatus(<?php echo $booking['id'] ?>, 'approved')">
                                    <i class="fa fa-check"></i> Approve
                                </button>
                                <button class="btn-action btn-reject" onclick="updateBookingStatus(<?php echo $booking['id'] ?>, 'rejected')">
                                    <i class="fa fa-times"></i> Reject
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <button class="btn-action btn-message" onclick="openMessage(<?php echo $booking['listing_id'] ?>, <?php echo $user_role == 'student' ? $booking['owner_id'] : $booking['student_id'] ?>)">
                            <i class="fa fa-comment"></i> Message
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
                <i class="fa fa-bookmark"></i>
                <h4>No bookings found</h4>
                <?php if($user_role == 'student'): ?>
                    <p>You haven't made any booking requests yet.</p>
                    <a href="index.php?page=search" class="btn btn-primary">Search for Rooms</a>
                <?php else: ?>
                    <p>No booking requests for your properties yet.</p>
                    <a href="index.php?page=listings" class="btn btn-primary">Manage Listings</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bookingDetails">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        filterBookings(status);
    });
});

function filterBookings(status) {
    if (status === 'all') {
        $('.booking-item').show();
    } else {
        $('.booking-item').hide();
        $(`.booking-item[data-status="${status}"]`).show();
    }
}

function viewBooking(bookingId) {
    $.ajax({
        url: 'ajax.php?action=get_booking_details',
        method: 'POST',
        data: {id: bookingId},
        success: function(response) {
            try {
                var booking = JSON.parse(response);
                $('#bookingDetails').html(generateBookingDetails(booking));
                $('#bookingModal').modal('show');
            } catch(e) {
                alert('Failed to load booking details');
            }
        },
        error: function() {
            alert('Failed to load booking details');
        }
    });
}

function generateBookingDetails(booking) {
    return `
        <div class="row">
            <div class="col-md-6">
                <h5>Property Information</h5>
                <p><strong>Title:</strong> ${booking.title}</p>
                <p><strong>Address:</strong> ${booking.address}</p>
                <p><strong>Monthly Rent:</strong> LKR ${parseFloat(booking.price_lkr).toLocaleString()}</p>
            </div>
            <div class="col-md-6">
                <h5>Booking Information</h5>
                <p><strong>Status:</strong> <span class="badge badge-${getStatusClass(booking.status)}">${booking.status}</span></p>
                <p><strong>Move-in Date:</strong> ${new Date(booking.start_date).toLocaleDateString()}</p>
                <p><strong>Move-out Date:</strong> ${booking.end_date ? new Date(booking.end_date).toLocaleDateString() : 'Indefinite'}</p>
                <p><strong>Total Amount:</strong> LKR ${parseFloat(booking.total_amount).toLocaleString()}</p>
                <p><strong>Requested:</strong> ${new Date(booking.created_at).toLocaleString()}</p>
            </div>
        </div>
        ${booking.student_note ? `<div class="mt-3"><h5>Student Note</h5><p>${booking.student_note}</p></div>` : ''}
        ${booking.owner_note ? `<div class="mt-3"><h5>Owner Note</h5><p>${booking.owner_note}</p></div>` : ''}
    `;
}

function getStatusClass(status) {
    switch(status) {
        case 'pending': return 'warning';
        case 'approved': return 'success';
        case 'rejected': return 'danger';
        case 'cancelled': return 'secondary';
        case 'completed': return 'info';
        default: return 'secondary';
    }
}

function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking request?')) {
        updateBookingStatus(bookingId, 'cancelled');
    }
}

function updateBookingStatus(bookingId, status) {
    const action = status === 'approved' ? 'approve' : (status === 'rejected' ? 'reject' : 'cancel');
    const message = status === 'approved' ? 'approve' : (status === 'rejected' ? 'reject' : 'cancel');
    
    if (confirm(`Are you sure you want to ${message} this booking?`)) {
        $.ajax({
            url: 'ajax.php?action=update_booking_status',
            method: 'POST',
            data: {id: bookingId, status: status},
            success: function(response) {
                if (response == 1) {
                    alert(`Booking ${message}d successfully`);
                    location.reload();
                } else {
                    alert(`Failed to ${message} booking`);
                }
            },
            error: function() {
                alert(`Failed to ${message} booking`);
            }
        });
    }
}

function openMessage(listingId, userId) {
    // Create or find message thread
    $.ajax({
        url: 'ajax.php?action=create_message_thread',
        method: 'POST',
        data: {
            listing_id: listingId,
            other_user_id: userId
        },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    // Redirect to messages page
                    window.location.href = 'index.php?page=messages';
                } else {
                    alert('Failed to start conversation: ' + result.message);
                }
            } catch(e) {
                alert('Failed to start conversation');
            }
        },
        error: function() {
            alert('Failed to start conversation');
        }
    });
}
</script>
