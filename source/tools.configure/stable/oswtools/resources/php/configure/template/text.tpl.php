	<div class="form-group<?php if(isset($config['error_elements'][$config_element])):?> has-error<?php endif?>">
		<label for="chmod_directory" class="control-label"><?php echo outputString($config_data['default_name'])?><?php if($config_data['valid_min_length']>0):?><span style="float:right;">*</span><?php endif;?></label>
		<div>
			<input class="form-control" name="conf_<?php echo $config_element?>" type="text" value="<?php echo $config_data['default_value']?>" />
			<?php if(isset($config['error_elements'][$config_element])):?><span class="help-block"><?php echo $config['error_elements'][$config_element]?></span><?php endif?>
		</div>
	</div>