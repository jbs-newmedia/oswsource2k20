<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var array $default_options
 * @var \osWFrame\Core\DDM4 $this
 *
 */

$default_options['enabled'] = true;
$default_options['options']['required'] = false;
$default_options['options']['order'] = false;
$default_options['options']['default_value'] = '';
$default_options['options']['text_hidden'] = $this->getGroupMessage('text_hidden');
$default_options['validation']['module'] = 'crypt';
$default_options['validation']['filter'] = 'password_double';
$default_options['_search']['enabled'] = false;
$default_options['_add']['options']['required'] = true;
