<?php require_once '_inc/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0, shrink-to-fit=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="description" content="Ask anything. So much freedom in one place.">

	<title><?php echo show_site_title(); ?></title>

	<!-- Import Favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
	<link rel="manifest" href="favicon/site.webmanifest">
	<link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#223967">
	<link rel="shortcut icon" href="favicon.ico">
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="msapplication-config" content="favicon/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	<!-- END Import Favicon -->

	<!-- Import Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Poppins:400,700&amp;subset=latin-ext" rel="stylesheet">
	<!-- END Import Fonts -->
	
	<!-- Import Font Awesome -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<!-- END Import Font Awesome -->

	<!-- Import CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css">
	<link rel="stylesheet" href="<?php echo asset( '/css/main.css' ); ?>">
	<!-- END Import CSS -->

	<noscript>
	    <style type="text/css">
	        #wrapper {display:none;}
	        #no-js-warning {display:block}
	    </style>
	</noscript>
</head>
<body id="body">
	<div id="no-js-warning">
		<h2>JavaScript is disabled!</h2> 
		<p>Why you want to do so? Please enable JavaScript in your web browser!</p>
	</div>

	<div class="wrapper" id="wrapper">
		<div id="page-status" class="page-status">
			<?php echo show_page_status(); ?>
		</div>