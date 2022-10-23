<!-- PAGE WRAP -->
<div class="dash-wrap js-dash-wrap">

	<!-- HEADING -->
	<section class="page-heading">
		<h1>Error Logs</h1>
	</section>

	<?php include dirname(__DIR__) . '/templates/post-message.php'; ?>

	<!-- TAB NAV -->
	<?php include dirname(__DIR__) . '/templates/logs/tab-nav.php'; ?>

	<!-- LIST -->
	<section class="items-list roof-xs">

		<!-- LIST POWERS -->
		<?php include dirname(__DIR__) . '/templates/logs/errors/list-powers.php'; ?>

		<!-- LIST BODY -->
		<?php include dirname(__DIR__) . '/templates/logs/errors/list-body.php'; ?>

		<!-- LIST FOOTER -->
		<?php include dirname(__DIR__) . '/templates/logs/errors/list-pagination.php'; ?>

	</section>
	
</div>

<?php include dirname(__DIR__) . '/templates/sidebar.php'; ?>