<!-- PAGE WRAP -->
<div class="dash-wrap js-dash-wrap">

	<!-- HEADING -->
	<section class="page-heading">
		<h1>Commentors</h1>
	</section>

	<?php include dirname(__DIR__) . '/templates/post-message.php'; ?>

	<!-- TABS -->
	<div class="floor-sm">
		<ul class="tab-nav tab-border">
			<li><a href="/admin/leads/">Leads</a></li>
			<li><a href="/admin/comments/">Comments</a></li>
		    <li><a href="/admin/comment-users/" class="active">Commentors</a></li>
		</ul>
	</div>
	
	<!-- LIST -->
	<section class="items-list">

		<!-- LIST POWERS -->
		<?php include dirname(__DIR__) . '/templates/commentusers/list-powers.php'; ?>

		<!-- LIST BODY -->
		<?php include dirname(__DIR__) . '/templates/commentusers/list-body.php'; ?>

		<!-- LIST FOOTER -->
		<?php include dirname(__DIR__) . '/templates/commentusers/list-pagination.php'; ?>

	</section>

</div>

<?php include dirname(__DIR__) . '/templates/sidebar.php'; ?>