<ul class="tab-nav tab-border">
	<?php foreach (admin_sirebar_links()['settings']['children'] as $name => $item) : ?>
		<li><a href="<?php echo $item['link']; ?>" <?php  echo $item['active'] ? 'class="active"' : '' ; ?>><?php echo $item['text']; ?></a></li>
	<?php endforeach; ?>
</ul>


