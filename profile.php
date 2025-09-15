<?php include 'db_connect.php' ?>
<style>
    .profile-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        text-align: center;
        position: relative;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        border: 4px solid rgba(255,255,255,0.3);
        font-size: 3rem;
        color: rgba(255,255,255,0.8);
    }
    
    .profile-name {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .profile-role {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 20px;
    }
    
    .profile-stats {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: 20px;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        display: block;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }
    
    .profile-content {
        padding: 40px;
    }
    
    .profile-section {
        margin-bottom: 40px;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .info-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #007bff;
    }
    
    .info-label {
        font-size: 0.9rem;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #333;
        font-weight: 500;
    }
    
    .edit-button {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.2s ease;
        margin-top: 20px;
    }
    
    .edit-button:hover {
        background: #0056b3;
    }
    
    .verification-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: 10px;
    }
    
    .badge-verified {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .badge-unverified {
        background: #f8d7da;
        color: #721c24;
    }
    
    .activity-timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -22px;
        top: 20px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #007bff;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #e9ecef;
    }
    
    .timeline-date {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 5px;
    }
    
    .timeline-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .timeline-description {
        font-size: 0.9rem;
        color: #666;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #ddd;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }
    
    .btn-primary {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.2s ease;
    }
    
    .btn-primary:hover {
        background: #0056b3;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.2s ease;
        margin-left: 10px;
    }
    
    .btn-secondary:hover {
        background: #545b62;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="profile-container">
                <?php
                $user_id = $_SESSION['login_id'];
                $user_role = $_SESSION['login_role'];
                
                // Get user profile based on role
                if ($user_role == 'student') {
                    $profile_sql = "SELECT u.*, sp.*, 
                                           (SELECT COUNT(*) FROM bookings WHERE student_id = u.id) as booking_count,
                                           (SELECT COUNT(*) FROM reviews WHERE by_user_id = u.id) as review_count,
                                           (SELECT COUNT(*) FROM complaints WHERE by_student_id = u.id) as complaint_count
                                    FROM users u 
                                    LEFT JOIN student_profiles sp ON u.id = sp.user_id
                                    WHERE u.id = ?";
                } else if ($user_role == 'owner') {
                    $profile_sql = "SELECT u.*, op.*, 
                                           (SELECT COUNT(*) FROM listings WHERE owner_id = u.id) as listing_count,
                                           (SELECT COUNT(*) FROM bookings b INNER JOIN listings l ON b.listing_id = l.id WHERE l.owner_id = u.id) as booking_count,
                                           (SELECT COUNT(*) FROM reviews WHERE target_user_id = u.id) as review_count
                                    FROM users u 
                                    LEFT JOIN owner_profiles op ON u.id = op.user_id
                                    WHERE u.id = ?";
                } else if ($user_role == 'admin') {
                    $profile_sql = "SELECT u.*, ap.*, 
                                           (SELECT COUNT(*) FROM audit_logs WHERE actor_user_id = u.id) as action_count
                                    FROM users u 
                                    LEFT JOIN admin_profiles ap ON u.id = ap.user_id
                                    WHERE u.id = ?";
                }
                
                $profile_stmt = $conn->prepare($profile_sql);
                $profile_stmt->bind_param("i", $user_id);
                $profile_stmt->execute();
                $profile_result = $profile_stmt->get_result();
                $profile = $profile_result->fetch_assoc();
                ?>
                
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="profile-name"><?php echo htmlspecialchars($profile['full_name'] ?: 'Unknown User') ?></div>
                    <div class="profile-role">
                        <?php echo ucfirst($user_role) ?>
                        <?php if ($user_role == 'owner'): ?>
                            <?php if ($profile['verified']): ?>
                                <span class="verification-badge badge-verified">Verified</span>
                            <?php else: ?>
                                <span class="verification-badge badge-pending">Pending Verification</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="profile-stats">
                        <?php if ($user_role == 'student'): ?>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $profile['booking_count'] ?></span>
                                <span class="stat-label">Bookings</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $profile['review_count'] ?></span>
                                <span class="stat-label">Reviews</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $profile['complaint_count'] ?></span>
                                <span class="stat-label">Complaints</span>
                            </div>
                        <?php elseif ($user_role == 'owner'): ?>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $profile['listing_count'] ?></span>
                                <span class="stat-label">Listings</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $profile['booking_count'] ?></span>
                                <span class="stat-label">Bookings</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $profile['review_count'] ?></span>
                                <span class="stat-label">Reviews</span>
                            </div>
                        <?php elseif ($user_role == 'admin'): ?>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $profile['action_count'] ?></span>
                                <span class="stat-label">Actions</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Profile Content -->
                <div class="profile-content">
                    <!-- Personal Information -->
                    <div class="profile-section">
                        <h3 class="section-title">Personal Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Full Name</div>
                                <div class="info-value"><?php echo htmlspecialchars($profile['full_name'] ?: 'Not provided') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Email Address</div>
                                <div class="info-value"><?php echo htmlspecialchars($profile['email']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value"><?php echo htmlspecialchars($profile['phone'] ?: 'Not provided') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Account Status</div>
                                <div class="info-value">
                                    <?php echo ucfirst($profile['status']) ?>
                                    <?php if ($profile['email_verified']): ?>
                                        <span class="verification-badge badge-verified">Email Verified</span>
                                    <?php else: ?>
                                        <span class="verification-badge badge-unverified">Email Not Verified</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Member Since</div>
                                <div class="info-value"><?php echo date('F d, Y', strtotime($profile['created_at'])) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Last Login</div>
                                <div class="info-value"><?php echo $profile['last_login'] ? date('F d, Y H:i', strtotime($profile['last_login'])) : 'Never' ?></div>
                            </div>
                        </div>
                        <button class="edit-button" onclick="editProfile()">
                            <i class="fa fa-edit"></i> Edit Profile
                        </button>
                    </div>
                    
                    <?php if ($user_role == 'student'): ?>
                    <!-- Student Specific Information -->
                    <div class="profile-section">
                        <h3 class="section-title">Student Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Gender</div>
                                <div class="info-value"><?php echo htmlspecialchars($profile['gender'] ?: 'Not specified') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">University</div>
                                <div class="info-value"><?php echo htmlspecialchars($profile['university'] ?: 'Not provided') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Date of Birth</div>
                                <div class="info-value"><?php echo $profile['date_of_birth'] ? date('F d, Y', strtotime($profile['date_of_birth'])) : 'Not provided' ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Emergency Contact</div>
                                <div class="info-value"><?php echo htmlspecialchars($profile['emergency_contact'] ?: 'Not provided') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Student ID Verification</div>
                                <div class="info-value">
                                    <?php if ($profile['student_id_doc']): ?>
                                        <span class="verification-badge badge-verified">Verified</span>
                                    <?php else: ?>
                                        <span class="verification-badge badge-unverified">Not Uploaded</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif ($user_role == 'owner'): ?>
                    <!-- Owner Specific Information -->
                    <div class="profile-section">
                        <h3 class="section-title">Owner Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Business Name</div>
                                <div class="info-value"><?php echo htmlspecialchars($profile['business_name'] ?: 'Not provided') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Verification Status</div>
                                <div class="info-value">
                                    <?php if ($profile['verified']): ?>
                                        <span class="verification-badge badge-verified">Verified Owner</span>
                                    <?php else: ?>
                                        <span class="verification-badge badge-pending">Pending Verification</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">NIC Document</div>
                                <div class="info-value">
                                    <?php if ($profile['nic_doc']): ?>
                                        <span class="verification-badge badge-verified">Uploaded</span>
                                    <?php else: ?>
                                        <span class="verification-badge badge-unverified">Not Uploaded</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Recent Activity -->
                    <div class="profile-section">
                        <h3 class="section-title">Recent Activity</h3>
                        <div class="activity-timeline" id="activity-timeline">
                            <!-- Activity items will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-profile-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($profile['full_name']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($profile['phone']) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($user_role == 'student'): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php echo $profile['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?php echo $profile['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?php echo $profile['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="university">University</label>
                                <input type="text" class="form-control" id="university" name="university" value="<?php echo htmlspecialchars($profile['university']) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo $profile['date_of_birth'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="emergency_contact">Emergency Contact</label>
                                <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" value="<?php echo htmlspecialchars($profile['emergency_contact']) ?>">
                            </div>
                        </div>
                    </div>
                    <?php elseif ($user_role == 'owner'): ?>
                    <div class="form-group">
                        <label for="business_name">Business Name</label>
                        <input type="text" class="form-control" id="business_name" name="business_name" value="<?php echo htmlspecialchars($profile['business_name']) ?>">
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="current_password">Current Password (to change password)</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    loadRecentActivity();
});

function editProfile() {
    $('#editProfileModal').modal('show');
}

function saveProfile() {
    const formData = new FormData(document.getElementById('edit-profile-form'));
    
    // Validate passwords if provided
    const newPassword = formData.get('new_password');
    const confirmPassword = formData.get('confirm_password');
    
    if (newPassword && newPassword !== confirmPassword) {
        alert('New passwords do not match');
        return;
    }
    
    if (newPassword && newPassword.length < 6) {
        alert('New password must be at least 6 characters long');
        return;
    }
    
    $.ajax({
        url: 'ajax.php?action=update_profile',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Profile updated successfully');
                    $('#editProfileModal').modal('hide');
                    location.reload();
                } else {
                    alert(result.message || 'Failed to update profile');
                }
            } catch(e) {
                alert('Failed to update profile');
            }
        },
        error: function() {
            alert('Failed to update profile');
        }
    });
}

function loadRecentActivity() {
    $.ajax({
        url: 'ajax.php?action=get_user_activity',
        method: 'POST',
        data: {user_id: <?php echo $user_id ?>},
        success: function(response) {
            try {
                const activities = JSON.parse(response);
                displayActivities(activities);
            } catch(e) {
                console.error('Failed to load activities:', e);
            }
        },
        error: function() {
            console.error('Failed to load activities');
        }
    });
}

function displayActivities(activities) {
    const timeline = document.getElementById('activity-timeline');
    
    if (!activities || activities.length === 0) {
        timeline.innerHTML = '<div class="empty-state"><i class="fa fa-history"></i><p>No recent activity</p></div>';
        return;
    }
    
    const activitiesHtml = activities.map(activity => `
        <div class="timeline-item">
            <div class="timeline-date">${new Date(activity.created_at).toLocaleDateString()}</div>
            <div class="timeline-title">${activity.title}</div>
            <div class="timeline-description">${activity.description}</div>
        </div>
    `).join('');
    
    timeline.innerHTML = activitiesHtml;
}
</script>
