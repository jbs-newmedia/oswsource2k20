<?php declare(strict_types=0);

/**
 * @var string $config_element
 * @var array $config_data
 * @var osWFrame\Core\Form $osW_Form
 */

?>

<div class="form-group mb-2">
	<label class="font-weight-bold" for="conf_<?php echo $config_element ?>"><?php echo \osWFrame\Core\HTML::outputString($config_data['default_name']) ?><?php if ($config_data['valid_min_length'] > 0): ?>*<?php endif ?>:</label>
	<?php echo $osW_Form->drawPasswordField('conf_' . $config_element, $config_data['default_value'], ['input_class' => 'form-control', 'input_errorclass' => 'is-invalid']) ?>
	<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('conf_' . $config_element) ?></div>
</div>
