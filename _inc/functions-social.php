<?php

/**
 * Is Allowed Social Name
 *
 * Checks for same value in two provided arrays
 * Returns false if mismatch
 * Returns true if provided arrays have same values
 *
 * @param   array    $social_names   social names to check
 * @param   array    $allowed_social_names   allowed social names
 * @return  bool     true if social name is allowed otherwise false
 */
function is_allowed_social_name( $social_names, $allowed_social_names ) {
	foreach ( $social_names as $social_name ) {
		// Check if we have social name and if social name is in the allowed social names array
		//  If not, set false and exit loop
		if ( $social_name && ! in_array( $social_name, $allowed_social_names ) ) {
			return false;
		}
	}

	return true;
}



/**
 * Create Social Classes
 *
 * Creates classes for css based on social names and type
 * Type is optional with defaul value of 'square'
 * Returns array of names for css classes
 *
 * @param   array     $social_names   social names to create classes
 * @param   string    $type   type of icon e.g. 'square' which is also default value
 * @return  array     array containing classes
 */
function create_social_classes( $social_names, $type = 'square' ) {
    $social_classes = [];
    $social_class = '';
    $prefix = 'fa-';
    
    if ( $type === 'square' ) {
        // Square type e.g. 'facebook-square'
        foreach ( $social_names as $social_name ) {
        	if ( ! $social_name ) continue;

            if ( $social_name === 'linkedin' ) {
                // Linkedin doesn't have square icon
                $social_class = $prefix . $social_name;
            } else {
                $social_class = $prefix . $social_name . '-' . $type;
            }
            
            array_push( $social_classes, $social_class );
        }
    } else {
        // Normal type e.g. 'facebook'
        foreach ( $social_names as $social_name ) {
        	if ( ! $social_name ) continue;

            $social_class = $prefix . $social_name;
            
            array_push( $social_classes, $social_class );
        }
    }
    
    return $social_classes;
}



/**
 * Helper Function - Prepare Social Links
 *
 * Used for array_map function as a helper function to create social links
 * Returns array of social names, urls, classes collected together
 *
 * @param   array    $social_name    social names
 * @param   array    $social_url     social urls
 * @param   array    $social_class   social classes
 * @return  array    array containing names, urls, classes
 */
function prepare_social_links( $social_name, $social_url, $social_class ) {
	$social_sites = [
		'name'  => $social_name,
		'url'   => $social_url,
		'class' => $social_class
	];

	return $social_sites;
}



/**
 * Create Social Links
 *
 * This function is used to create social links based on names, urls, classes
 * Returns array of arrays
 *
 * @param   array    $social_names    social names to create social links
 * @param   array    $social_url      social names to create social links
 * @return  array    array containing social links
 */
function create_social_links( $social_names, $social_urls ) {
	// Create social classes for css
	$social_classes = create_social_classes( $social_names );

	// Create social links
	$social_links = array_map( 'prepare_social_links', $social_names, $social_urls, $social_classes );

	return $social_links;
}



/**
 * Validate Social
 *
 * Validates social names, urls
 * Takes social param as array
 * Returns true if everything is valid otherwise returns false
 *
 * @param   array    $social social array to validate
 * @return  true     true if everything is valid otherwise false
 */
function validate_social( $social ) {
	// If we have array.
	foreach ( $social as $social_name => $social_url ) {
		if ( ! $social_name && ! $social_url ) {
			// If we don't have both social name and url. Continue...
			continue;
		}

		// Check social name and social url
		if ( $social_name && ! $social_url  ) {
			// If we have social name but no social url. Set error message and return false.
			set_page_status( 'error', 'Social url is required along with the name!' );

			return false;
		} elseif ( ! $social_name && $social_url ) {
			// If we have social url but no social name. Set error message and return false.
			set_page_status( 'error', 'Social name is required along with the url!' );
			return false;
		}

		// Check social url
		if ( ! filter_var( $social_url, FILTER_VALIDATE_URL ) ) {
			// If social url is not valid url. Set error message and return false.
			set_page_status( 'error', 'Social site url is not valid!' );
			return false;
		}
	}

	// Everything is valid
	return true;
}



/**
 * Prepare Social
 *
 * Checks if social name is allowed
 * Prepares social before inserting into database
 * Takes social names, urls, allowed social names as arrays
 * Returns true if everything is valid otherwise returns false
 *
 * @param   array    $social_names   social names to check
 * @param   array    $social_urls    social names to check
 * @param   array    $allowed_social_names   allowed social names
 * @return  true     true if everything is prepared and we can insert otherwise false
 */
function prepare_social( $social_names, $social_urls, $allowed_social_names ) {
	// If social site is not allowed
	if ( ! is_allowed_social_name( $social_names, $allowed_social_names ) ) {
		// Set error message and return false
		set_page_status( 'error', 'Please check if social site is in the allowed list under the form.' );
		return false;
	}

	// We have allowed social name

	// Combine social names and urls
	$social = array_combine( $social_names, $social_urls );

	// Validate social
	if ( ! validate_social( $social ) ) {
		// If social is not valid, return false
		return false;
	}

	// Social is validated
	return true;
}



/**
 * Get Social Links From DB
 *
 * Grabs all social links from the DB
 *
 * @return  array    or empty array
 */
function get_social_links_from_db() {
	// Access global database variable
	global $db;

	// Select everything from social
	$query = $db->prepare(
		"SELECT * 
		 FROM social"
	);

	// Execute select query
	$query->execute();

	// Fetch as array of objects if success
	if ( $query->rowCount() ) {
		// Fetch as array of objects
		$result = $query->fetchAll( PDO::FETCH_ASSOC );
	} else {
		// Save empty array
		$result = [];
	}

	return $result;
}



/**
 * Get Social Links From SESSION
 *
 * Grabs all social links from the SESSION
 *
 * @return  array of objects    or empty array
 */
function get_social_links_from_session() {
	// Check if we have social inside session
	if ( isset( $_SESSION['social_links'] ) ) {
		// Save social from session
		$result = (object) $_SESSION['social_links'];

		// Unset session
		// Because we want to survive only one refresh
		unset( $_SESSION['social_links'] );
	} else {
		// Save empty array
		$result = [];
	}

	return $result;
}



/**
 * Get Social Links
 *
 * Returns social links from either a database or a session
 * 
 * @param   bool     $auto_format   format social links to show, default is false
 * @return  array    array containing social links
 */
function get_social_links( $auto_format = false ) {
	// Try to fetch social links from session
	$social_links_session = get_social_links_from_session();

	// Return social links from session if we have session
	if ( $social_links_session ) {
		return $social_links_session;
	}

	// We don't have social links inside session so we want to return social links from database

	// Get social links from db
	$social_links_db = get_social_links_from_db();

	// Format social links if auto_format is true
	if ( $auto_format ) {
		return format_social_links( $social_links_db );
	}

	return convert_to_array_of_objects( $social_links_db );
}



/**
 * Add Social Links From Partial
 *
 * Adds social links from partial file once or multiple times
 *
 * @return  void
 */
function add_social_links_from_partial( $social_links ) {
	// Check if we have social links
	if ( $social_links ) {
		foreach ( $social_links as $social_link ) {
			// Include partial file containing part of form
			include 'partials/social-links-form.php';
		}
	}

	// We don't have social links 
	// We want to include social links at least once and empty
	include_once 'partials/social-links-form.php';
}



/**
 * Get Allowed Social Names
 *
 * Returns string containing all allowed social names from global variable
 *
 * @return  string    string containing allowed social names separeted by comma
 */
function get_allowed_social_names() {
	// Access global allowed social names
	global $allowed_social_names;

	return implode( ', ', $allowed_social_names );
}



/**
 * Format Social Links
 *
 * Returns sanitized, formated object containing social links
 *
 * @param   array     $page   page to format
 * @return  object    containing formated page
 */
function format_social_links( $social_links ) {
	$cleaned_social_links = [];

	// Clean it up
	foreach ( $social_links as $social_link ) {
		$cleaned_social_links[] = array_map( 'plain', $social_link );
	}

	return convert_to_array_of_objects( $cleaned_social_links );
}

/**
 * Show Social Links
 *
 * Creates html needed for social links to be displayed
 *
 * @param   array     $social_links   social links to display
 * @return  string    html needed for page status to display
 */
function show_social_links( $social_links ) {
	// Prepare html variable
	$html = '';

	// If we have social links
	if ( $social_links ) {
		$html .= '<ul class="list-unstyled social-list">';

		foreach ( $social_links as $social_link ) {
			// Create page status element
			$html .= 	'<li>';
			$html .= 		'<a href="'. $social_link->url .'" class="social-link link-no-underline">';
			$html .= 			'<i class="fab '. $social_link->class .' social-icon social-icon-brand social-icon-'. $social_link->name .'"></i>';
			$html .= 		'</a>';
			$html .= 	'</li>';
		}

		$html .= '</ul>';
	}	

	return $html;
}