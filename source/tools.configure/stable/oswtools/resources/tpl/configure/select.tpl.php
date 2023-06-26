<div class="form-group mb-2">
	<label class="font-weight-bold" for="conf_<?php echo $config_element ?>"><?php echo \osWFrame\Core\HTML::outputString($config_data['default_name']) ?><?php if ($config_data['valid_min_length']>0): ?>*<?php endif ?>:</label>
	<?php echo $osW_Form->drawSelectField('conf_'.$config_element, $config_data['default_select'], $config_data['default_value'], ['input_class'=>'selectpicker form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="custom-select" data-size="10"']) ?>
	<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('conf_'.$config_element) ?></div>
</div>