<?php include 'config/db_connect.php' ?>
<style>
    .audit-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 20px;
        border-left: 5px solid #007bff;
    }
    .audit-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .audit-card.login { border-left-color: #28a745; }
    .audit-card.logout { border-left-color: #6c757d; }
    .audit-card.register { border-left-color: #17a2b8; }
    .audit-card.create_listing { border-left-color: #ffc107; }
    .audit-card.moderate_listing { border-left-color: #dc3545; }
    .audit-card.update_user_status { border-left-color: #fd7e14; }
    .audit-card.delete_user { border-left-color: #e83e8c; }
    .audit-card.update_complaint_status { border-left-color: #20c997; }
    
    .audit-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
    }
    .audit-id {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }
    .audit-action {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }
    .audit-timestamp {
        font-size: 0.9rem;
        color: #666;
    }
    
    .audit-content {
        padding: 20px;
    }
    .audit-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    .detail-item {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        border-left: 3px solid #007bff;
    }
    .detail-label {
        font-size: 0.8rem;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 3px;
    }
    .detail-value {
        font-size: 0.9rem;
        color: #333;
        font-weight: 500;
    }
    
    .audit-meta {
        background: #e3f2fd;
        border: 1px solid #bbdefb;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }
    .audit-meta h6 {
        color: #1976d2;
        margin-bottom: 10px;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    .audit-meta pre {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 10px;
        font-size: 0.8rem;
        margin: 0;
        white-space: pre-wrap;
        word-wrap: break-word;
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
    .filter-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 30px;
    }
    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .filter-tab {
        padding: 8px 16px;
        border: none;
        background: #f8f9fa;
        color: #666;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    .filter-tab.active {
        background: #007bff;
        color: white;
    }
    .filter-tab:hover {
        background: #007bff;
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
    .stat-total { color: #007bff; }
    .stat-login { color: #28a745; }
    .stat-register { color: #17a2b8; }
    .stat-moderate { color: #dc3545; }
    
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
        border-color: #007bff;
    }
    
    .pagination {
        justify-content: center;
        margin-top: 30px;
    }
    .page-link {
        border-radius: 8px;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        color: #007bff;
    }
    .page-link:hover {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Audit Logs</h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row stats-cards">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-total" id="total-logs">
                    <?php 
                    $total = $conn->query("SELECT COUNT(*) as count FROM audit_logs")->fetch_assoc()['count'];
                    echo $total;
                    ?>
                </div>
                <div class="stat-label">Total Logs</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-login" id="login-logs">
                    <?php 
                    $logins = $conn->query("SELECT COUNT(*) as count FROM audit_logs WHERE action = 'login'")->fetch_assoc()['count'];
                    echo $logins;
                    ?>
                </div>
                <div class="stat-label">Login Events</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-register" id="register-logs">
                    <?php 
                    $registers = $conn->query("SELECT COUNT(*) as count FROM audit_logs WHERE action = 'register'")->fetch_assoc()['count'];
                    echo $registers;
                    ?>
                </div>
                <div class="stat-label">Registrations</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-moderate" id="moderate-logs">
                    <?php 
                    $moderates = $conn->query("SELECT COUNT(*) as count FROM audit_logs WHERE action LIKE '%moderate%' OR action LIKE '%update_user_status%'")->fetch_assoc()['count'];
                    echo $moderates;
                    ?>
                </div>
                <div class="stat-label">Admin Actions</div>
            </div>
        </div>
    </div>

    <!-- Search Box -->
    <div class="search-box">
        <input type="text" class="search-input" id="log-search" placeholder="Search logs by user, action, or entity...">
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-tabs">
            <button class="filter-tab active" data-action="all">All Actions</button>
            <button class="filter-tab" data-action="login">Login/Logout</button>
            <button class="filter-tab" data-action="register">Registration</button>
            <button class="filter-tab" data-action="listing">Listings</button>
            <button class="filter-tab" data-action="user">User Management</button>
            <button class="filter-tab" data-action="complaint">Complaints</button>
            <button class="filter-tab" data-action="booking">Bookings</button>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="date-from">From Date</label>
                    <input type="date" class="form-control" id="date-from">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="date-to">To Date</label>
                    <input type="date" class="form-control" id="date-to">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="user-filter">User Role</label>
                    <select class="form-control" id="user-filter">
                        <option value="">All Users</option>
                        <option value="admin">Admins</option>
                        <option value="owner">Owners</option>
                        <option value="student">Students</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="logs-container">
        <?php
        $logs = $conn->query("SELECT al.*, u.email as actor_email, u.role as actor_role
                             FROM audit_logs al 
                             LEFT JOIN users u ON al.actor_user_id = u.id 
                             ORDER BY al.created_at DESC 
                             LIMIT 50");

        if ($logs->num_rows > 0):
            while ($log = $logs->fetch_assoc()):
                $action_class = str_replace('_', '-', $log['action']);
                $meta_data = json_decode($log['meta'], true);
        ?>
        <div class="col-12 log-item" data-action="<?php echo $log['action'] ?>" data-role="<?php echo $log['actor_role'] ?>" data-email="<?php echo strtolower($log['actor_email']) ?>" data-entity="<?php echo strtolower($log['entity']) ?>">
            <div class="audit-card <?php echo $action_class ?>">
                <div class="audit-header">
                    <div class="audit-id">Log #<?php echo $log['id'] ?></div>
                    <div class="audit-action">
                        <i class="fa fa-<?php echo getActionIcon($log['action']) ?>"></i>
                        <?php echo ucfirst(str_replace('_', ' ', $log['action'])) ?>
                    </div>
                    <div class="audit-timestamp">
                        <i class="fa fa-clock"></i> <?php echo date('M d, Y H:i:s', strtotime($log['created_at'])) ?>
                    </div>
                </div>
                
                <div class="audit-content">
                    <div class="audit-details">
                        <div class="detail-item">
                            <div class="detail-label">Actor</div>
                            <div class="detail-value">
                                <?php echo htmlspecialchars($log['actor_email'] ?: 'System') ?>
                                <?php if ($log['actor_role']): ?>
                                    <span class="badge badge-<?php echo getRoleBadgeClass($log['actor_role']) ?> ml-2">
                                        <?php echo ucfirst($log['actor_role']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Entity</div>
                            <div class="detail-value"><?php echo ucfirst($log['entity']) ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Entity ID</div>
                            <div class="detail-value"><?php echo $log['entity_id'] ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">IP Address</div>
                            <div class="detail-value"><?php echo htmlspecialchars($log['ip_address'] ?: 'Unknown') ?></div>
                        </div>
                    </div>
                    
                    <?php if ($meta_data && !empty($meta_data)): ?>
                    <div class="audit-meta">
                        <h6><i class="fa fa-info-circle"></i> Additional Information</h6>
                        <pre><?php echo htmlspecialchars(json_encode($meta_data, JSON_PRETTY_PRINT)) ?></pre>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php 
            endwhile;
        else:
        ?>
        <div class="col-12">
            <div class="empty-state">
                <i class="fa fa-history"></i>
                <h4>No audit logs found</h4>
                <p>There are no audit logs to display at the moment.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Audit logs pagination">
        <ul class="pagination" id="pagination">
            <!-- Pagination will be generated by JavaScript -->
        </ul>
    </nav>
</div>

<script>
$(document).ready(function(){
    // Set default date range (last 30 days)
    const today = new Date();
    const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
    
    $('#date-from').val(thirtyDaysAgo.toISOString().split('T')[0]);
    $('#date-to').val(today.toISOString().split('T')[0]);
    
    // Filter tabs
    $('.filter-tab').click(function(){
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        
        const action = $(this).data('action');
        filterLogs();
    });
    
    // Search functionality
    $('#log-search').on('keyup', function(){
        filterLogs();
    });
    
    // Date and role filters
    $('#date-from, #date-to, #user-filter').change(function(){
        filterLogs();
    });
    
    function filterLogs() {
        const searchTerm = $('#log-search').val().toLowerCase();
        const selectedAction = $('.filter-tab.active').data('action');
        const selectedRole = $('#user-filter').val();
        const dateFrom = $('#date-from').val();
        const dateTo = $('#date-to').val();
        
        $('.log-item').each(function(){
            const $item = $(this);
            const action = $item.data('action');
            const role = $item.data('role');
            const email = $item.data('email');
            const entity = $item.data('entity');
            
            let show = true;
            
            // Search filter
            if (searchTerm && !email.includes(searchTerm) && !action.includes(searchTerm) && !entity.includes(searchTerm)) {
                show = false;
            }
            
            // Action filter
            if (selectedAction !== 'all') {
                if (selectedAction === 'login' && !['login', 'logout'].includes(action)) {
                    show = false;
                } else if (selectedAction === 'register' && action !== 'register') {
                    show = false;
                } else if (selectedAction === 'listing' && !action.includes('listing')) {
                    show = false;
                } else if (selectedAction === 'user' && !action.includes('user')) {
                    show = false;
                } else if (selectedAction === 'complaint' && !action.includes('complaint')) {
                    show = false;
                } else if (selectedAction === 'booking' && !action.includes('booking')) {
                    show = false;
                }
            }
            
            // Role filter
            if (selectedRole && role !== selectedRole) {
                show = false;
            }
            
            // Date filter (would need to be implemented with actual date data)
            // For now, we'll skip date filtering as it requires more complex logic
            
            if (show) {
                $item.show();
            } else {
                $item.hide();
            }
        });
    }
});

function getActionIcon(action) {
    const icons = {
        'login': 'sign-in-alt',
        'logout': 'sign-out-alt',
        'register': 'user-plus',
        'create_listing': 'home',
        'moderate_listing': 'check-circle',
        'update_user_status': 'user-cog',
        'delete_user': 'user-times',
        'update_complaint_status': 'exclamation-triangle',
        'create_booking': 'bookmark',
        'update_booking_status': 'bookmark'
    };
    return icons[action] || 'info-circle';
}

function getRoleBadgeClass(role) {
    const classes = {
        'admin': 'success',
        'owner': 'warning',
        'student': 'info'
    };
    return classes[role] || 'secondary';
}
</script>

<?php
function getActionIcon($action) {
    $icons = [
        'login' => 'sign-in-alt',
        'logout' => 'sign-out-alt',
        'register' => 'user-plus',
        'create_listing' => 'home',
        'moderate_listing' => 'check-circle',
        'update_user_status' => 'user-cog',
        'delete_user' => 'user-times',
        'update_complaint_status' => 'exclamation-triangle',
        'create_booking' => 'bookmark',
        'update_booking_status' => 'bookmark'
    ];
    return $icons[$action] ?? 'info-circle';
}

function getRoleBadgeClass($role) {
    $classes = [
        'admin' => 'success',
        'owner' => 'warning',
        'student' => 'info'
    ];
    return $classes[$role] ?? 'secondary';
}
?>

