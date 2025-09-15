<?php include 'config/db_connect.php' ?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large><b><?php echo $_SESSION['login_role'] == 'student' ? 'My Bookings' : 'Property Bookings'; ?></b></large>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered" id="bookings-table">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">Property</th>
								<?php if($_SESSION['login_role'] == 'student'): ?>
								<th class="text-center">Landlord</th>
								<?php else: ?>
								<th class="text-center">Student</th>
								<?php endif; ?>
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
							if($_SESSION['login_role'] == 'student') {
								// Student view: show their bookings
								$bookings = $conn->query("SELECT b.*, l.title as listing_title, u.email as landlord_email 
														FROM bookings b 
														LEFT JOIN listings l ON b.listing_id = l.id 
														LEFT JOIN users u ON l.owner_id = u.id 
														WHERE b.student_id = '{$_SESSION['login_id']}' 
														ORDER BY b.id DESC");
							} else {
								// Owner view: show bookings for their properties
								$bookings = $conn->query("SELECT b.*, l.title as listing_title, u.email as student_email 
														FROM bookings b 
														LEFT JOIN listings l ON b.listing_id = l.id 
														LEFT JOIN users u ON b.student_id = u.id 
														WHERE l.owner_id = '{$_SESSION['login_id']}' 
														ORDER BY b.id DESC");
							}
							while($row = $bookings->fetch_assoc()):
							?>
							<tr>
								<td class="text-center"><?php echo $i++ ?></td>
								<td class="text-center"><?php echo $row['listing_title'] ?></td>
								<td class="text-center">
									<?php if($_SESSION['login_role'] == 'student'): ?>
										<?php echo $row['landlord_email'] ?>
									<?php else: ?>
										<?php echo $row['student_email'] ?>
									<?php endif; ?>
								</td>
								<td class="text-center"><?php echo date('M d, Y', strtotime($row['start_date'])) ?></td>
								<td class="text-center"><?php echo $row['end_date'] ? date('M d, Y', strtotime($row['end_date'])) : 'N/A' ?></td>
								<td class="text-center">LKR <?php echo number_format($row['total_amount'], 2) ?></td>
								<td class="text-center">
									<?php if($row['status'] == 'pending'): ?>
										<span class="badge badge-warning">Pending</span>
									<?php elseif($row['status'] == 'approved'): ?>
										<span class="badge badge-success">Approved</span>
									<?php else: ?>
										<span class="badge badge-danger">Rejected</span>
									<?php endif; ?>
								</td>
								<td class="text-center">
									<button class="btn btn-sm btn-primary view_booking" type="button" data-id="<?php echo $row['id'] ?>">
										<i class="fa fa-eye"></i>
									</button>
									<?php if($_SESSION['login_role'] == 'owner' && $row['status'] == 'pending'): ?>
									<button class="btn btn-sm btn-success approve_booking" type="button" data-id="<?php echo $row['id'] ?>">
										<i class="fa fa-check"></i>
									</button>
									<button class="btn btn-sm btn-danger reject_booking" type="button" data-id="<?php echo $row['id'] ?>">
										<i class="fa fa-times"></i>
									</button>
									<?php endif; ?>
								</td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#bookings-table').dataTable();
	
	$('.view_booking').click(function(){
		uni_modal("Booking Details", "pages/view_booking.php?id=" + $(this).attr('data-id'), "mid-large");
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
</script>
