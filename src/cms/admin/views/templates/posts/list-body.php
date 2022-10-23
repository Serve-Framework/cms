<div class="list-body">
	<?php if (empty($posts)) : ?>
		<?php include 'list-empty.php'; ?>
	<?php else : ?>
		<?php foreach ($posts as $article) : ?>
			<?php include 'list-item.php'; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>