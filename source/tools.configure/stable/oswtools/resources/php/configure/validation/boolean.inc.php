<?php declare(strict_types=0);

/**
 * @var string $config_element
 * @var array $config_data
 * @var \osWFrame\Tools\Tool\Configure $this
 */

if (strlen($this->values_post[$config_element]['value']) < $config_data['valid_min_length']) {
    $this->getForm()->addErrorMessage($config_element, $config_data['default_name'] . ' is too short');
} elseif (strlen($this->values_post[$config_element]['value']) > $config_data['valid_max_length']) {
    $this->getForm()->addErrorMessage($config_element, $config_data['default_name'] . ' is too long');
}
