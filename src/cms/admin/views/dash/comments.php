<!-- PAGE WRAP -->
<div class="dash-wrap js-dash-wrap">

	<!-- HEADING -->
	<section class="page-heading">
		<h1>Comments</h1>
	</section>

	<?php include dirname(__DIR__) . '/templates/post-message.php'; ?>

	<div class="floor-sm">
		<ul class="tab-nav tab-border ">
			<li><a href="/admin/leads/">Leads</a></li>
			<li><a href="/admin/comments/" class="active">Comments</a></li>
		    <li><a href="/admin/comment-users/">Commentors</a></li>
		</ul>
	</div>

	<!-- LIST -->
	<section class="items-list">

		<!-- LIST POWERS -->
		<?php include dirname(__DIR__) . '/templates/comments/list-powers.php'; ?>

		<!-- LIST BODY -->
		<?php include dirname(__DIR__) . '/templates/comments/list-body.php'; ?>

		<!-- LIST FOOTER -->
		<?php include dirname(__DIR__) . '/templates/comments/list-pagination.php'; ?>

	</section>

</div>

<?php include dirname(__DIR__) . '/templates/sidebar.php'; ?>