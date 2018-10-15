<?php 

	require_once '_inc/config.php';

	// Go away if we are not authorized
	if ( ! can_edit() ) {
		redirect( '/' );
	}	

	include_once 'partials/header.php';

	// Get page
	$page = get_page( 'home' );

	// Get social links
	$social_links = get_social_links();
?>

	<!-- Header -->
	<header class="header">
		<!-- Site Controls -->
		<div class="site-controls">
			<a href="<?php echo BASE_URL; ?>">
				<i class="fas fa-chevron-left"></i> BACK
			</a>
		</div>
		<!-- END Site Controls -->

		<!-- Logo -->
		<div class="logo">
			<h1>
				<a href="#">
					Edit
				</a>
			</h1>
		</div>
		<!-- END Logo -->
	</header>
	<!-- END Header -->

	<!-- Main -->
	<main class="main">
		<!-- Edit -->
		<section class="section edit">
			<!-- Edit Form -->
			<form action="<?php echo BASE_URL; ?>/_admin/page-edit.php" method="post" class="post" id="form-edit">
				<div class="form-group">
					<label for="title">Title: <sup class="required">*</sup></label> 
					<input type="text" name="title" id="title" class="form-control" value="<?php echo $page->title; ?>">
				</div>

				<div class="form-group">
					<label for="info">Info: <sup class="required">*</sup></label>
					<input type="text" name="info" id="info" class="form-control" value="<?php echo $page->info; ?>">
				</div>

				<div class="form-group">
					<label for="info_highlight">Info highlight:</label>
					<input type="text" name="info_highlight" id="info_highlight" class="form-control" value="<?php echo $page->info_highlight; ?>">
				</div>

				<div class="form-group">
					<label for="body">Text: <sup class="required">*</sup></label>
					<textarea name="body" id="body" rows="10" class="form-control"><?php echo $page->body; ?></textarea>
				</div>

				<?php add_social_links_from_partial( $social_links ); ?>

				<div class="form-add-more">
					<a href="#" class="link-no-underline">add more</a>
				</div>

				<div class="form-meta">
					<small>
						Allowed social sites: <span class="highlight"><?php echo get_allowed_social_names(); ?></span>
					</small>
				</div>

				<input type="hidden" name="page_name" value="<?php echo $page->name; ?>">
				
				<div class="form-group form-group-btn">
					<input type="submit" value="SEND" class="form-control form-btn">
				</div>
			</form>
			<!-- END Edit Form -->
		</section>
		<!-- END Edit -->
	</main>
	<!-- Main -->

<?php include_once 'partials/footer.php'; ?>