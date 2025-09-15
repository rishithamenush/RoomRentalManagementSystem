<?php include 'db_connect.php' ?>
<style>
    .complaint-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 30px;
        border-left: 5px solid #dc3545;
    }
    .complaint-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .complaint-card.under-review {
        border-left-color: #ffc107;
    }
    .complaint-card.resolved {
        border-left-color: #28a745;
    }
    .complaint-card.dismissed {
        border-left-color: #6c757d;
    }
    
    .complaint-header {
        background: #f8f9fa;
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
    }
    .complaint-id {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }
    .complaint-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }
    .complaint-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-under-review { background: #fff3cd; color: #856404; }
    .status-resolved { background: #d4edda; color: #155724; }
    .status-dismissed { background: #e2e3e5; color: #383d41; }
    
    .complaint-content {
        padding: 20px;
    }
    .complaint-description {
        margin-bottom: 20px;
        line-height: 1.6;
        color: #555;
    }
    .complaint-details {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        padding: 5px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    .detail-label {
        font-weight: 600;
        color: #333;
    }
    .detail-value {
        color: #666;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
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
    .btn-resolve {
        background: #28a745;
        color: white;
    }
    .btn-resolve:hover {
        background: #218838;
        color: white;
    }
    .btn-dismiss {
        background: #6c757d;
        color: white;
    }
    .btn-dismiss:hover {
        background: #5a6268;
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
    .btn-reopen {
        background: #ffc107;
        color: #212529;
    }
    .btn-reopen:hover {
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
    .stat-total { color: #007bff; }
    .stat-under-review { color: #ffc107; }
    .stat-resolved { color: #28a745; }
    .stat-dismissed { color: #6c757d; }
    
    .priority-high { border-left-color: #dc3545 !important; }
    .priority-medium { border-left-color: #ffc107 !important; }
    .priority-low { border-left-color: #17a2b8 !important; }
    
    .admin-notes {
        background: #e3f2fd;
        border: 1px solid #bbdefb;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }
    .admin-notes h6 {
        color: #1976d2;
        margin-bottom: 10px;
    }
    .admin-notes p {
        margin: 0;
        color: #555;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Manage Complaints</h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row stats-cards">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-total" id="total-complaints">
                    <?php 
                    $total = $conn->query("SELECT COUNT(*) as count FROM complaints")->fetch_assoc()['count'];
                    echo $total;
                    ?>
                </div>
                <div class="stat-label">Total Complaints</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-under-review" id="under-review-complaints">
                    <?php 
                    $under_review = $conn->query("SELECT COUNT(*) as count FROM complaints WHERE status = 'under_review'")->fetch_assoc()['count'];
                    echo $under_review;
                    ?>
                </div>
                <div class="stat-label">Under Review</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-resolved" id="resolved-complaints">
                    <?php 
                    $resolved = $conn->query("SELECT COUNT(*) as count FROM complaints WHERE status = 'resolved'")->fetch_assoc()['count'];
                    echo $resolved;
                    ?>
                </div>
                <div class="stat-label">Resolved</div>
                </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-number stat-dismissed" id="dismissed-complaints">
                    <?php 
                    $dismissed = $conn->query("SELECT COUNT(*) as count FROM complaints WHERE status = 'dismissed'")->fetch_assoc()['count'];
                    echo $dismissed;
                    ?>
                </div>
                <div class="stat-label">Dismissed</div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active" data-status="all">All Complaints</button>
        <button class="filter-tab" data-status="under_review">Under Review</button>
        <button class="filter-tab" data-status="resolved">Resolved</button>
        <button class="filter-tab" data-status="dismissed">Dismissed</button>
        <button class="filter-tab" data-priority="high">High Priority</button>
    </div>

    <div class="row" id="complaints-container">
        <?php
        $complaints = $conn->query("SELECT c.*, 
                                          sp.full_name as student_name, sp.university,
                                          op.full_name as owner_name,
                                          l.title as listing_title, l.address as listing_address,
                                          ap.full_name as resolver_name
                                   FROM complaints c 
                                   LEFT JOIN users u1 ON c.by_student_id = u1.id
                                   LEFT JOIN student_profiles sp ON u1.id = sp.user_id
                                   LEFT JOIN users u2 ON c.against_owner_id = u2.id
                                   LEFT JOIN owner_profiles op ON u2.id = op.user_id
                                   LEFT JOIN listings l ON c.listing_id = l.id
                                   LEFT JOIN users u3 ON c.resolver_admin_id = u3.id
                                   LEFT JOIN admin_profiles ap ON u3.id = ap.user_id
                                   ORDER BY c.created_at DESC");

        if ($complaints->num_rows > 0):
            while ($complaint = $complaints->fetch_assoc()):
                $status_class = 'status-' . str_replace('_', '-', $complaint['status']);
                $priority_class = 'priority-' . ($complaint['priority'] ?? 'medium');
        ?>
        <div class="col-lg-6 col-md-12 complaint-item" data-status="<?php echo $complaint['status'] ?>" data-priority="<?php echo $complaint['priority'] ?? 'medium' ?>">
            <div class="complaint-card <?php echo $status_class ?> <?php echo $priority_class ?>">
                <div class="complaint-header">
                    <div class="complaint-id">Complaint #<?php echo $complaint['id'] ?></div>
                    <div class="complaint-title"><?php echo htmlspecialchars($complaint['title'] ?? 'No Title') ?></div>
                    <div class="complaint-meta">
                        <span class="status-badge <?php echo $status_class ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])) ?>
                        </span>
                        <small class="text-muted">
                            <i class="fa fa-calendar"></i> <?php echo date('M d, Y', strtotime($complaint['created_at'])) ?>
                        </small>
                    </div>
                </div>
                
                <div class="complaint-content">
                    <div class="complaint-description">
                        <?php echo nl2br(htmlspecialchars($complaint['description'])) ?>
                    </div>
                    
                    <div class="complaint-details">
                        <div class="detail-row">
                            <span class="detail-label">Complaint By:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($complaint['student_name'] ?: 'Unknown Student') ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Against Owner:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($complaint['owner_name'] ?: 'Unknown Owner') ?></span>
                        </div>
                        <?php if ($complaint['listing_title']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Property:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($complaint['listing_title']) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="detail-row">
                            <span class="detail-label">Priority:</span>
                            <span class="detail-value"><?php echo ucfirst($complaint['priority'] ?? 'Medium') ?></span>
                        </div>
                        <?php if ($complaint['resolved_at']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Resolved:</span>
                            <span class="detail-value"><?php echo date('M d, Y', strtotime($complaint['resolved_at'])) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($complaint['resolver_name']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Resolved By:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($complaint['resolver_name']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($complaint['admin_notes']): ?>
                    <div class="admin-notes">
                        <h6><i class="fa fa-sticky-note"></i> Admin Notes</h6>
                        <p><?php echo nl2br(htmlspecialchars($complaint['admin_notes'])) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="action-buttons">
                        <button class="btn-action btn-view" onclick="viewComplaint(<?php echo $complaint['id'] ?>)">
                            <i class="fa fa-eye"></i> View Details
                        </button>
                        
                        <?php if($complaint['status'] == 'under_review'): ?>
                            <button class="btn-action btn-resolve" onclick="resolveComplaint(<?php echo $complaint['id'] ?>)">
                                <i class="fa fa-check"></i> Resolve
                            </button>
                            <button class="btn-action btn-dismiss" onclick="dismissComplaint(<?php echo $complaint['id'] ?>)">
                                <i class="fa fa-times"></i> Dismiss
                            </button>
                        <?php elseif($complaint['status'] == 'resolved'): ?>
                            <button class="btn-action btn-reopen" onclick="reopenComplaint(<?php echo $complaint['id'] ?>)">
                                <i class="fa fa-redo"></i> Reopen
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
                <i class="fa fa-exclamation-triangle"></i>
                <h4>No complaints found</h4>
                <p>There are no complaints to review at the moment.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Complaint Details Modal -->
<div class="modal fade" id="complaintModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="complaintTitle"></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="complaintDetails">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="resolveBtn" style="display: none;">Resolve</button>
                <button type="button" class="btn btn-warning" id="dismissBtn" style="display: none;">Dismiss</button>
                <button type="button" class="btn btn-info" id="reopenBtn" style="display: none;">Reopen</button>
            </div>
        </div>
    </div>
</div>

<!-- Resolution Modal -->
<div class="modal fade" id="resolutionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resolve Complaint</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="resolution-form">
                    <input type="hidden" id="resolve-complaint-id">
                    <div class="form-group">
                        <label for="resolution-notes">Resolution Notes</label>
                        <textarea class="form-control" id="resolution-notes" rows="4" placeholder="Please provide details about how this complaint was resolved..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmResolution()">Resolve Complaint</button>
            </div>
        </div>
    </div>
</div>

<!-- Dismissal Modal -->
<div class="modal fade" id="dismissalModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dismiss Complaint</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="dismissal-form">
                    <input type="hidden" id="dismiss-complaint-id">
                    <div class="form-group">
                        <label for="dismissal-notes">Dismissal Reason</label>
                        <textarea class="form-control" id="dismissal-notes" rows="4" placeholder="Please provide a reason for dismissing this complaint..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="confirmDismissal()">Dismiss Complaint</button>
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
        
        const filter = $(this).data('status') || $(this).data('priority');
        const filterType = $(this).data('status') ? 'status' : 'priority';
        filterComplaints(filter, filterType);
    });
});

function filterComplaints(filter, type) {
    if (filter === 'all') {
        $('.complaint-item').show();
    } else {
        $('.complaint-item').hide();
        $(`.complaint-item[data-${type}="${filter}"]`).show();
    }
}

function viewComplaint(complaintId) {
    $.ajax({
        url: 'ajax.php?action=get_complaint_details',
        method: 'POST',
        data: {id: complaintId},
        success: function(response) {
            try {
                var complaint = JSON.parse(response);
                $('#complaintTitle').text('Complaint #' + complaint.id);
                $('#complaintDetails').html(generateComplaintDetails(complaint));
                
                // Show action buttons based on status
                if (complaint.status === 'under_review') {
                    $('#resolveBtn').show().attr('onclick', `resolveComplaint(${complaintId})`);
                    $('#dismissBtn').show().attr('onclick', `dismissComplaint(${complaintId})`);
                    $('#reopenBtn').hide();
                } else if (complaint.status === 'resolved') {
                    $('#resolveBtn').hide();
                    $('#dismissBtn').hide();
                    $('#reopenBtn').show().attr('onclick', `reopenComplaint(${complaintId})`);
                } else {
                    $('#resolveBtn').hide();
                    $('#dismissBtn').hide();
                    $('#reopenBtn').hide();
                }
                
                $('#complaintModal').modal('show');
            } catch(e) {
                alert('Failed to load complaint details');
            }
        },
        error: function() {
            alert('Failed to load complaint details');
        }
    });
}

function generateComplaintDetails(complaint) {
    return `
        <div class="row">
            <div class="col-md-6">
                <h6>Complaint Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>ID:</strong></td><td>#${complaint.id}</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="badge badge-${getStatusClass(complaint.status)}">${complaint.status.replace('_', ' ')}</span></td></tr>
                    <tr><td><strong>Priority:</strong></td><td>${complaint.priority || 'Medium'}</td></tr>
                    <tr><td><strong>Created:</strong></td><td>${new Date(complaint.created_at).toLocaleDateString()}</td></tr>
                    ${complaint.resolved_at ? `<tr><td><strong>Resolved:</strong></td><td>${new Date(complaint.resolved_at).toLocaleDateString()}</td></tr>` : ''}
                </table>
            </div>
            <div class="col-md-6">
                <h6>Parties Involved</h6>
                <table class="table table-sm">
                    <tr><td><strong>Complaint By:</strong></td><td>${complaint.student_name || 'Unknown'}</td></tr>
                    <tr><td><strong>Against Owner:</strong></td><td>${complaint.owner_name || 'Unknown'}</td></tr>
                    ${complaint.listing_title ? `<tr><td><strong>Property:</strong></td><td>${complaint.listing_title}</td></tr>` : ''}
                    ${complaint.resolver_name ? `<tr><td><strong>Resolved By:</strong></td><td>${complaint.resolver_name}</td></tr>` : ''}
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h6>Description</h6>
                <p>${complaint.description}</p>
                ${complaint.admin_notes ? `
                    <h6>Admin Notes</h6>
                    <div class="admin-notes">
                        <p>${complaint.admin_notes}</p>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}

function getStatusClass(status) {
    switch(status) {
        case 'resolved': return 'success';
        case 'under_review': return 'warning';
        case 'dismissed': return 'secondary';
        default: return 'secondary';
    }
}

function resolveComplaint(complaintId) {
    $('#resolve-complaint-id').val(complaintId);
    $('#resolution-notes').val('');
    $('#resolutionModal').modal('show');
}

function confirmResolution() {
    const complaintId = $('#resolve-complaint-id').val();
    const notes = $('#resolution-notes').val();
    
    if (!notes.trim()) {
        alert('Please provide resolution notes');
        return;
    }
    
    $.ajax({
        url: 'ajax.php?action=update_complaint_status',
        method: 'POST',
        data: {id: complaintId, status: 'resolved', notes: notes},
        success: function(response) {
            if (response == 1) {
                alert('Complaint resolved successfully');
                $('#resolutionModal').modal('hide');
                location.reload();
            } else {
                alert('Failed to resolve complaint');
            }
        },
        error: function() {
            alert('Failed to resolve complaint');
        }
    });
}

function dismissComplaint(complaintId) {
    $('#dismiss-complaint-id').val(complaintId);
    $('#dismissal-notes').val('');
    $('#dismissalModal').modal('show');
}

function confirmDismissal() {
    const complaintId = $('#dismiss-complaint-id').val();
    const notes = $('#dismissal-notes').val();
    
    if (!notes.trim()) {
        alert('Please provide dismissal reason');
        return;
    }
    
    $.ajax({
        url: 'ajax.php?action=update_complaint_status',
        method: 'POST',
        data: {id: complaintId, status: 'dismissed', notes: notes},
        success: function(response) {
            if (response == 1) {
                alert('Complaint dismissed successfully');
                $('#dismissalModal').modal('hide');
                location.reload();
            } else {
                alert('Failed to dismiss complaint');
            }
        },
        error: function() {
            alert('Failed to dismiss complaint');
        }
    });
}

function reopenComplaint(complaintId) {
    if (confirm('Are you sure you want to reopen this complaint?')) {
        $.ajax({
            url: 'ajax.php?action=update_complaint_status',
            method: 'POST',
            data: {id: complaintId, status: 'under_review'},
            success: function(response) {
                if (response == 1) {
                    alert('Complaint reopened successfully');
                    location.reload();
                } else {
                    alert('Failed to reopen complaint');
                }
            },
            error: function() {
                alert('Failed to reopen complaint');
            }
        });
    }
}
</script>
