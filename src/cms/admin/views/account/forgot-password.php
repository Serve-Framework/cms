<!-- PAGE CONTAINER -->
<section class="container-fluid">
	
	<!-- FORM CARD -->
	<div class="card accnt-form-card">

		<div class="pad-40">

			<!-- LOGO -->
			<div class="roof-xs floor-sm text-center">
				<img class="logo" src="<?php echo admin_assets_url() ?>/img/logo.png">
				<h1 class="roof-sm">Forgot Password</h1>
			</div>

			<!-- FORM -->
			<form class="js-validation-form <?php if (isset($POST_RESPONSE) && isset($POST_RESPONSE['class'])) echo $POST_RESPONSE['class']; ?>" method="post">
				<p class="color-gray tex">
					Enter your Serve username and we'll send you an email with instructions on resetting your password.
				</p>

				<!-- INPUTS -->
			    <div class="form-field row on-primary">
			        <input type="text" name="username" id="username" data-js-required="true" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">
			        <label for="username">Username</label>
			        <p class="help-danger">* Please enter your username.</p>
			    </div>
			    
			    <!-- ACCESS TOKEN -->
			    <input type="hidden" name="access_token" value="<?php echo $ACCESS_TOKEN; ?>">
			    
			    <!-- SUBMIT -->
			    <button type="submit" class="btn btn-primary btn-xl raised btn-block with-spinner">
			        <svg viewBox="0 0 64 64" class="loading-spinner"><circle class="path" cx="32" cy="32" r="30" fill="none" stroke-width="4"></circle></svg>
			        Request Reset Link
			    </button>
			    
			    <!-- FORM RESULT -->
			    <?php if (isset($POST_RESPONSE) && !empty($POST_RESPONSE)) : ?>
			    <div class="form-result">
			        <div class="msg msg-<?php echo $POST_RESPONSE['class']; ?>" aria-hidden="true">
			            <div class="msg-icon">
			                <span class="glyph-icon glyph-icon-<?php echo $POST_RESPONSE['icon']; ?>"></span>
			            </div>
			            <div class="msg-body">
			                <p><?php echo $POST_RESPONSE['msg']; ?></p>
			            </div>
			        </div>
			    </div>
				<?php endif; ?>
				
			</form>
			
			<!-- OPTIONS -->
			<div class="text-center roof-xs">
				<a class="fancy-link p6 inline-block float-left" href="/admin/login/">Back to login</a>
				<a class="fancy-link p6 inline-block float-right" href="/admin/forgot-username/">Forgot your username?</a>
			</div>
		</div>
	</div>
	
</section>