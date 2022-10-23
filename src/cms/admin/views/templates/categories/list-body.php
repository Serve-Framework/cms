<div class="list-body">
	<?php if (empty($categories)) : ?>
		<?php include 'list-empty.php'; ?>
	<?php else : ?>
		<?php foreach ($categories as $category) : ?>
			<?php include 'list-item.php'; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>