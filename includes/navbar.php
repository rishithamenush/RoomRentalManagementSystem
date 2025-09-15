<style>
	/* Enhanced sidebar styling */
	:root{
		--sb-bg:#1f2630;
		--sb-accent:#667eea;
		--sb-accent-2:#764ba2;
		--sb-text:#e9edf3;
		--sb-muted:#9aa3b2;
		--sb-hover:#2a3340;
	}

	nav#sidebar{
		background: linear-gradient(180deg, var(--sb-bg) 0%, #1b212b 100%) !important;
		border-right: 1px solid rgba(255,255,255,0.06);
		min-height: 100vh;
		padding-top: 10px;
	}

	#sidebar .sidebar-list{
		padding: 8px 10px 20px 10px;
	}

	#sidebar .nav-item{
		display: flex;
		align-items: center;
		gap: 10px;
		color: var(--sb-text);
		padding: 12px 14px;
		border-radius: 10px;
		margin: 6px 6px;
		text-decoration: none;
		font-weight: 500;
		transition: background .2s ease, color .2s ease, transform .1s ease;
	}

	#sidebar .nav-item .icon-field{
		width: 28px;
		height: 28px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: 8px;
		background: rgba(255,255,255,0.06);
		color: var(--sb-text);
	}

	#sidebar .nav-item:hover{
		background: var(--sb-hover);
		color: #fff;
		transform: translateX(2px);
	}

	#sidebar .nav-item.active{
		background: linear-gradient(90deg, var(--sb-accent) 0%, var(--sb-accent-2) 100%);
		color: #fff !important;
		box-shadow: 0 6px 16px rgba(102,126,234,.25);
	}

	#sidebar .nav-item.active .icon-field{
		background: rgba(255,255,255,0.2);
		color: #fff;
	}

	/* Section labels (optional) */
	#sidebar .section-label{
		color: var(--sb-muted);
		font-size: .75rem;
		letter-spacing: .08em;
		text-transform: uppercase;
		margin: 14px 16px 6px;
	}

	/* Small screen tweak */
	@media (max-width: 768px){
		#sidebar{ min-height: auto; }
	}
</style>

<nav id="sidebar" class='mx-lt-5' >
		
		<div class="sidebar-list">
				<div class="section-label">General</div>
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
				<div class="section-label">Administration</div>
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