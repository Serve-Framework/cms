<?php admin_enqueueScripts();?>
<!DOCTYPE html>
<html lang="en">
<head>

	<!-- META -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $ADMIN_PAGE_TITLE; ?> | Serve CMS</title>

	<!-- FAVICONS -->
	<link rel="shortcut icon"                    href="<?php echo admin_assets_url(); ?>/img/logo.png">
	<link rel="apple-touch-icon" sizes="57x57"   href="<?php echo admin_assets_url(); ?>/img/logo.png">
	<link rel="apple-touch-icon" sizes="72x72"   href="<?php echo admin_assets_url(); ?>/img/logo.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo admin_assets_url(); ?>/img/logo.png">

	<!-- SCRIPTS/STYLES -->
	<?php echo serve_head(); ?>

</head>
<body>