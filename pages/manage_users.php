<?php include 'config/db_connect.php' ?>
<style>
    .user-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 30px;
    }
    .user-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .user-avatar {
        height: 80px;
        width: 80px;
        border-radius: 50%;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 2rem;
        margin: 0 auto 15px;
        position: relative;
    }
    .user-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    .status-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 3px solid white;
    }
    .status-active { background: #28a745; }
    .status-pending { background: #ffc107; }
    .status-suspended { background: #dc3545; }
    .status-inactive { background: #6c757d; }
    
    .user-content {
        padding: 20px;
        text-align: center;
    }
    .user-name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }
    .user-email {
        color: #666;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }
    .user-role {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .role-student { background: #e3f2fd; color: #1976d2; }
    .role-owner { background: #f3e5f5; color: #7b1fa2; }
    .role-admin { background: #e8f5e8; color: #388e3c; }
    
    .user-stats {
        display: flex;
        justify-content: space-around;
        margin-bottom: 15px;
        padding: 10px 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }
    .stat-item {
        text-align: center;
    }
    .stat-number {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
    }
    .stat-label {
        font-size: 0.8rem;
        color: #666;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }
    .btn-approve {
        background: #28a745;
        color: white;
    }
    .btn-approve:hover {
        background: #218838;
        color: white;
    }
    .btn-suspend {
        background: #ffc107;
        color: #212529;
    }
    .btn-suspend:hover {
        background: #e0a800;
        color: #212529;
    }
    .btn-activate {
        background: #17a2b8;
        color: white;
    }
    .btn-activate:hover {
        background: #138496;
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
    .btn-delete {
        background: #dc3545;
        color: white;
    }
    .btn-delete:hover {
        background: #c82333;
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
    .stat-students { color: #1976d2; }
    .stat-owners { color: #7b1fa2; }
    .stat-admins { color: #388e3c; }
    .stat-total { color: #007bff; }
    .stat-pending { color: #ffc107; }
    .stat-suspended { color: #dc3545; }
    
    .search-box {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 30px;
    }
    .search-input {
        width: 100%;
        padding: 12px 20px;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }
    .search-input:focus {
        outline: none;
        border-color: #667eea;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Manage Users</h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row stats-cards">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-number stat-total" id="total-users">
                    <?php 
                    $total = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
                    echo $total;
                    ?>
                </div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-number stat-students" id="student-count">
                    <?php 
                    $students = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'")->fetch_assoc()['count'];
                    echo $students;
                    ?>
                </div>
                <div class="stat-label">Students</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-number stat-owners" id="owner-count">
                    <?php 
                    $owners = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'owner'")->fetch_assoc()['count'];
                    echo $owners;
                    ?>
                </div>
                <div class="stat-label">Owners</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-number stat-admins" id="admin-count">
                    <?php 
                    $admins = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'")->fetch_assoc()['count'];
                    echo $admins;
                    ?>
                </div>
                <div class="stat-label">Admins</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-number stat-pending" id="pending-count">
                    <?php 
                    $pending = $conn->query("SELECT COUNT(*) as count FROM users WHERE status = 'pending'")->fetch_assoc()['count'];
                    echo $pending;
                    ?>
                </div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-number stat-suspended" id="suspended-count">
                    <?php 
                    $suspended = $conn->query("SELECT COUNT(*) as count FROM users WHERE status = 'suspended'")->fetch_assoc()['count'];
                    echo $suspended;
                    ?>
                </div>
                <div class="stat-label">Suspended</div>
            </div>
        </div>
    </div>

    <!-- Search Box -->
    <div class="search-box">
        <input type="text" class="search-input" id="user-search" placeholder="Search users by name, email, or role...">
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active" data-role="all">All Users</button>
        <button class="filter-tab" data-role="student">Students</button>
        <button class="filter-tab" data-role="owner">Owners</button>
        <button class="filter-tab" data-role="admin">Admins</button>
        <button class="filter-tab" data-status="pending">Pending Approval</button>
        <button class="filter-tab" data-status="suspended">Suspended</button>
    </div>

    <div class="row" id="users-container">
        <?php
        $users = $conn->query("SELECT u.*, 
                                     sp.full_name as student_name, sp.university, sp.gender as student_gender,
                                     op.full_name as owner_name, op.verified as owner_verified,
                                     ap.full_name as admin_name,
                                     (SELECT COUNT(*) FROM listings WHERE owner_id = u.id) as listing_count,
                                     (SELECT COUNT(*) FROM bookings WHERE student_id = u.id) as booking_count
                              FROM users u 
                              LEFT JOIN student_profiles sp ON u.id = sp.user_id
                              LEFT JOIN owner_profiles op ON u.id = op.user_id
                              LEFT JOIN admin_profiles ap ON u.id = ap.user_id
                              ORDER BY u.created_at DESC");

        if ($users->num_rows > 0):
            while ($user = $users->fetch_assoc()):
                $full_name = $user['student_name'] ?: $user['owner_name'] ?: $user['admin_name'] ?: 'Unknown';
                $role_class = 'role-' . $user['role'];
                $status_class = 'status-' . $user['status'];
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6 user-item" data-role="<?php echo $user['role'] ?>" data-status="<?php echo $user['status'] ?>" data-name="<?php echo strtolower($full_name) ?>" data-email="<?php echo strtolower($user['email']) ?>">
            <div class="user-card">
                <div class="user-content">
                    <div class="user-avatar">
                        <i class="fa fa-user"></i>
                        <div class="status-badge <?php echo $status_class ?>"></div>
                    </div>
                    
                    <div class="user-name"><?php echo htmlspecialchars($full_name) ?></div>
                    <div class="user-email"><?php echo htmlspecialchars($user['email']) ?></div>
                    <div class="user-role <?php echo $role_class ?>">
                        <?php echo ucfirst($user['role']) ?>
                    </div>
                    
                    <div class="user-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $user['listing_count'] ?></div>
                            <div class="stat-label">Listings</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $user['booking_count'] ?></div>
                            <div class="stat-label">Bookings</div>
                        </div>
                    </div>
                    
                    <div class="text-muted mb-3">
                        <small>
                            <i class="fa fa-calendar"></i> Joined: <?php echo date('M d, Y', strtotime($user['created_at'])) ?><br>
                            <?php if($user['role'] == 'owner' && $user['owner_verified']): ?>
                                <i class="fa fa-check-circle text-success"></i> Verified Owner<br>
                            <?php endif; ?>
                            <?php if($user['role'] == 'student' && $user['university']): ?>
                                <i class="fa fa-graduation-cap"></i> <?php echo htmlspecialchars($user['university']) ?><br>
                            <?php endif; ?>
                        </small>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn-action btn-view" onclick="viewUser(<?php echo $user['id'] ?>)">
                            <i class="fa fa-eye"></i> View
                        </button>
                        
                        <?php if($user['status'] == 'pending'): ?>
                            <button class="btn-action btn-approve" onclick="updateUserStatus(<?php echo $user['id'] ?>, 'active')">
                                <i class="fa fa-check"></i> Approve
                            </button>
                        <?php elseif($user['status'] == 'active'): ?>
                            <button class="btn-action btn-suspend" onclick="updateUserStatus(<?php echo $user['id'] ?>, 'suspended')">
                                <i class="fa fa-pause"></i> Suspend
                            </button>
                        <?php elseif($user['status'] == 'suspended'): ?>
                            <button class="btn-action btn-activate" onclick="updateUserStatus(<?php echo $user['id'] ?>, 'active')">
                                <i class="fa fa-play"></i> Activate
                            </button>
                        <?php endif; ?>
                        
                        <?php if($user['role'] != 'admin'): ?>
                            <button class="btn-action btn-delete" onclick="deleteUser(<?php echo $user['id'] ?>)">
                                <i class="fa fa-trash"></i> Delete
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
                <i class="fa fa-users"></i>
                <h4>No users found</h4>
                <p>There are no users registered on the platform yet.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userTitle"></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="userDetails">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="approveUserBtn" style="display: none;">Approve</button>
                <button type="button" class="btn btn-warning" id="suspendUserBtn" style="display: none;">Suspend</button>
                <button type="button" class="btn btn-info" id="activateUserBtn" style="display: none;">Activate</button>
            </div>
        </div>
    </div>
</div>

<!-- Suspension Modal -->
<div class="modal fade" id="suspensionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Suspend User</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="suspension-form">
                    <input type="hidden" id="suspend-user-id">
                    <div class="form-group">
                        <label for="suspension-reason">Reason for Suspension</label>
                        <textarea class="form-control" id="suspension-reason" rows="4" placeholder="Please provide a reason for suspending this user..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="confirmSuspension()">Suspend User</button>
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
        
        const filter = $(this).data('role') || $(this).data('status');
        const filterType = $(this).data('role') ? 'role' : 'status';
        filterUsers(filter, filterType);
    });
    
    // Search functionality
    $('#user-search').on('keyup', function(){
        const searchTerm = $(this).val().toLowerCase();
        filterUsersBySearch(searchTerm);
    });
});

function filterUsers(filter, type) {
    if (filter === 'all') {
        $('.user-item').show();
    } else {
        $('.user-item').hide();
        $(`.user-item[data-${type}="${filter}"]`).show();
    }
}

function filterUsersBySearch(searchTerm) {
    $('.user-item').each(function(){
        const name = $(this).data('name');
        const email = $(this).data('email');
        const role = $(this).data('role');
        
        if (name.includes(searchTerm) || email.includes(searchTerm) || role.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function viewUser(userId) {
    $.ajax({
        url: 'api/ajax.php?action=get_user_details',
        method: 'POST',
        data: {id: userId},
        success: function(response) {
            try {
                var user = JSON.parse(response);
                $('#userTitle').text(user.full_name);
                $('#userDetails').html(generateUserDetails(user));
                
                // Show action buttons based on status
                if (user.status === 'pending') {
                    $('#approveUserBtn').show().attr('onclick', `updateUserStatus(${userId}, 'active')`);
                    $('#suspendUserBtn').hide();
                    $('#activateUserBtn').hide();
                } else if (user.status === 'active') {
                    $('#approveUserBtn').hide();
                    $('#suspendUserBtn').show().attr('onclick', `showSuspensionModal(${userId})`);
                    $('#activateUserBtn').hide();
                } else if (user.status === 'suspended') {
                    $('#approveUserBtn').hide();
                    $('#suspendUserBtn').hide();
                    $('#activateUserBtn').show().attr('onclick', `updateUserStatus(${userId}, 'active')`);
                }
                
                $('#userModal').modal('show');
            } catch(e) {
                alert('Failed to load user details');
            }
        },
        error: function() {
            alert('Failed to load user details');
        }
    });
}

function generateUserDetails(user) {
    var roleClass = 'role-' + user.role;
    var statusClass = 'status-' + user.status;
    
    return `
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="user-avatar mb-3">
                    <i class="fa fa-user fa-3x"></i>
                    <div class="status-badge ${statusClass}"></div>
                </div>
                <h4>${user.full_name}</h4>
                <p class="text-muted">${user.email}</p>
                <span class="user-role ${roleClass}">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</span>
            </div>
            <div class="col-md-8">
                <h5>Account Information</h5>
                <table class="table table-sm">
                    <tr><td><strong>Status:</strong></td><td><span class="badge badge-${getStatusClass(user.status)}">${user.status.charAt(0).toUpperCase() + user.status.slice(1)}</span></td></tr>
                    <tr><td><strong>Phone:</strong></td><td>${user.phone || 'Not provided'}</td></tr>
                    <tr><td><strong>Joined:</strong></td><td>${new Date(user.created_at).toLocaleDateString()}</td></tr>
                    <tr><td><strong>Last Login:</strong></td><td>${user.last_login ? new Date(user.last_login).toLocaleDateString() : 'Never'}</td></tr>
                </table>
                
                ${user.role === 'student' ? `
                    <h5>Student Profile</h5>
                    <table class="table table-sm">
                        <tr><td><strong>University:</strong></td><td>${user.university || 'Not provided'}</td></tr>
                        <tr><td><strong>Gender:</strong></td><td>${user.gender || 'Not provided'}</td></tr>
                        <tr><td><strong>Student ID:</strong></td><td>${user.student_id_doc ? 'Verified' : 'Not verified'}</td></tr>
                    </table>
                ` : ''}
                
                ${user.role === 'owner' ? `
                    <h5>Owner Profile</h5>
                    <table class="table table-sm">
                        <tr><td><strong>Verified:</strong></td><td>${user.verified ? '<span class="text-success">Yes</span>' : '<span class="text-warning">No</span>'}</td></tr>
                        <tr><td><strong>NIC Document:</strong></td><td>${user.nic_doc ? 'Uploaded' : 'Not uploaded'}</td></tr>
                    </table>
                ` : ''}
                
                <h5>Activity</h5>
                <table class="table table-sm">
                    <tr><td><strong>Listings:</strong></td><td>${user.listing_count}</td></tr>
                    <tr><td><strong>Bookings:</strong></td><td>${user.booking_count}</td></tr>
                </table>
            </div>
        </div>
    `;
}

function getStatusClass(status) {
    switch(status) {
        case 'active': return 'success';
        case 'pending': return 'warning';
        case 'suspended': return 'danger';
        case 'inactive': return 'secondary';
        default: return 'secondary';
    }
}

function updateUserStatus(userId, status) {
    const action = status === 'active' ? 'approve' : (status === 'suspended' ? 'suspend' : 'activate');
    const message = status === 'active' ? 'approve' : (status === 'suspended' ? 'suspend' : 'activate');
    
    if (confirm(`Are you sure you want to ${message} this user?`)) {
        $.ajax({
            url: 'api/ajax.php?action=update_user_status',
            method: 'POST',
            data: {id: userId, status: status},
            success: function(response) {
                if (response == 1) {
                    alert(`User ${message}d successfully`);
                    location.reload();
                } else {
                    alert(`Failed to ${message} user`);
                }
            },
            error: function() {
                alert(`Failed to ${message} user`);
            }
        });
    }
}

function showSuspensionModal(userId) {
    $('#suspend-user-id').val(userId);
    $('#suspension-reason').val('');
    $('#suspensionModal').modal('show');
}

function confirmSuspension() {
    const userId = $('#suspend-user-id').val();
    const reason = $('#suspension-reason').val();
    
    if (!reason.trim()) {
        alert('Please provide a reason for suspension');
        return;
    }
    
    $.ajax({
        url: 'api/ajax.php?action=update_user_status',
        method: 'POST',
        data: {id: userId, status: 'suspended', reason: reason},
        success: function(response) {
            if (response == 1) {
                alert('User suspended successfully');
                $('#suspensionModal').modal('hide');
                location.reload();
            } else {
                alert('Failed to suspend user');
            }
        },
        error: function() {
            alert('Failed to suspend user');
        }
    });
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        $.ajax({
            url: 'api/ajax.php?action=delete_user',
            method: 'POST',
            data: {id: userId},
            success: function(response) {
                if (response == 1) {
                    alert('User deleted successfully');
                    location.reload();
                } else {
                    alert('Failed to delete user');
                }
            },
            error: function() {
                alert('Failed to delete user');
            }
        });
    }
}
</script>

