<?php include 'config/db_connect.php' ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <large><b><?php echo $_SESSION['login_role'] == 'student' ? 'My Bookings' : 'Property Bookings'; ?></b></large>
                <!-- Debug info -->
                <small class="text-muted float-right">
                    User ID: <?php echo $_SESSION['login_id']; ?> | Role: <?php echo $_SESSION['login_role']; ?>
                </small>
            </div>
            <div class="card-body">
                
                <?php
                // Optional: Show booking count for current user
                $user_bookings_count = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE student_id = '{$_SESSION['login_id']}'");
                $user_count = $user_bookings_count->fetch_assoc()['count'];
                
                if ($user_count > 0) {
                    echo "<div class='alert alert-success'>";
                    echo "<i class='fa fa-info-circle'></i> You have $user_count booking(s) in the system.";
                    echo "</div>";
                }
                ?>

                <div class="table-responsive">
                    <table class="table table-bordered" id="bookings-table">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Booking ID</th>
                                <th class="text-center">Property</th>
                                <th class="text-center">Landlord</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End Date</th>
                                <th class="text-center">Total Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            
                            // Simple query for student bookings
                            if($_SESSION['login_role'] == 'student') {
                                $query = "SELECT b.*, l.title as listing_title, u.email as landlord_email
                                         FROM bookings b 
                                         LEFT JOIN listings l ON b.listing_id = l.id 
                                         LEFT JOIN users u ON l.owner_id = u.id 
                                         WHERE b.student_id = '{$_SESSION['login_id']}' 
                                         ORDER BY b.id DESC";
                            } else {
                                // Owner bookings
                                $query = "SELECT b.*, l.title as listing_title, u.email as student_email
                                         FROM bookings b 
                                         LEFT JOIN listings l ON b.listing_id = l.id 
                                         LEFT JOIN users u ON b.student_id = u.id 
                                         WHERE l.owner_id = '{$_SESSION['login_id']}' 
                                         ORDER BY b.id DESC";
                            }
                            
                            echo "<!-- Query: " . htmlspecialchars($query) . " -->";
                            
                            $bookings = $conn->query($query);
                            
                            if ($bookings && $bookings->num_rows > 0) {
                                while($row = $bookings->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><strong><?php echo $row['id']; ?></strong></td>
                                        <td class="text-center"><?php echo $row['listing_title'] ?: 'Unknown Property'; ?></td>
                                        <td class="text-center">
                                            <?php 
                                            if($_SESSION['login_role'] == 'student') {
                                                echo $row['landlord_email'] ?: 'Unknown';
                                            } else {
                                                echo $row['student_email'] ?: 'Unknown';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo date('M d, Y', strtotime($row['start_date'])); ?></td>
                                        <td class="text-center"><?php echo $row['end_date'] ? date('M d, Y', strtotime($row['end_date'])) : 'N/A'; ?></td>
                                        <td class="text-center"><strong>LKR <?php echo number_format($row['total_amount'], 2); ?></strong></td>
                                        <td class="text-center">
                                            <?php 
                                            $payment_status = $row['payment_status'] ?? 'not_required';
                                            
                                            if($row['status'] == 'confirmed' && $payment_status == 'completed'): ?>
                                                <span class="badge badge-success">Paid</span>
                                            <?php elseif($row['status'] == 'pending'): ?>
                                                <span class="badge badge-warning">Pending</span>
                                            <?php elseif($row['status'] == 'approved'): ?>
                                                <span class="badge badge-info">Approved</span>
                                            <?php elseif($row['status'] == 'confirmed'): ?>
                                                <span class="badge badge-success">Confirmed</span>
                                            <?php elseif($row['status'] == 'rejected'): ?>
                                                <span class="badge badge-danger">Rejected</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary"><?php echo ucfirst($row['status']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary view_booking" type="button" data-id="<?php echo $row['id']; ?>">
                                                <i class="fa fa-eye"></i> View
                                            </button>
                                            
                                            <?php 
                                            $payment_status = $row['payment_status'] ?? 'not_required';
                                            // Show Pay Now button only for approved bookings that haven't been paid yet
                                            if($_SESSION['login_role'] == 'student' && $row['status'] == 'approved' && $payment_status != 'completed'): ?>
                                            <button class="btn btn-sm btn-success pay_now" type="button" 
                                                    data-booking-id="<?php echo $row['id']; ?>"
                                                    data-amount="<?php echo $row['total_amount']; ?>"
                                                    data-property="<?php echo htmlspecialchars($row['listing_title']); ?>"
                                                    title="Pay LKR <?php echo number_format($row['total_amount'], 2); ?>">
                                                <i class="fa fa-credit-card"></i> Pay Now
                                            </button>
                                            <?php endif; ?>
                                            
                                            <?php if($_SESSION['login_role'] == 'owner' && $row['status'] == 'pending'): ?>
                                            <button class="btn btn-sm btn-success approve_booking" type="button" data-id="<?php echo $row['id']; ?>">
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                            <button class="btn btn-sm btn-danger reject_booking" type="button" data-id="<?php echo $row['id']; ?>">
                                                <i class="fa fa-times"></i> Reject
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fa fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <h4 class="text-muted">No Bookings Found</h4>
                                            <?php if($_SESSION['login_role'] == 'student'): ?>
                                                <p class="text-muted">You haven't made any bookings yet.</p>
                                                <p><strong>Current User ID:</strong> <?php echo $_SESSION['login_id']; ?></p>
                                                <p><strong>Database has bookings for User ID:</strong> 20</p>
                                                <a href="fix_booking_user.php" class="btn btn-warning">
                                                    <i class="fa fa-wrench"></i> Fix User Mismatch
                                                </a>
                                                <a href="index.php?page=search" class="btn btn-primary">
                                                    <i class="fa fa-search"></i> Search Properties
                                                </a>
                                            <?php else: ?>
                                                <p class="text-muted">No one has booked your properties yet.</p>
                                                <a href="index.php?page=listings" class="btn btn-primary">
                                                    <i class="fa fa-home"></i> Manage Your Properties
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.empty-state {
    padding: 40px 20px;
}
.empty-state i {
    display: block;
    margin-bottom: 15px;
}
.empty-state h4 {
    margin-bottom: 10px;
    font-weight: 500;
}
.empty-state p {
    margin-bottom: 10px;
    font-size: 14px;
}
.alert {
    margin-bottom: 20px;
}
</style>

<script>
$(document).ready(function() {
    $('#bookings-table').dataTable({
        "order": [[ 1, "desc" ]] // Sort by booking ID descending
    });
    
    $('.view_booking').click(function(){
        uni_modal("Booking Details", "pages/view_booking.php?id=" + $(this).attr('data-id'), "mid-large");
    });
    
    $('.pay_now').click(function(){
        var bookingId = $(this).data('booking-id');
        var amount = $(this).data('amount');
        var property = $(this).data('property');
        
        if(confirm('Pay LKR ' + parseFloat(amount).toLocaleString() + ' for "' + property + '"?\n\nThis will redirect you to the payment page.')) {
            window.location.href = 'index.php?page=make_payment_simple&booking_id=' + bookingId;
        }
    });
    
    $('.approve_booking').click(function(){
        var booking_id = $(this).attr('data-id');
        updateBookingStatus(booking_id, 'approved');
    });
    
    $('.reject_booking').click(function(){
        var booking_id = $(this).attr('data-id');
        updateBookingStatus(booking_id, 'rejected');
    });
    
    function updateBookingStatus(booking_id, status) {
        start_load();
        $.ajax({
            url: 'api/ajax.php?action=update_booking_status',
            method: 'POST',
            data: {id: booking_id, status: status},
            success: function(resp){
                if(resp == 1){
                    alert_toast("Booking " + status + " successfully.", "success");
                    location.reload();
                } else {
                    alert_toast("Error updating booking status.", "error");
                }
                end_load();
            }
        });
    }
});
</script>