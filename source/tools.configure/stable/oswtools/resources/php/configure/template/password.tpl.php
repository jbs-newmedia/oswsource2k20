	<div class="form-group<?php if(isset($error['chmod_directory'])):?> has-error<?php endif?>">
		<label for="chmod_directory" class="control-label"><?php echo outputString($config_data['default_name'])?><?php if($config_data['valid_min_length']>0):?><span style="float:right;">*</span><?php endif;?></label>
		<div>
			<input class="form-control" name="conf_<?php echo $config_element?>" type="password" value="<?php echo $config_data['default_value']?>" />
			<?php if(isset($error['chmod_directory'])):?><span class="help-block"><?php echo $error['chmod_directory']?></span><?php endif?>
		</div>
	</div>