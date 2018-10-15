<?php

/**
 * Set Page Status
 *
 * Very simple flash messaging system
 * Takes status type param e.g. 'success' and custom message
 * Creates new session array 'page_status' with two values - type, message
 *
 * @param   string    $status_type   success, error,..
 * @param   string    $status_message   custom message
 * @return  void
 */
function set_page_status( $status_type, $status_message ) {
	// Unset session if exist
	if ( isset( $_SESSION['page_status'] ) ) {
		unset( $_SESSION['page_status'] ); 
	}
	
	// Set new session array
	$_SESSION['page_status'] = [
		'status_type'    => $status_type,
		'status_message' => $status_message
	];
}



/**
 * Get Page Status
 *
 * This function works for getting existing value from session which was set by set_page_status function
 * Takes status type param e.g. 'success' and custom message
 * Returns object containing status type, message and class based on 'page_status' session
 * 
 * @return  object    containing status
 */
function get_page_status() { 
	// Check if we have session 'page_status'. If not, return false
	if ( ! isset( $_SESSION['page_status'] ) ) {
		return false;
	}

	// Extract values from session
	extract( $_SESSION['page_status'] );

	// If we have page status. Create array with type and message
	$status = [
		'type'    => $status_type,
		'message' => $status_message,
		'class'   => 'page-status-' . $status_type
	];

	// Unset session
	unset( $_SESSION['page_status'] );

	// Convert array to object and return
	return (object) $status;
}



/**
 * Show Page Status
 *
 * Creates html needed for page status to be displayed
 * Returns html as a string
 *
 * @return  string    html needed for page status to display
 */
function show_page_status() {
	// Get page status
	$page_status = get_page_status();

	// Prepare html variable
	$html = '';

	// If we have something inside status
	if ( $page_status ) {
		// Create page status element
		$html .= '<div id="page-status-content" class="page-status-content ' . $page_status->class . '">';
		$html .= 	'<p>' . $page_status->message . '</p>';
		$html .= '</div>';
	}
	
	return $html;
}



/**
 * Validate Page
 *
 * Validates page title, info and much more
 * Takes page param as array
 * Returns true if everything is valid otherwise returns false
 *
 * @param   array    $page   page to valide
 * @return  bool     true    or false
 */
function validate_page( $page ) {
	// Save items from $page to variables
	$title = $page['title'];
	$info = $page['info'];
	$info_highlight = $page['info_highlight'];
	$body = $page['body'];

	// Set session
	$_SESSION['page']['title'] = $title;
	$_SESSION['page']['info'] = $info;
	$_SESSION['page']['info_highlight'] = $info_highlight;
	$_SESSION['page']['body'] = $body;

	// Validate title
	if ( empty( $title ) ) {
		// Set error message and return false if title is empty
		set_page_status( 'error', 'Title is required!' );
		return false;
	}

	// Validate info
	if ( empty( $info ) ) {
		// Set error message and return false if title is info
		set_page_status( 'error', 'Info is required!' );
		return false;
	}

	// Validate body
	if ( empty( $body ) ) {
		// Set error message and return false if title is body
		set_page_status( 'error', 'Text is required!' );
		return false;
	}

	return true;
}



/**
 * Get Page From DB
 *
 * Grabs everything about certain page from the DB
 *
 * @param   string    $page_name   page to edit
 * @return  array    or empty array
 */
function get_page_from_db( $page_name ) {
	// Access global database variable
	global $db;

	// Select everything from pages
	$query = $db->prepare(
		"SELECT p.* 
		 FROM pages p
		 WHERE p.name = :page_name"
	);

	// Execute select query
	$query->execute( [ 'page_name' => $page_name ] );

	// Fetch as array of objects if success
	if ( $query->rowCount() === 1 ) {
		// Fetch as array of objects
		$result = $query->fetch( PDO::FETCH_ASSOC );
	} else {
		// Save empty array
		$result = [];
	}

	return $result;
}



/**
 * Get Page From SESSION
 *
 * Grabs all about certain page from the SESSION
 *
 * @return  array of objects    or empty array
 */
function get_page_from_session() {
	// Check if we have page inside session
	if ( isset( $_SESSION['page'] ) ) {
		// Save page from session
		$result = (object) $_SESSION['page'];

		// Unset session
		// Because we want to survive only one refresh
		unset( $_SESSION['page'] );
	} else {
		// Save empty array
		$result = [];
	}

	return $result;
}



/**
 * Get Page
 *
 * Returns page from either a database or a session
 *
 * @param   string    $page_name     page to get
 * @param   bool      $auto_format   format page to show, default is false
 * @return  object    containing page from session or db
 */
function get_page( $page_name, $auto_format = false ) {
	// Try to fetch page from session
	$page_session = get_page_from_session();

	// Return page from session if we have session
	if ( $page_session ) {
		return $page_session;
	}

	// We don't have page inside session so we want to return page from database

	// Get page from db
	$page_db = get_page_from_db( $page_name );

	// Format page if auto_format is true
	if ( $auto_format ) {
		return format_page( $page_db );
	}

	return (object) $page_db;
}



/**
 * Format Page
 *
 * Returns sanitized, formated object containing page
 *
 * @param   array     $page   page to format
 * @return  object    containing formated page
 */
function format_page( $page ) {
	// Clean it up
	$page['title'] = plain( $page['title'] );
	$page['info'] = plain( $page['info'] );
	$page['body'] = plain( $page['body'] );		
		
	// Check if we have something to highlight
	if ( $page['info_highlight'] ) {
		// Clean info highlight
		$page['info_highlight'] = plain( $page['info_highlight'] );

		// Reassign info with highlighted section by strong tag
		$page['info'] = preg_replace("/\p{L}*?".preg_quote( $page['info_highlight'] )."\p{L}*/ui", "<strong>$0</strong>", $page['info'] );
	}
	
	// Format body
	$page['body'] = filter_url( $page['body'] );
	$page['body'] = add_paragraphs( $page['body'] );

	return (object) $page;
}