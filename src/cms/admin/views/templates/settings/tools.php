<div class="col-12 col-md-8 roof-sm floor-sm">

    <!-- RESTORE DEFAULTS -->
    <form method="post">
        <div class="form-field row">
            <label>Restore defaults</label>
            <p class="color-danger font-bolder">
                Use this tool to restore Serve's database to its original state. 
                Warning, this will delete all data associated with your Serve installation 
                including all your posts, tags, categories, accounts, passwords and
                immediately log you out of the admin panel. Use with caution.
            </p>
        </div>
        <input type="hidden" name="form_name" value="restore_serve">
        <input type="hidden" name="access_token" value="<?php echo $ACCESS_TOKEN; ?>">
        <button type="submit" class="btn btn-danger js-restore-serve-trigger">Restore Serve</button>
    </form>

</div>