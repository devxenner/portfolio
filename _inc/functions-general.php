<?php 

/**
 * Can Edit
 *
 * Very simple auth system if you can't be bothered with real login
 * ONLY FOR REALLY SMALL PERSONAL PROJECTS!!!
 *
 * @return bool    true if we can edit otherwise false
 */
function can_edit() {
	// Check if we have 'edit.php' in url
	// Return false if not
	if ( ! strpos( $_SERVER['REQUEST_URI'], 'edit.php' ) ) return false;

	// Save 'what' and 'add' from get if we have something otherwise save false
	$what = isset( $_GET['what'] ) ? $_GET['what'] : false;
	$add = isset( $_GET['add'] ) ? $_GET['add'] : false;
	
	// Return true if 'what' and 'add' contains right values if not return false
	return $what === 'admonolae17' && $add == 55413;
}



/**
 * Asset
 *
 * Creates absolute URL to asset file
 *
 * @param   string    $path   path to asset file
 * @param   string    $base   asset base url
 * @return  string    absolute URL to asset file
 */
function asset( $path, $base = BASE_URL . '/assets/' ) {
	// Trim '/' on both sides
	$path = trim($path, '/');

	// Return string with sanitized url to asset file
	return filter_var( $base . $path, FILTER_SANITIZE_URL );
}



/**
 * Simple Redirect
 *
 * Takes the page, status code
 * Redirect to certain page using BASE_URL constant
 * If page is set to 'back' redirect it to previous page
 *
 * @param   string     $page to redirect
 * @param   integer    $status_code (optional) status code
 * @return  void
 */
function redirect( $page, $status_code = 302 ) {
	// Trim '/' on both sides
	$page = ltrim( $page, '/' );

	// Create absolute url to page
	$location = BASE_URL . $page;

	// If page is set to back return to previous page
	if ( $page == 'back' ) {
		$location = $_SERVER['HTTP_REFERER'];
	}

	// Set header and end the application
	header( "Location: $location", true, $status_code );
	die();
}



/**
 * Show Site Title
 *
 * Creates site title based on SITE_NAME constant plus current page
 *
 * @return  string     string containing site title
 */
function show_site_title() {
	// Find current file and delete .php extension from file name
	$page_name = basename( $_SERVER['SCRIPT_NAME'], '.php' );

	// Save home if page name is index
	if ( $page_name === 'index' ) {
		$page_name = 'home';
	}

	// Create site title based on SITE_NAME constant
	$site_title = SITE_NAME . ' | ' . ucfirst( $page_name );

	return $site_title;
}



/**
 * Auto Update Copyright
 *
 * Takes the copyright year, the current year and compares them
 * If copyright passed into function is different from the current one, add a dash with current year
 * Example: Year passed into function : '2017'
 * Result: '2017' or '2017-2018'
 * 
 * @param   string    $copyright_year to compare with current year
 * @return  string    copyright year with '-' or without '-'
 */
function auto_update_copyright( $copyright_year ) {
	// Find out the current year
	$current_year = date( 'Y' );

	// Compare current year with copyright year passed into function
	return $copyright_year . ( ( $copyright_year != $current_year ) ? '-' . $current_year : '' );
}



/**
 * Convert to Array of Objects
 *
 * Very simple trick for converting array of arrays to array of objects
 * Returns array of objects
 *
 * @return  array    array of objects
 */
function convert_to_array_of_objects( $data ) {
	return json_decode( json_encode( $data ) );
}