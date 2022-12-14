<!-- MEDIA LIBRARY WRAPPER -->
<div class="media-library js-media-library loading" data-blog-location="<?php echo blog_location(); ?>">

	<!-- POWERS -->
	<div class="list-powers media-powers js-media-powers">

		<!-- DEFAULT POWERS -->
		<span class="default-powers">
			<button type="button" class="btn btn-pure js-bulk-select-trigger">Bulk Select</button>
			<button type="button" class="btn btn-pure btn-primary js-collapse" data-collapse-target="dz-wrap">Add New</button>
		</span>

		<!-- SELECTION POWERS -->
		<span class="selection-powers">
			<button type="button" class="btn btn-pure js-cancel-bulk-select-trigger">Cancel Selection</button>
			<button type="button" class="btn btn-pure btn-danger js-bulk-delete-trigger">Delete Selected</button>
		</span>

		<button class="btn btn-pure btn-circle float-right close-media-lib js-close-media-lib">
			<span class="glyph-icon glyph-icon-cross2 icon-md"></span>
		</button>

	</div>

	<!-- DROPZONE -->
	<div class="hide-overflow collapsed" id="dz-wrap">
		<div class="card dz js-dz">
			<div class="pad-40 text-center dz-message">
				<div class="prompt">
					<div class="floor-sm">
						<span class="glyph-icon glyph-icon-camera3 color-primary icon-xl"></span>
					</div>
					<h3>Drop Files Here to Upload</h3>
					<p>Click or drop files to begin uploading.</p>
				</div>
			</div>
		</div>
	</div>
	
	<!-- MEDIA ITEMS -->
	<div class="media-items clearfix js-media-items roof-xs floor-xs">

		<!-- EMPTY MESSAGE -->
		<div class="card empty-msg">
			<div class="pad-40 text-center">
				<div class="roof-sm floor-sm">
					<span class="glyph-icon glyph-icon-camera3 color-primary icon-xl"></span>
				</div>
				<h3>No Media to Display!</h3>
				<p>There's currently no media items to display. Drag and drop an image to get started.</p>
			</div>
		</div>
	</div>

	<!-- SELECTED ATTACHMENT -->
	<div class="selected-media-container js-selected-media-container floor-sm">

		<div class="card">
			
			<div class="card-block">

				<!-- TOP POWERS -->
				<div class="attachment-powers">

					<span class="h3 powers-title">Attachment Details</span>
					<div class="inline-block float-right">
						<button class="btn btn-pure btn-circle js-image-left-trigger tooltipped tooltipped-s" data-tooltip="Previous attachment">
							<span class="glyph-icon glyph-icon-arrow-left4 icon-xs"></span>
						</button>
						<button class="btn btn-pure btn-circle js-image-right-trigger tooltipped tooltipped-s" data-tooltip="Next attachment">
							<span class="glyph-icon glyph-icon-arrow-right4 icon-xs"></span>
						</button>
						<button class="btn btn-pure btn-circle js-close-preview tooltipped tooltipped-se" data-tooltip="Close preview">
							<span class="glyph-icon glyph-icon-cross2 icon-xs"></span>
						</button>
					</div>
				</div>

				<!-- IMAGE PREIVEW AND DETAILS -->
				<div class="row roof-xs">

					<!-- PREVIEW -->
					<div class="col col-12 col-md-8 gutter-md-xl gutter-md-r">
						<div class="preview-wrapper card js-preivew-wrapper">

						</div>
					</div>

					<!-- DETAILS SIDEBAR -->
					<div class="col col-12 col-md-4 details-wrapper">

						<!-- SIDEBAR INFO -->
						<div class="details color-gray">
							<div><strong>File path:</strong> <span class="js-filepath"></span></div>
							<div><strong>File URL:</strong> <span class="js-fileurl"></span></div>
							<div><strong>File name:</strong> <span class="js-filename"></span></div>
							<div><strong>File type:</strong> <span class="js-filetype"></span></div>
							<div><strong>File size:</strong> <span class="js-filesize"></span></div>
							<div class="is-image"><strong>Dimensions:</strong> <span class="js-filedimensions"></span></div>
							<div><strong>Uploaded:</strong> <span class="js-filedate"></span></div>
							<div><strong>Uploaded by:</strong> <span class="js-fileuploader"></span></div>
						</div>

						<!-- INFO FORM - INSERT OR UPDATE -->
						<form class="media-details-form js-media-details-form roof-xs floor-xs">
							<div class="form-field on-primary row margin-xs-s">
								<input type="text" id="media_url" value="" readonly class="readonly">
								<label>URL</label>
							</div>
							<div class="form-field on-primary row margin-xs-s">
								<input type="text" name="media_title" id="media_title" value="">
								<label for="media_title">Title</label>
							</div>
							<div class="form-field on-primary row margin-xs-s" class="is-image">
								<input type="text" name="media_alt" id="media_alt" value="">
								<label for="media_alt">Alt Text</label>
							</div>
							
							<div class="form-field on-primary row margin-xs-s size-select">
								<select id="media_size" class="js-size-select">
									<option value="origional">Original</option>
									<?php foreach ($serve->Config->get('cms.uploads.thumbnail_sizes') as $suffix => $size) : ?>
									<option value="<?php echo $suffix; ?>"><?php echo ucfirst($suffix); ?> </option>
									<?php endforeach; ?>
								</select>
								<label for="media_size">Attachment Size</label>
							</div>

							<div class="form-field on-primary row margin-xs-s link-to">
								<label for="media_link_to_select">Link To</label>
								<select name="media_link_to_select" id="media_link_to_select">
									<option value="none" selected>None</option>
										<option value="file">Media File</option>
										<option value="attachment">Attachment Page</option>
										<option value="custom">Custom URL</option>
								</select>
								<div class="collapsed js-link-to-wrap">
									<div class="roof-xs">
										<input type="text" name="media_link_to_input" id="media_link_to_input" value="">
									</div>
								</div>
							</div>

							<input type="hidden" name="media_id" id="media_id" value="">

							<button type="button" class="btn btn-danger btn-pure with-spinner delete-media js-delete-media">
								<svg viewBox="0 0 64 64" class="loading-spinner"><circle class="path" cx="32" cy="32" r="30" fill="none" stroke-width="4"></circle></svg>
								Delete
							</button>

							<button type="button" class="btn btn-pure with-spinner update-media js-update-media">
								<svg viewBox="0 0 64 64" class="loading-spinner"><circle class="path" cx="32" cy="32" r="30" fill="none" stroke-width="4"></circle></svg>
								Update
							</button>
							
							<button type="button" class="btn btn-pure btn-primary with-spinner insert-media js-insert-media">
								<svg viewBox="0 0 64 64" class="loading-spinner"><circle class="path" cx="32" cy="32" r="30" fill="none" stroke-width="4"></circle></svg>
								Insert
							</button>

							<button type="button" class="btn btn-pure btn-primary with-spinner set-feature-image js-set-feature-image">
								Set As Feature Image
							</button>

							<button type="button" class="btn btn-pure btn-primary with-spinner set-author-avatar js-set-author-avatar">
								Set As Avatar
							</button>

							<button type="button" class="btn btn-pure btn-primary with-spinner set-image-trigger js-set-image-trigger">
								Select Image
							</button>
							
						</form>
					</div>
				
				</div>
			</div>
		</div>

	</div>

	<!-- LOADING SPINNER  -->
	<div class="loading-overlay">
		<svg viewBox="0 0 64 64" class="loading-spinner spinner-primary">
		    <circle class="path" cx="32" cy="32" r="30" fill="none" stroke-width="4"></circle>
		</svg>
	</div>

	<input type="hidden" class="hidden js-access-token" name="access_token" value="<?php echo $ACCESS_TOKEN; ?>">

</div>

<div class="progress-bar bg-gradient media-progress js-media-progress"><span style="width:0%;" class="progress"></span></div>