<?php declare(strict_types=0);

/**
 * @var string $config_element
 * @var array $config_data
 * @var osWFrame\Core\Form $osW_Form
 */

echo $osW_Form->drawHiddenField('conf_' . $config_element, $config_data['default_value']);
