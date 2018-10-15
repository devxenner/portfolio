<?php 

// Import config
require_once '../_inc/config.php';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	// Sanitize POST Array
	$_POST  = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

	// Trim POST Array
	$_POST  = filter_var( $_POST, \FILTER_CALLBACK, [ 'options' => 'trim' ] );
	
	// Page validation
	$can_edit_page = validate_page( $_POST );

	// Social validation
	if ( isset( $_POST['social_name'] ) && isset( $_POST['social_url'] ) ) {
		$social_names = $_POST['social_name'];
		$social_urls = $_POST['social_url'];

		// Check if we can edit
		$can_edit_social = prepare_social( $social_names, $social_urls, $allowed_social_names );

		// Create social links
		$social_links = create_social_links( $social_names, $social_urls );

		// Store social links inside session but covert inner arrays to objects
		$_SESSION['social_links'] = convert_to_array_of_objects( $social_links );
	}


	// Check if we can edit this page
	if ( $can_edit_page && $can_edit_social ) {
		// Before we edit DB we want to destroy ALL session variables.
		// Because they are used only if we have form errors.
		$_SESSION = array();

		// Update page
		$update_page = $db->prepare(
			"UPDATE 
				pages
			 SET
				title = :title,
				info = :info,
				info_highlight = :info_highlight,
				body = :body
			 WHERE	
				name = :name"
		);

		// Execute update
		$update_page->execute([
			'title' 		  => $_POST['title'],
			'info'  		  => $_POST['info'],
			'info_highlight'  => $_POST['info_highlight'],
			'body' 		      => $_POST['body'],
			'name' 			  => $_POST['page_name']
 		]);

 		// Delete all social links from DB
 		$delete_social = $db->prepare(
 			"DELETE FROM social"
 		);

 		// Execute delete
 		$delete_social->execute();

 		// If we have social links
 		if ( $social_links ) {
 			foreach ( $social_links as $social ) {
 				// Filter empty arrays
 				$social = array_filter( $social );

 				// Continue if we have nothing
 				if ( ! $social ) {
 					continue;
 				}

 				// Insert new social sites
				$insert_social = $db->prepare("
					INSERT INTO social (name, url, class)
					VALUES (:name, :url, :class)
				");

				// Execute insert
				$insert_social->execute([
					'name'   => $social['name'],
					'url'    => $social['url'],
					'class'  => $social['class']
				]);
 			}
 		}

		// Redirect with success message
		set_page_status( 'success', 'Page updated successfully!' );
		redirect( '/' );
	} else {
		// Redirect back if we can't edit
		redirect( 'back' );
	}
}