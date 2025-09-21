<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config/db_connect.php' 
?>
<?php
$booking_id = $_GET['id'];
$booking = $conn->query("SELECT b.*, l.title as listing_title, l.description, l.price_lkr as price, l.address, l.main_image,
								u.email as landlord_email, u.phone as landlord_contact
						 FROM bookings b 
						 LEFT JOIN listings l ON b.listing_id = l.id 
						 LEFT JOIN users u ON l.owner_id = u.id 
						 WHERE b.id = '$booking_id'")->fetch_assoc();
?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large><b>Booking Details</b></large>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<h5>Property Information</h5>
						<div class="form-group">
							<label class="control-label">Property Title:</label>
							<p><?php echo $booking['listing_title'] ?></p>
						</div>
						<div class="form-group">
							<label class="control-label">Description:</label>
							<p><?php echo $booking['description'] ?></p>
						</div>
						<div class="form-group">
							<label class="control-label">Price:</label>
							<p><strong>LKR <?php echo number_format($booking['price'], 2) ?></strong></p>
						</div>
						<div class="form-group">
							<label class="control-label">Address:</label>
							<p><?php echo $booking['address'] ?></p>
						</div>
					</div>
					<div class="col-md-6">
						<h5>Booking Information</h5>
						<div class="form-group">
							<label class="control-label">Start Date:</label>
							<p><?php echo date('M d, Y', strtotime($booking['start_date'])) ?></p>
						</div>
						<?php if($booking['end_date']): ?>
						<div class="form-group">
							<label class="control-label">End Date:</label>
							<p><?php echo date('M d, Y', strtotime($booking['end_date'])) ?></p>
						</div>
						<?php endif; ?>
						<div class="form-group">
							<label class="control-label">Status:</label>
							<p>
								<?php if($booking['status'] == 'pending'): ?>
									<span class="badge badge-warning">Pending</span>
								<?php elseif($booking['status'] == 'approved'): ?>
									<span class="badge badge-success">Approved</span>
								<?php else: ?>
									<span class="badge badge-danger">Rejected</span>
								<?php endif; ?>
							</p>
						</div>
						<div class="form-group">
							<label class="control-label">Student Note:</label>
							<p><?php echo $booking['student_note'] ?: 'No message provided' ?></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h5>Landlord Information</h5>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Email:</label>
									<p><?php echo $booking['landlord_email'] ?></p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Phone:</label>
									<p><?php echo $booking['landlord_contact'] ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if(!empty($booking['main_image'])): ?>
				<div class="row">
					<div class="col-md-12">
						<h5>Property Image</h5>
						<img src="assets/uploads/<?php echo $booking['main_image'] ?>" alt="Property Image" class="img-fluid" style="max-height: 300px;">
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
