<div class="list-body">
	<?php if (empty($comments)) : ?>
		<?php include 'list-empty.php'; ?>
	<?php else : ?>
		<?php foreach ($comments as $comment) : ?>
			<?php include 'list-item.php'; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>