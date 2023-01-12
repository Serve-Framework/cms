<div class="col-12 col-md-8 roof-xs floor-xs">
   <form method="post" class="js-validation-form">
        <p class="color-gray">
            Your author information is used by Serve to display on posts you write and is 
            linked to your account credentials. You don't need to fill out everything - only 
            your name and slug are mandatory.
        </p>

        <div class="author-avatar-img js-author-avatar-img <?php if (!empty($USER->thumbnail_id)) echo 'active'; ?>">
            <div class="form-field row floor-xs">
                <label class="block">Avatar</label>
                <?php
                if (!empty($USER->thumbnail_id)) {
                    echo display_thumbnail(the_author_thumbnail($USER->id), 'original', '', '', '');
                }
                else {
                    echo '<img src="" >';
                }
                ?>
                <input  type="hidden" name="thumbnail_id" class="js-avatar-id" value="<?php echo $USER->thumbnail_id; ?>" />
                <button type="button" class="btn btn-pure select-img-trigger js-select-img-trigger js-show-media-lib">Select image</button>
                <button type="button" class="btn btn-pure remove-img-trigger js-remove-img-trigger">Remove image</button>
            </div>
        </div>

        <div class="row padding-xxs-s">
            <div class="form-field on-primary row">
                <input type="text" name="name" id="name" placeholder="John" value="<?php echo $USER->name; ?>" data-js-required="true">
                <label for="name">Name</label>
            </div>
            <p class="help-danger">* Please enter your name.</p>
        </div>
        
        <div class="row padding-xxs-s">        
            <div class="form-field on-primary row">            
                <input type="text" name="slug" id="slug" value="<?php echo $USER->slug; ?>" data-js-required="true" class="js-mask-alpha-dash">
                <label for="slug">Slug</label>
            </div>
            <p class="help-danger">* Please enter a url slug.</p>
        </div>

        <div class="row padding-xxs-s">
            <div class="form-field on-primary row">
                <textarea name="description" id="description"><?php echo $USER->description; ?></textarea>
                <label for="description">Description</label>
            </div>
        </div>

        <div class="row padding-xxs-s">
            <div class="form-field on-primary row">
                <input type="text" name="facebook" id="facebook" placeholder="http://facebook.com/example" value="<?php echo $USER->facebook; ?>" data-js-validation="url">
                <label for="facebook">FaceBook URL</label>
            </div>
            <p class="help-danger">* Please enter a valid url.</p>
        </div>

        <div class="row padding-xxs-s">
            <div class="form-field on-primary row">
                <input type="text" name="twitter" id="twitter" placeholder="http://twitter.com/example" value="<?php echo $USER->twitter; ?>" data-js-validation="url">
                <label for="twitter">Twitter URL</label>
                <p class="help-danger">* Please enter a valid url.</p>
            </div>
        </div>

        <div class="row padding-xxs-s">
            <div class="form-field on-primary row">
                <input type="text" name="gplus" id="gplus" placeholder="http://plus.google.com/example" value="<?php echo $USER->gplus; ?>" data-js-validation="url">
                <label for="gplus">Google+ URL</label>
            </div>
            <p class="help-danger">* Please enter a valid url.</p>
        </div>

        <div class="row padding-xxs-s">
            <div class="form-field on-primary row">
                <input type="text" name="instagram" id="instagram" placeholder="http://instagram.com/example" value="<?php echo $USER->instagram; ?>" data-js-validation="url">
                <label for="gplus">Instagram URL</label>
            </div>
            <p class="help-danger">* Please enter a valid url.</p>
        </div>
        
        <input type="hidden" name="access_token" value="<?php echo $ACCESS_TOKEN; ?>">
        <input type="hidden" name="form_name" value="author_settings">

        <button type="submit" class="btn btn-pure btn-primary">Update information</button>
    </form>
</div>