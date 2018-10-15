<?php 
	include_once 'partials/header.php'; 

	// Get page
	$page = get_page( 'home', true );

	// Get social links
	$social_links = get_social_links( true );
?>
		<!-- Header -->
		<header class="header">
			<!-- Logo -->
			<div class="logo">
				<h1>
					<a href="<?php echo BASE_URL; ?>" class="link-no-underline">
						<?php echo $page->title; ?>
					</a>
				</h1>
			</div>
			<!-- END Logo -->
			
			<!-- Info -->
			<div class="info">
				<h2 class="info-title">
					<?php echo $page->info; ?>
				</h2>
			</div>
			<!-- END Info -->
		</header>
		<!-- END Header -->
		
		<!-- Main -->
		<main class="main">
			<!-- About -->
			<section class="section about">
				<h3 class="section-title">
					Who am I?
				</h3>
			
				<?php echo $page->body; ?>
			</section>
			<!-- END About -->

			<!-- Contact -->
			<section class="section contact">
				<h3 class="section-title">
					Contact
				</h3>

				<p>
					email: <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>
				</p>

				<?php echo show_social_links( $social_links ); ?>
			</section>
			<!-- END Contact -->
		</main>
		<!-- Main -->

<?php include_once 'partials/footer.php'; ?>