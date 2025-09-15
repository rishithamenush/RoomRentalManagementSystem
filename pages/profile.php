<?php include 'config/db_connect.php' ?>
<?php
$user = $conn->query("SELECT * FROM users WHERE id = '{$_SESSION['login_id']}'")->fetch_assoc();
$role = $user['role'];
$profile = [];
if($role == 'student'){
	$pr = $conn->query("SELECT * FROM student_profiles WHERE user_id = '{$_SESSION['login_id']}'");
	if($pr && $pr->num_rows > 0) $profile = $pr->fetch_assoc();
}else if($role == 'owner'){
	$pr = $conn->query("SELECT * FROM owner_profiles WHERE user_id = '{$_SESSION['login_id']}'");
	if($pr && $pr->num_rows > 0) $profile = $pr->fetch_assoc();
}
?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large><b>My Profile</b></large>
			</div>
			<div class="card-body">
				<form action="" id="manage-profile">
					<input type="hidden" name="id" value="<?php echo $user['id'] ?>">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="full_name" class="control-label">Full Name</label>
								<input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($profile['full_name'] ?? '') ?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="email" class="control-label">Email</label>
								<input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email'] ?>" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone" class="control-label">Contact</label>
								<input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? '') ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="role" class="control-label">Role</label>
								<input type="text" class="form-control" id="role" name="role" value="<?php echo ucfirst($role) ?>" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="address" class="control-label">Address</label>
								<textarea class="form-control" id="address" name="address" rows="3"><?php echo $user['address'] ?></textarea>
							</div>
						</div>
					</div>
					<?php if($role == 'student'): ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="university" class="control-label">University</label>
								<input type="text" class="form-control" id="university" name="university" value="<?php echo htmlspecialchars($profile['university'] ?? '') ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="gender" class="control-label">Gender</label>
								<select class="form-control" id="gender" name="gender">
									<option value="" <?php echo (($profile['gender'] ?? '')=='')?'selected':''; ?>>Select Gender</option>
									<option value="male" <?php echo (($profile['gender'] ?? '')=='male')?'selected':''; ?>>Male</option>
									<option value="female" <?php echo (($profile['gender'] ?? '')=='female')?'selected':''; ?>>Female</option>
									<option value="other" <?php echo (($profile['gender'] ?? '')=='other')?'selected':''; ?>>Other</option>
								</select>
							</div>
						</div>
					</div>
					<?php endif; ?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="avatar" class="control-label">Profile Picture</label>
								<input type="file" class="form-control" id="avatar" name="avatar" onchange="displayImg(this,$(this))">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<?php if(!empty($user['avatar'])): ?>
									<img src="<?php echo (strpos($user['avatar'],'http')===0 ? $user['avatar'] : 'assets/uploads/'.$user['avatar']) ?>" alt="Profile Picture" id="cimg" width="150" height="150" style="object-fit: cover; border-radius: 50%;">
								<?php else: ?>
									<img src="assets/uploads/default-avatar.png" alt="Profile Picture" id="cimg" width="150" height="150" style="object-fit: cover; border-radius: 50%;">
								<?php endif; ?>
							</div>
						</div>
					</div>
					<center>
						<button class="btn btn-info btn-primary btn-block col-md-2">Update Profile</button>
					</center>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}
	
	$('#manage-profile').submit(function(e){
		e.preventDefault();
		start_load();
		
		if($('#password').val() != $('#confirm_password').val()){
			alert_toast("Password does not match.", "error");
			end_load();
			return false;
		}
		
		$.ajax({
			url: 'api/ajax.php?action=update_profile',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
		    success: function(resp){
		    	if(resp == 1){
		    		alert_toast("Profile updated successfully.", "success");
		    		end_load();
		    	} else {
		    		alert_toast("Error updating profile.", "error");
		    		end_load();
		    	}
		    }
		});
	});
</script>
