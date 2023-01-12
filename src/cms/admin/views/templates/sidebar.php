<!-- SIDEBAR -->
<section class="sidebar raised js-sidebar">
	<div class="sb-header">
		<a class="logo js-toggle-sb" href="#">
			<img src="<?php echo admin_assets_url() ?>/img/logo.png">
		</a>
	</div>
	<nav>
		<ul class="list-unstyled">
			<?php foreach (admin_sirebar_links() as $itemName => $item) : ?>
			<li class="<?php echo $item['active'] ? 'active' : ''; ?>">
				<?php if (!empty($item['children'])) : ?>
				<span class="glyph-icon glyph-icon-arrow-down5 toggle-list js-toggle-down"></span>
				<?php endif; ?>
				<a href="<?php echo $item['link']; ?>">
					<span class="glyph-icon glyph-icon-<?php echo $item['icon']; ?>"></span>
					<?php echo $item['text']; ?>
				</a>
				<?php if (!empty($item['children'])) : ?>
				<ul class="list-unstyled">
					<?php foreach ($item['children'] as $subName => $subItem) : ?>
						<li class="<?php echo $subItem['active'] ? 'active' : ''; ?>">
							<a href="<?php echo $subItem['link']; ?>">
								<span class="glyph-icon glyph-icon-<?php echo $subItem['icon']; ?>"></span>
								<?php echo $subItem['text']; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</nav>
</section>