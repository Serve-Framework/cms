<!-- PAGE WRAP -->
<div class="dash-wrap js-dash-wrap <?php echo strtolower($postName); ?>">

	<!-- HEADING -->
	<section class="page-heading">
		<h1><?php echo $postName; ?></h1>
		<a href="/admin/writer/?post-type=<?php echo strtolower($postType); ?>" class="btn btn-circle btn-sm add-btn raised-3">
			<span class="glyph-icon glyph-icon-plus3"></span>
		</a>
	</section>

	<?php include dirname(__DIR__) . '/templates/post-message.php'; ?>

	<!-- LIST -->
	<section class="items-list">

		<!-- LIST POWERS -->
		<?php include dirname(__DIR__) . '/templates/posts/list-powers.php'; ?>

		<!-- LIST BODY -->
		<?php include dirname(__DIR__) . '/templates/posts/list-body.php'; ?>

		<!-- LIST FOOTER -->
		<?php include dirname(__DIR__) . '/templates/posts/list-pagination.php'; ?>

	</section>

</div>

<?php include dirname(__DIR__) . '/templates/sidebar.php'; ?>