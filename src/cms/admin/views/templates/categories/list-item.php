<?php use serve\utility\Str;

?>
<?php
	$parent1  = $category->parent();
	$parent2  = !$parent1 ? false : $category->parent()->parent();
	$children = $category->children();
?>
<!-- LIST ITEM -->
<div class="row list-row">
	<div class="media">
		<div class="media-left">
			<?php if ($category->id !== 1) : ?>
			<div class="form-field">    
		        <span class="checkbox checkbox-primary">
		            <input type="checkbox" class="js-bulk-action-cb" name="categories[]" id="cb-category-<?php echo $category->id; ?>" value="<?php echo $category->id; ?>" />
		            <label for="cb-category-<?php echo $category->id; ?>"></label>
		        </span>
		    </div>
		    <?php endif; ?>
		</div>
		<div class="media-body gutter-md">
			<div>
	            <a class="color-white h5" href="<?php echo the_category_url($category->id); ?>" target="_blank">
	            	<?php echo $category->name; ?>
	            </a>
	        </div>
	        <?php if ($parent1) : ?>
	        <div>
            	<span class="color-secondary p5">@&nbsp;&nbsp;</span>
				<?php if ($parent2) : ?>
					<a class="color-secondary p5" href="<?php echo the_category_url($parent2->id); ?>" target="_blank">
		            	<?php echo $parent2->name; ?>
		            </a>
		            <span  class="color-secondary p5">&nbsp;/&nbsp;</span>
				<?php endif; ?>
					<a class="color-secondary p5" href="<?php echo the_category_url($parent1->id); ?>" target="_blank">
		            	<?php echo $parent1->name; ?>
		            </a>
		            <span class="color-secondary p5">&nbsp;/&nbsp;</span>
		            <a class="color-secondary p5 font-bolder" href="<?php echo the_category_url($category->id); ?>" target="_blank">
		            	<?php echo $category->name; ?>
		            </a>
		    </div>
			<?php endif; ?>
	        <span class="color-gray">Slug:&nbsp;"<?php echo $category->slug; ?>"&nbsp;-&nbsp;</span>
	        <a class="p6 color-secondary" href="/admin/posts/?category=<?php echo $category->id; ?>">
	           	with <?php echo $category->article_count; ?> posts
	       	</a>

	       	<div class="color-gray p6">
	       		<?php echo !empty($category->description) ? Str::reduce($category->description, 100, '...') : ''; ?>
	       	</div>
	       	<div class="taxonomy-edit-wrap collapsed" id="category-edit-<?php echo $category->id; ?>">
	       		<div class="roof-xs col-8">
	       			<form method="post" class="js-validation-form">
				        <div class="form-field on-primary row margin-xs-s">				            
				            <input type="text" name="name" id="category_name_<?php echo $category->id; ?>" placeholder="Category name" value="<?php echo $category->name; ?>" data-js-required="true">
				            <label for="category_name_<?php echo $category->id; ?>">Name</label>
				        </div>

				        <div class="form-field on-primary row margin-xs-s">
				            <input type="text" name="slug" id="category_slug_<?php echo $category->id; ?>" placeholder="category-slug" value="<?php echo $category->slug; ?>" data-js-required="true" class="js-mask-alpha-dash">
				            <label for="category_slug_<?php echo $category->id; ?>">Slug</label>
				        </div>

				        <div class="form-field on-primary row margin-xs-s">
							<select name="parent" id="category_<?php echo $category->id; ?>_parent">
								<option value="0" <?php echo $category->parent_id > 0 ? '' : 'selected'; ?>>None</option>
								<?php foreach (all_the_categories() as $parent) : ?>
									<?php
									$skip = false;

									// You can't set the parent to itself or uncategorized as the parent
									if ($parent->id === $category->id || $parent->id === 1)
									{
										$skip = true;
									}

									// You can't set the parent to a child of this category
									foreach ($children as $child)
									{
										if ($child->id === $parent->id)
										{
											$skip = true;

											break;
										}
									}

									if ($skip)
									{
										continue;
									}
									?>
									<option value="<?php echo $parent->id; ?>" <?php echo $parent->id === $category->parent_id  ? 'selected' : ''; ?>><?php echo $parent->name; ?></option>
								<?php endforeach; ?>
							</select>
							<label for="category_<?php echo $category->id; ?>_parent">Parent</label>
				        </div>

				        <div class="form-field on-primary row margin-xs-s">
				            <textarea name="description" id="category_description_<?php echo $category->id; ?>" style="resize: vertical;" rows="5"><?php echo $category->description; ?></textarea>
				             <label for="category_description_<?php echo $category->id; ?>">Description</label>
				        </div>
				        
				        <input type="hidden" name="categories[]" value="<?php echo $category->id; ?>">
				        <input type="hidden" name="access_token" value="<?php echo $ACCESS_TOKEN; ?>">
				        <input type="hidden" name="bulk_action"  value="update">

				        <button type="button" class="btn btn-pure js-collapse" data-collapse-target="category-edit-<?php echo $category->id; ?>">Cancel</button>
				        <button type="submit" class="btn btn-pure btn-primary">Update Category</button>
				    </form>
	       		</div>
	       	</div>
		</div>
		<div class="media-right nowrap">
			<?php if ($category->id !== 1) : ?>
			<a href="#" class="btn btn-pure btn-circle tooltipped tooltipped-n js-collapse" data-collapse-target="category-edit-<?php echo $category->id; ?>" data-tooltip="Quick edit category">
				<span class="glyph-icon glyph-icon-pencil icon-xs"></span>
			</a>
			<a href="#" class="btn btn-pure btn-circle tooltipped tooltipped-n" data-tooltip="Clear category" onclick="document.getElementById('clear-form-<?php echo $category->id; ?>').submit()">
				<span class="glyph-icon glyph-icon-unlink icon-xs"></span>
			</a>
	        <a href="#" class="btn btn-pure btn-circle tooltipped tooltipped-n js-confirm-delete" data-item="category" data-form="delete-form-<?php echo $category->id; ?>" data-tooltip="Delete category">
				<span class="glyph-icon glyph-icon-bin2 icon-xs"></span>
			</a>
			<form method="post" id="clear-form-<?php echo $category->id; ?>" style="display: none">
				<input type="hidden" name="access_token" value="<?php echo $ACCESS_TOKEN; ?>">
				<input type="hidden" name="bulk_action"  value="clear">
				<input type="hidden" name="categories[]" value="<?php echo $category->id; ?>">
			</form>
			<form method="post" id="delete-form-<?php echo $category->id; ?>" style="display: none">
				<input type="hidden" name="access_token" value="<?php echo $ACCESS_TOKEN; ?>">
				<input type="hidden" name="bulk_action"  value="delete">
				<input type="hidden" name="categories[]" value="<?php echo $category->id; ?>">
			</form>
			<?php endif; ?>
		</div>
	</div>
</div>
