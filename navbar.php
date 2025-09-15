
<style>
	.collapse a{
		text-indent:10px;
	}
	nav#sidebar{
		/*background: url(assets/uploads/<?php echo $_SESSION['system']['cover_img'] ?>) !important*/
	}
</style>

<nav id="sidebar" class='mx-lt-5 bg-dark' >
		
		<div class="sidebar-list">
				<a href="index.php?page=dashboard" class="nav-item nav-dashboard"><span class='icon-field'><i class="fa fa-tachometer-alt "></i></span> Dashboard</a>
				
				<?php if($_SESSION['login_role'] == 'student'): ?>
				<a href="index.php?page=search" class="nav-item nav-search"><span class='icon-field'><i class="fa fa-search "></i></span> Search Rooms</a>
				<a href="index.php?page=bookings" class="nav-item nav-bookings"><span class='icon-field'><i class="fa fa-bookmark "></i></span> My Bookings</a>
				<a href="index.php?page=messages" class="nav-item nav-messages"><span class='icon-field'><i class="fa fa-comments "></i></span> Messages</a>
				<a href="index.php?page=reviews" class="nav-item nav-reviews"><span class='icon-field'><i class="fa fa-star "></i></span> Reviews</a>
				<a href="index.php?page=complaints" class="nav-item nav-complaints"><span class='icon-field'><i class="fa fa-exclamation-triangle "></i></span> Complaints</a>
				<a href="index.php?page=profile" class="nav-item nav-profile"><span class='icon-field'><i class="fa fa-user "></i></span> Profile</a>
				
				<?php elseif($_SESSION['login_role'] == 'owner'): ?>
				<a href="index.php?page=listings" class="nav-item nav-listings"><span class='icon-field'><i class="fa fa-home "></i></span> My Listings</a>
				<a href="index.php?page=create_listing" class="nav-item nav-create_listing"><span class='icon-field'><i class="fa fa-plus "></i></span> Add Listing</a>
				<a href="index.php?page=bookings" class="nav-item nav-bookings"><span class='icon-field'><i class="fa fa-bookmark "></i></span> Bookings</a>
				<a href="index.php?page=messages" class="nav-item nav-messages"><span class='icon-field'><i class="fa fa-comments "></i></span> Messages</a>
				<a href="index.php?page=reviews" class="nav-item nav-reviews"><span class='icon-field'><i class="fa fa-star "></i></span> Reviews</a>
				<a href="index.php?page=profile" class="nav-item nav-profile"><span class='icon-field'><i class="fa fa-user "></i></span> Profile</a>
				
				<?php elseif($_SESSION['login_role'] == 'admin'): ?>
				<a href="index.php?page=moderate_listings" class="nav-item nav-moderate_listings"><span class='icon-field'><i class="fa fa-check-circle "></i></span> Moderate Listings</a>
				<a href="index.php?page=manage_users" class="nav-item nav-manage_users"><span class='icon-field'><i class="fa fa-users "></i></span> Manage Users</a>
				<a href="index.php?page=complaints" class="nav-item nav-complaints"><span class='icon-field'><i class="fa fa-exclamation-triangle "></i></span> Complaints</a>
				<a href="index.php?page=reports" class="nav-item nav-reports"><span class='icon-field'><i class="fa fa-chart-bar "></i></span> Reports</a>
				<a href="index.php?page=audit_logs" class="nav-item nav-audit_logs"><span class='icon-field'><i class="fa fa-history "></i></span> Audit Logs</a>
				<a href="index.php?page=site_settings" class="nav-item nav-site_settings"><span class='icon-field'><i class="fa fa-cogs "></i></span> Settings</a>
				<?php endif; ?>
		</div>

</nav>
<script>
	$('.nav_collapse').click(function(){
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
