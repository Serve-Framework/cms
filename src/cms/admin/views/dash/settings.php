<!-- PAGE WRAP -->
<div class="dash-wrap js-dash-wrap">

	<!-- HEADING -->
	<section class="page-heading">
		<h1>Settings</h1>
	</section>

	<?php include dirname(__DIR__) . '/templates/post-message.php'; ?>

	<!-- TAB NAV -->
	<?php include dirname(__DIR__) . '/templates/settings/tab-nav.php'; ?>

	<!-- SETTINGS FORM -->
	<?php foreach (admin_sirebar_links()['settings']['children'] as $name => $item) : ?>

		<?php if ($item['active'] && $name === 'settingsAccount') : ?>
			
			<!-- ACCOUNT -->
			<?php include dirname(__DIR__) . '/templates/settings/account.php'; ?>

		<?php elseif ($item['active'] && $name === 'settingsAuthor') : ?>

			<!-- AUTHOR FORM -->
			<?php include dirname(__DIR__) . '/templates/settings/author.php'; ?>

			<!-- MEDIA LIBRARY -->
			<div class="avatar-media-wrapper js-triggerable-media">
				<?php include dirname(__DIR__) . '/templates/media/media-library.php'; ?>
			</div>

		<?php elseif ($item['active'] && $name === 'settingsServe') : ?>
			
			<!-- SERVE FORM -->
			<?php include dirname(__DIR__) . '/templates/settings/serve.php'; ?>

		<?php elseif ($item['active'] && $name === 'settingsAccess') : ?>
			
			<!-- ACCESS FORM -->
			<?php include dirname(__DIR__) . '/templates/settings/access.php'; ?>

		<?php elseif ($item['active'] && $name === 'settingsUsers') : ?>
			
			<!-- USERS FORM -->
			<?php include dirname(__DIR__) . '/templates/settings/users.php'; ?>

		<?php elseif ($item['active'] && $name === 'settingsAnalytics') : ?>
			
			<!-- ANALYTICS FORM -->
			<?php include dirname(__DIR__) . '/templates/settings/analytics.php'; ?>

		<?php elseif ($item['active'] && $name === 'settingsTools') : ?>
			
			<!-- TOOOLS FORM -->
			<?php include dirname(__DIR__) . '/templates/settings/tools.php'; ?>

		<?php endif;?> 
		
	<?php endforeach; ?>

</div>

<?php include dirname(__DIR__) . '/templates/sidebar.php'; ?>
