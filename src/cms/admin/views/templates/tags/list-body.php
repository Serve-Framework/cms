<div class="list-body">
	<?php if (empty($tags)) : ?>
		<?php include 'list-empty.php'; ?>
	<?php else : ?>
		<?php foreach ($tags as $tag) : ?>
			<?php include 'list-item.php'; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>