<?php declare(strict_types=0);

/**
 * @var string $config_element
 * @var array $config_data
 * @var \osWFrame\Tools\Tool\Configure $this
 */

$this->values_post[$config_element]['value'] = (int) ($this->values_post[$config_element]['value']);

if (($config_data['default_type'] === 'password') && ($this->values_post[$config_element]['value'] === '') && ((isset($this->data['values_json'][$config_element])) && ($this->data['values_json'][$config_element] !== ''))) {
} elseif ((isset($config_data['valid_min_length'])) && (strlen($this->values_post[$config_element]['value']) < $config_data['valid_min_length'])) {
    $this->getForm()->addErrorMessage('conf_' . $config_element, $config_data['default_name'] . ' is too short');
} elseif ((isset($config_data['valid_max_length'])) && (strlen($this->values_post[$config_element]['value']) > $config_data['valid_max_length'])) {
    $this->getForm()->addErrorMessage('conf_' . $config_element, $config_data['default_name'] . ' is too long');
} elseif ((isset($config_data['valid_min_value'])) && ($this->values_post[$config_element]['value'] < $config_data['valid_min_value'])) {
    $this->getForm()->addErrorMessage('conf_' . $config_element, $config_data['default_name'] . ' is too small');
} elseif ((isset($config_data['valid_max_value'])) && ($this->values_post[$config_element]['value'] > $config_data['valid_max_value'])) {
    $this->getForm()->addErrorMessage('conf_' . $config_element, $config_data['default_name'] . ' is too big');
}
