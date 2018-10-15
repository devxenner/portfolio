<div class="form-social-wrap">
	<div class="form-group form-group-social-name">
		<label for="social_name">Social name: </label>
		<input type="text" name="social_name[]" id="social_name" class="form-control" value="<?php echo $social_link->name ?? ''; ?>">
	</div>

	<div class="form-group form-group-social-url">
		<label for="social_url">Social url: </label>
		<input type="text" name="social_url[]" id="social_url" class="form-control" value="<?php echo $social_link->url ?? ''; ?>">
	</div>
</div>

