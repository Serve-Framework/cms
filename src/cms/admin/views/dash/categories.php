<!-- PAGE WRAP -->
<div class="dash-wrap js-dash-wrap">

	<!-- HEADING -->
	<section class="page-heading">
		<h1>Categories</h1>
	</section>

	<?php include dirname(__DIR__) . '/templates/post-message.php'; ?>

	<!-- LIST -->
	<section class="items-list">

		<!-- LIST POWERS -->
		<?php include dirname(__DIR__) . '/templates/categories/list-powers.php'; ?>

		<!-- LIST BODY -->
		<?php include dirname(__DIR__) . '/templates/categories/list-body.php'; ?>

		<!-- LIST FOOTER -->
		<?php include dirname(__DIR__) . '/templates/categories/list-pagination.php'; ?>

	</section>

</div>

<?php include dirname(__DIR__) . '/templates/sidebar.php'; ?>