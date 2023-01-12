<div class="list-powers">
	 
	<!-- CHECK ALL -->
	<span class="checkbox checkbox-primary v-middle">
        <input type="checkbox" id="cb-article-checkall" class="js-list-check-all">
        <label for="cb-article-checkall"></label>
    </span>

	<!-- BULK ACTIONS -->
	<form class="inline-block js-bulk-actions-form v-middle" method="post">
		<input type="hidden" name="access_token" value="<?php echo $ACCESS_TOKEN; ?>">
		<div class="form-field field-group on-primary">
	    	<select name="bulk_action" id="bulk_action">
				<option value="" selected=""></option>
				<option value="clear">Clear</option>
				<option value="delete">Delete</option>
			</select>
			<label for="bulk_action">Bulk actions</label>
			<button type="submit" class="btn">Apply</button>
	    </div>
	</form>
	
	<!-- SPACER -->
	<span>&nbsp;&nbsp;</span>
	
    <!-- STATUS AND SORTS -->
    <div class="btn-group inline-block">

    	<!-- SORT -->
	    <div class="drop-container">
		    <button type="button" class="btn  btn-pure btn-dropdown js-drop-trigger">
		        Sort
		        &nbsp;<span class="caret-s"></span>
		    </button>
		    <div class="drop-menu drop-sw">
		        <div class="drop">
		            <ul>
		                <li class="drop-header">Sort by:</li>
		                <li><a href="/admin/categories/?<?php echo "sort=name&search=$queries[search]"; ?>" <?php if ($queries['sort'] === 'name') echo 'class="selected"'; ?>>Name</a></li>
						<li><a href="/admin/categories/?<?php echo "sort=count&search=$queries[search]"; ?>" <?php if ($queries['sort'] === 'count') echo 'class="selected"'; ?>>Article count</a></li>
					</ul>
		        </div>
		    </div>
		</div>

		<!-- COLLAPSE TOGGLE -->
		<a href="#" class="btn btn-pure tooltipped tooltipped-s js-expand-collapse-all" data-tooltip="Expad/Collapse all">
			<span class="glyph-icon glyph-icon-list"></span>
		</a>

		<!-- CLEAR -->
		<a href="/admin/categories/" class="btn btn-pure btn-primary tooltipped tooltipped-s" data-tooltip="Clear filters" <?php echo !$empty_queries ? '' : 'style="display:none;"'; ?> >
			<span class="glyph-icon glyph-icon-cross icon-xs"></span>
		</a>

		<script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function()
            {
                document.querySelector('.js-expand-collapse-all').addEventListener('click', function (e)
                {
                	e = e || window.event;
                	e.preventDefault();
                	var helper      = Hubble.helper();
                	var collapseAll = helper.$All('.taxonomy-edit-wrap');
                	var isOpen      = false;

                	for (i = 0; i < collapseAll.length; i++)
                	{
                		if (collapseAll[i].style.height === 'auto')
                		{
                			isOpen = true;
                		}
                	}

                	for (j = 0; j < collapseAll.length; j++)
                	{
                		if (isOpen)
                		{
                			collapseAll[j].style.height = '0';
                		}
                		else
                		{
                			collapseAll[j].style.height = 'auto';
                		}
					}                    
                });
            });
		</script>
	</div>

	<!-- SEARCH -->
	<form method="get" class="inline-block float-right">
		<input type="hidden" name="sort" value="<?php echo $queries['sort']; ?>">
	    <div class="form-field field-group on-primary">
	        <input type="text" name="search" id="search" value="<?php echo $queries['search']; ?>">
	        <label for="search">Search</label>
	        <button type="submit" class="btn">
	        	&nbsp;&nbsp;<span class="glyph-icon glyph-icon-search"></span>&nbsp;&nbsp;
	        </button>
	    </div>
	</form>

</div>