<!-- ICONS -->
<?php include dirname(__DIR__) . '/templates/writer/icons.php'; ?>

<!-- PAGE WRAP FOR SIDEBAR NOT USERD -->
<div class="dash-wrap js-dash-wrap hidden"></div>

<!-- CONTAINER -->
<div class="row site-container">
	
	<!-- WRITER -->
	<?php include dirname(__DIR__) . '/templates/writer/writer.php'; ?>

	<!-- READER -->
	<?php include dirname(__DIR__) . '/templates/writer/reader.php'; ?>
	
	<!-- REVIEW/PUBLISH -->
	<?php include dirname(__DIR__) . '/templates/writer/review.php'; ?>

</div>

<!-- FOOTER -->
<?php include dirname(__DIR__) . '/templates/writer/footer.php'; ?>

<!-- MEDIA LIBRARY -->
<div class="writer-media-wrapper js-triggerable-media">
	<?php include dirname(__DIR__) . '/templates/media/media-library.php'; ?>
</div>

<!-- OFFLINE JS -->
<?php include dirname(__DIR__) . '/templates/writer/offline.php'; ?>

<!-- CONTEXT MENU -->
<?php include dirname(__DIR__) . '/templates/writer/context-menu.php'; ?>

<?php include dirname(__DIR__) . '/templates/sidebar.php'; ?>