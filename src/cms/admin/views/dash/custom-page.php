<!-- PAGE WRAP -->
<div class="dash-wrap js-dash-wrap">

	<!-- HEADING -->
	<section class="page-heading">
		<h1><?php echo trim(explode('|', admin_the_title())[0]); ?></h1>
	</section>

	<!-- POST MESSAGE -->
	<?php include dirname(__DIR__) . '/templates/post-message.php'; ?>

	<!-- CUSTOM PAGE CONTENT -->
	<?php
		$template = $serve->Filters->apply('adminPageTemplate', admin_page_name());

		if (file_exists($template))
		{
			require_once($template);
		}
	?>
	
</div>

<?php include dirname(__DIR__) . '/templates/sidebar.php'; ?>