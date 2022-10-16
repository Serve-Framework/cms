<?php enqueueScripts(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo the_meta_title(); ?></title>
	<meta name="description" content="<?php echo the_meta_description(); ?>">
	<link rel="icon" href="<?php echo theme_url(); ?>/assets/img/logo.png">

	<link rel="canonical" title="<?php echo the_meta_title(); ?>" href="<?php echo the_canonical_url(); ?>">
	<?php if (the_previous_page_url()) : ?>
	<link rel="prev" title="<?php echo the_previous_page_title(); ?>" href="<?php echo the_previous_page_url(); ?>">
	<?php endif; ?>
	<?php if (the_next_page_url()) : ?>
	<link rel="next" title="<?php echo the_next_page_title(); ?>" href="<?php echo the_next_page_url(); ?>">
	<?php endif; ?>

	<?php if (is_single()) : ?>
	<link rel="alternate" type="application/atom+xml" title="Serve » Blog » Feed » <?php echo the_category_name(); ?> » <?php echo the_title(); ?>" href="<?php echo the_canonical_url(); ?>feed/atom/">
	<link rel="alternate" type="application/rdf+xml"   title="Serve » Blog » Feed » <?php echo the_category_name(); ?> » <?php echo the_title(); ?>" href="<?php echo the_canonical_url(); ?>feed/rdf/">
	<link rel="alternate" type="application/rss+xml"  title="Serve » Blog » Feed » <?php echo the_category_name(); ?> » <?php echo the_title(); ?>" href="<?php echo the_canonical_url(); ?>feed/rss/">

	<?php elseif (is_page()) : ?>
	<link rel="alternate" type="application/atom+xml" title="Serve » Feed » <?php echo the_title(); ?>" href="<?php echo the_canonical_url(); ?>feed/atom/">
	<link rel="alternate" type="application/rdf+xml"   title="Serve » Feed » <?php echo the_title(); ?>" href="<?php echo the_canonical_url(); ?>feed/rdf/">
	<link rel="alternate" type="application/rss+xml"  title="Serve » Feed » <?php echo the_title(); ?>" href="<?php echo the_canonical_url(); ?>feed/rss/">

	<?php elseif (is_blog_location() && is_front_page() && !(is_category() || is_author() || is_tag())) : ?>
	<link rel="alternate" type="application/atom+xml" title="Serve » Blog » Feed" href="<?php echo the_canonical_url(); ?>feed/atom/">
	<link rel="alternate" type="application/rdf+xml"   title="Serve » Blog » Feed" href="<?php echo the_canonical_url(); ?>feed/rdf/">
	<link rel="alternate" type="application/rss+xml"  title="Serve » Blog » Feed" href="<?php echo the_canonical_url(); ?>feed/rss/">

	<?php elseif (is_home() && is_front_page()) : ?>
	<link rel="alternate" type="application/atom+xml" title="Serve » Feed" href="<?php echo the_canonical_url(); ?>feed/atom/">
	<link rel="alternate" type="application/rdf+xml"   title="Serve » Feed" href="<?php echo the_canonical_url(); ?>feed/rdf/">
	<link rel="alternate" type="application/rss+xml"  title="Serve » Feed" href="<?php echo the_canonical_url(); ?>feed/rss/">

	<?php elseif (is_front_page() && (is_category() || is_author() || is_tag())) :  ?>
	<link rel="alternate" type="application/atom+xml" title="Serve » Blog » Feed » <?php echo the_taxonomy()->name; ?>" href="<?php echo the_canonical_url(); ?>feed/atom/">
	<link rel="alternate" type="application/rdf+xml"   title="Serve » Blog » Feed » <?php echo the_taxonomy()->name; ?>" href="<?php echo the_canonical_url(); ?>feed/rdf/">
	<link rel="alternate" type="application/rss+xml"  title="Serve » Blog » Feed » <?php echo the_taxonomy()->name; ?>" href="<?php echo the_canonical_url(); ?>feed/rss/">
	<?php endif; ?>

	<?php
	$social_thumb = theme_url() . '/assets/img/favicons/logo.png';

	if (is_single() || is_page())
	{
		$social_thumb = the_post_thumbnail_src();

		if (!$social_thumb)
		{
			$social_thumb = theme_url() . '/assets/img/favicons/logo.png';
		}
	}
	?>
	<meta name="twitter:card"   content="summary">
	<meta name="twitter:domain" content="<?php echo domain_name(); ?>">
	<meta name="twitter:title"  content="<?php echo the_meta_title(); ?>">
	<meta name="twitter:description" content="<?php echo the_meta_description(); ?>">
	<meta name="twitter:site"    content="@<?php echo $serve->Config->get('theme.social.twitter_handle'); ?>">
	<meta name="twitter:creator" content="@<?php echo $serve->Config->get('theme.social.twitter_handle'); ?>">
	<meta name="twitter:image:src" content="<?php echo $social_thumb; ?>">

	<meta property="og:locale"         content="en_US">
	<meta property="og:type"           content="<?php echo (is_single() ? 'article' : 'website'); ?>">
	<meta property="og:title"          content="<?php echo the_meta_title(); ?>">
	<meta property="og:description"    content="<?php echo the_meta_description(); ?>">
	<meta property="og:url"            content="<?php echo the_canonical_url(); ?>">
	<meta property="og:image"          content="<?php echo $social_thumb; ?>">
	<meta property="og:site_name"      content="<?php echo website_title(); ?>">
	<meta property="article:author"    content="<?php echo $serve->Config->get('theme.social.facebook'); ?>">
	<meta property="article:publisher" content="<?php echo $serve->Config->get('theme.social.facebook'); ?>">

	<meta property="fb:pages" content="foobar">
	<meta name="google-site-verification" content="fobar">
	<meta name="p:domain_verify" content="fobar">
	<meta name="msvalidate.01" content="fobar">

	<?php echo serve_head(); ?>

</head>
<body class="<?php echo the_page_type(); ?>-body <?php echo is_blog_location() ? 'blog-body' : ''; ?>">
	<header class="site-header">
		<div class="container">
			<a href="/" class="logo-wrap">
				<img class="logo" src="<?php echo theme_url(); ?>/assets/img/logo.png">
				<span class="logo-text">Serve CMS</span>
			</a>
			<a class="link" href="https://serve-framework.github.io/#/" target="_blank">Documentation</a>
			<a class="link" href="https://github.com/Serve-Framework" target="_blank">Github</a>
		</div>
	</header>
	<div class="page-container row">
		<?php include 'breadcrumbs.php'; ?>
