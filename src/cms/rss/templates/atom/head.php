<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?><feed
	xmlns="http://www.w3.org/2005/Atom"
	xmlns:thr="http://purl.org/syndication/thread/1.0"
	xml:lang="en-US"
	>
	<title type="text"><?php echo htmlspecialchars(website_title()); ?></title>
	<subtitle type="text"><?php echo htmlspecialchars(website_description()); ?></subtitle>
	<updated><?php echo the_modified_time('c'); ?></updated>
	<link rel="alternate" type="text/html" href="<?php echo the_canonical_url(); ?>" />
	<link rel="self" type="application/atom+xml" href="<?php echo trim(the_canonical_url(), '/'); ?>/feed/atom/" />
	<generator uri="https://serve-framework.github.io/" version="<?php echo $serve::VERSION; ?>">Serve CMS</generator>