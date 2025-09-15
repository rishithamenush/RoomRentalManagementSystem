<?php include 'config/db_connect.php' ?>
<style>
   .dashboard-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .stat-icon {
        font-size: 3rem;
        opacity: 0.8;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }
    .stat-label {
        font-size: 1rem;
        color: #666;
        margin: 0;
    }
    .welcome-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
    }
    .quick-action-btn {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        text-decoration: none;
        color: #333;
        transition: all 0.3s ease;
        display: block;
    }
    .quick-action-btn:hover {
        border-color: #667eea;
        color: #667eea;
        text-decoration: none;
        transform: translateY(-2px);
    }
    .quick-action-btn i {
        font-size: 2rem;
        margin-bottom: 10px;
        display: block;
    }
    .recent-activity {
        max-height: 400px;
        overflow-y: auto;
    }
    .activity-item {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
    }
    .activity-item:last-child {
        border-bottom: none;
    }
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.2rem;
    }
    .activity-content {
        flex: 1;
    }
    .activity-title {
        font-weight: 600;
        margin: 0 0 5px 0;
    }
    .activity-time {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">Welcome back, <?php echo $_SESSION['login_name'] ?? 'User' ?>!</h2>
                        <p class="mb-0">Here's what's happening with your <?php echo ucfirst($_SESSION['login_role'] ?? 'account') ?> account today.</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="text-white-50">
                            <i class="fa fa-calendar-alt fa-2x"></i>
                            <div><?php echo date('l, F j, Y') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($_SESSION['login_role'] == 'student'): ?>
    <!-- Student Dashboard -->
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-search stat-icon text-primary"></i>
                    <h3 class="stat-number text-primary">
                        <?php 
                        $searches = $conn->query("SELECT COUNT(*) as count FROM audit_logs WHERE actor_user_id = {$_SESSION['login_id']} AND action = 'search_listings'")->fetch_assoc()['count'];
                        echo $searches;
                        ?>
                    </h3>
                    <p class="stat-label">Searches Made</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-bookmark stat-icon text-warning"></i>
                    <h3 class="stat-number text-warning">
                        <?php 
                        $bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE student_id = {$_SESSION['login_id']}")->fetch_assoc()['count'];
                        echo $bookings;
                        ?>
                    </h3>
                    <p class="stat-label">Total Bookings</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-check-circle stat-icon text-success"></i>
                    <h3 class="stat-number text-success">
                        <?php 
                        $active_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE student_id = {$_SESSION['login_id']} AND status = 'approved'")->fetch_assoc()['count'];
                        echo $active_bookings;
                        ?>
                    </h3>
                    <p class="stat-label">Active Bookings</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-star stat-icon text-info"></i>
                    <h3 class="stat-number text-info">
                        <?php 
                        $reviews = $conn->query("SELECT COUNT(*) as count FROM reviews WHERE by_user_id = {$_SESSION['login_id']}")->fetch_assoc()['count'];
                        echo $reviews;
                        ?>
                    </h3>
                    <p class="stat-label">Reviews Written</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="index.php?page=search" class="quick-action-btn">
                                <i class="fa fa-search text-primary"></i>
                                <div>Search Rooms</div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="index.php?page=bookings" class="quick-action-btn">
                                <i class="fa fa-bookmark text-warning"></i>
                                <div>My Bookings</div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="index.php?page=messages" class="quick-action-btn">
                                <i class="fa fa-comments text-info"></i>
                                <div>Messages</div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="index.php?page=profile" class="quick-action-btn">
                                <i class="fa fa-user text-success"></i>
                                <div>Profile</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Bookings</h5>
                </div>
                <div class="card-body">
                    <div class="recent-activity">
                        <?php 
                        $recent_bookings = $conn->query("SELECT b.*, l.title, l.price_lkr FROM bookings b 
                                                       INNER JOIN listings l ON b.listing_id = l.id 
                                                       WHERE b.student_id = {$_SESSION['login_id']} 
                                                       ORDER BY b.created_at DESC LIMIT 5");
                        if($recent_bookings->num_rows > 0):
                            while($booking = $recent_bookings->fetch_assoc()):
                        ?>
                        <div class="activity-item">
                            <div class="activity-icon bg-primary text-white">
                                <i class="fa fa-home"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title"><?php echo $booking['title'] ?></div>
                                <div class="activity-time">
                                    <?php echo date('M d, Y', strtotime($booking['created_at'])) ?> • 
                                    Status: <span class="badge badge-<?php echo $booking['status'] == 'approved' ? 'success' : ($booking['status'] == 'pending' ? 'warning' : 'danger') ?>">
                                        <?php echo ucfirst($booking['status']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-weight-bold">LKR <?php echo number_format($booking['price_lkr'], 2) ?></div>
                            </div>
                        </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <div class="text-center py-4">
                            <i class="fa fa-bookmark fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No bookings yet. Start searching for your perfect room!</p>
                            <a href="index.php?page=search" class="btn btn-primary">Search Rooms</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php elseif($_SESSION['login_role'] == 'owner'): ?>
    <!-- Owner Dashboard -->
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-home stat-icon text-primary"></i>
                    <h3 class="stat-number text-primary">
                        <?php 
                        $listings = $conn->query("SELECT COUNT(*) as count FROM listings WHERE owner_id = {$_SESSION['login_id']}")->fetch_assoc()['count'];
                        echo $listings;
                        ?>
                    </h3>
                    <p class="stat-label">Total Listings</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-bookmark stat-icon text-warning"></i>
                    <h3 class="stat-number text-warning">
                        <?php 
                        $bookings = $conn->query("SELECT COUNT(*) as count FROM bookings b 
                                                INNER JOIN listings l ON b.listing_id = l.id 
                                                WHERE l.owner_id = {$_SESSION['login_id']}")->fetch_assoc()['count'];
                        echo $bookings;
                        ?>
                    </h3>
                    <p class="stat-label">Total Bookings</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-money-bill-wave stat-icon text-success"></i>
                    <h3 class="stat-number text-success">
                        <?php 
                        $revenue = $conn->query("SELECT SUM(p.amount_lkr) as total FROM payments p 
                                               INNER JOIN bookings b ON p.booking_id = b.id 
                                               INNER JOIN listings l ON b.listing_id = l.id 
                                               WHERE l.owner_id = {$_SESSION['login_id']} AND p.status = 'paid'")->fetch_assoc()['total'];
                        echo 'LKR ' . number_format($revenue ?? 0, 2);
                        ?>
                    </h3>
                    <p class="stat-label">Total Revenue</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-star stat-icon text-info"></i>
                    <h3 class="stat-number text-info">
                        <?php 
                        $avg_rating = $conn->query("SELECT AVG(r.rating) as avg_rating FROM reviews r 
                                                  INNER JOIN listings l ON r.listing_id = l.id 
                                                  WHERE l.owner_id = {$_SESSION['login_id']}")->fetch_assoc()['avg_rating'];
                        echo number_format($avg_rating ?? 0, 1);
                        ?>
                    </h3>
                    <p class="stat-label">Average Rating</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="index.php?page=create_listing" class="quick-action-btn">
                                <i class="fa fa-plus text-primary"></i>
                                <div>Add Listing</div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="index.php?page=listings" class="quick-action-btn">
                                <i class="fa fa-home text-warning"></i>
                                <div>My Listings</div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="index.php?page=bookings" class="quick-action-btn">
                                <i class="fa fa-bookmark text-info"></i>
                                <div>Bookings</div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="index.php?page=messages" class="quick-action-btn">
                                <i class="fa fa-comments text-success"></i>
                                <div>Messages</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Bookings</h5>
                </div>
                <div class="card-body">
                    <div class="recent-activity">
                        <?php 
                        $recent_bookings = $conn->query("SELECT b.*, l.title, u.email as student_email FROM bookings b 
                                                       INNER JOIN listings l ON b.listing_id = l.id 
                                                       INNER JOIN users u ON b.student_id = u.id 
                                                       WHERE l.owner_id = {$_SESSION['login_id']} 
                                                       ORDER BY b.created_at DESC LIMIT 5");
                        if($recent_bookings->num_rows > 0):
                            while($booking = $recent_bookings->fetch_assoc()):
                        ?>
                        <div class="activity-item">
                            <div class="activity-icon bg-warning text-white">
                                <i class="fa fa-bookmark"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title"><?php echo $booking['title'] ?></div>
                                <div class="activity-time">
                                    <?php echo date('M d, Y', strtotime($booking['created_at'])) ?> • 
                                    Student: <?php echo $booking['student_email'] ?> • 
                                    Status: <span class="badge badge-<?php echo $booking['status'] == 'approved' ? 'success' : ($booking['status'] == 'pending' ? 'warning' : 'danger') ?>">
                                        <?php echo ucfirst($booking['status']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-weight-bold">LKR <?php echo number_format($booking['total_amount'], 2) ?></div>
                            </div>
                        </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <div class="text-center py-4">
                            <i class="fa fa-bookmark fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No bookings yet. Create your first listing to get started!</p>
                            <a href="index.php?page=create_listing" class="btn btn-primary">Create Listing</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php elseif($_SESSION['login_role'] == 'admin'): ?>
    <!-- Admin Dashboard -->
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-users stat-icon text-primary"></i>
                    <h3 class="stat-number text-primary">
                        <?php 
                        $users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
                        echo $users;
                        ?>
                    </h3>
                    <p class="stat-label">Total Users</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-home stat-icon text-warning"></i>
                    <h3 class="stat-number text-warning">
                        <?php 
                        $listings = $conn->query("SELECT COUNT(*) as count FROM listings")->fetch_assoc()['count'];
                        echo $listings;
                        ?>
                    </h3>
                    <p class="stat-label">Total Listings</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-exclamation-triangle stat-icon text-danger"></i>
                    <h3 class="stat-number text-danger">
                        <?php 
                        $pending_complaints = $conn->query("SELECT COUNT(*) as count FROM complaints WHERE status = 'under_review'")->fetch_assoc()['count'];
                        echo $pending_complaints;
                        ?>
                    </h3>
                    <p class="stat-label">Pending Complaints</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <i class="fa fa-clock stat-icon text-info"></i>
                    <h3 class="stat-number text-info">
                        <?php 
                        $pending_listings = $conn->query("SELECT COUNT(*) as count FROM listings WHERE availability_status = 'under_review'")->fetch_assoc()['count'];
                        echo $pending_listings;
                        ?>
                    </h3>
                    <p class="stat-label">Pending Listings</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Admin Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="index.php?page=moderate_listings" class="quick-action-btn">
                                <i class="fa fa-check-circle text-primary"></i>
                                <div>Moderate Listings</div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="index.php?page=manage_users" class="quick-action-btn">
                                <i class="fa fa-users text-warning"></i>
                                <div>Manage Users</div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="index.php?page=complaints" class="quick-action-btn">
                                <i class="fa fa-exclamation-triangle text-danger"></i>
                                <div>Complaints</div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="index.php?page=reports" class="quick-action-btn"> 
                                <i class="fa fa-chart-bar text-info"></i>
                                <div>Reports</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-8 mb-4">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="recent-activity">
                        <?php 
                        $recent_activity = $conn->query("SELECT al.*, u.email as actor_email FROM audit_logs al 
                                                       LEFT JOIN users u ON al.actor_user_id = u.id 
                                                       ORDER BY al.created_at DESC LIMIT 10");
                        if($recent_activity->num_rows > 0):
                            while($activity = $recent_activity->fetch_assoc()):
                        ?>
                        <div class="activity-item">
                            <div class="activity-icon bg-info text-white">
                                <i class="fa fa-<?php echo $activity['action'] == 'login' ? 'sign-in-alt' : ($activity['action'] == 'register' ? 'user-plus' : 'cog') ?>"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">
                                    <?php echo ucfirst($activity['action']) ?> - <?php echo $activity['entity'] ?>
                                </div>
                                <div class="activity-time">
                                    <?php echo date('M d, Y H:i', strtotime($activity['created_at'])) ?> • 
                                    User: <?php echo $activity['actor_email'] ?? 'System' ?>
                                </div>
                            </div>
                        </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <div class="text-center py-4">
                            <i class="fa fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent activity</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    // Auto-refresh dashboard every 5 minutes
    setInterval(function(){
        location.reload();
    }, 300000);
</script>

