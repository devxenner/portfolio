<?php 

// Show All Errors
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);


// Run Sessions
if( ! session_id() ) @session_start();


// Define Constants
define( 'BASE_URL', 'http://portfolio.devxenner.com' );
define( 'SITE_EMAIL', 'work@devxenner.com' );
define( 'SITE_NAME', 'DevXenner | Web Developer' );
define( 'COPYRIGHT_NAME', 'devxenner.com' );


// Settings
$allowed_social_names = [ 'facebook', 'twitter', 'github', 'linkedin', 'youtube', 'google-plus' ];


// Connect to DB
$config = [

	'db' => [
		'type'     => 'mysql',
		'name'     => 'd47963_xenport',
		'server'   => 'wm40.wedos.net',
		'username' => 'a47963_xenport',
		'password' => 'Uru9x7AV',
		'charset'  => 'utf8mb4'
	]

];

try {
	$db = new PDO("{$config['db']['type']}:host={$config['db']['server']};dbname={$config['db']['name']};charset={$config['db']['charset']}", 
		$config['db']['username'], 
		$config['db']['password']);
} catch ( PDOException $e ) {
	echo 'Error while connecting to database!';
	die();
}

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


// Import functions
require_once 'functions-general.php';
require_once 'functions-string.php';
require_once 'functions-page.php';
require_once 'functions-social.php';