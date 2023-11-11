<?php declare(strict_types=0);

/**
 * @var string $config_element
 * @var array $config_data
 * @var \osWFrame\Tools\Tool\Configure $this
 */

if (($config_data['default_type'] === 'password') && ($this->values_post[$config_element]['value'] === '') && ((isset($this->data['values_json'][$config_element])) && ($this->data['values_json'][$config_element] !== ''))) {
} elseif (strlen($this->values_post[$config_element]['value']) < $config_data['valid_min_length']) {
    $this->getForm()->addErrorMessage('conf_' . $config_element, $config_data['default_name'] . ' is too short');
} elseif (strlen($this->values_post[$config_element]['value']) > $config_data['valid_max_length']) {
    $this->getForm()->addErrorMessage('conf_' . $config_element, $config_data['default_name'] . ' is too long');
}
