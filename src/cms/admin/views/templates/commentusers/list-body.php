<div class="list-body">
	<?php if (empty($commenters)) : ?>
		<?php include 'list-empty.php'; ?>
	<?php else : ?>
		<?php foreach ($commenters as $commenter) : ?>
			<?php include 'list-item.php'; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>