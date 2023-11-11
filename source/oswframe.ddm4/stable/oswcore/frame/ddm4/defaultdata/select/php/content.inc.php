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
$default_options['options']['check_required'] = true;
$default_options['options']['order'] = false;
$default_options['options']['search'] = false;
$default_options['options']['default_value'] = '';
$default_options['options']['blank_value'] = true;
$default_options['options']['data'] = [];
$default_options['options']['text_all'] = $this->getGroupMessage('text_all');
$default_options['options']['text_choose'] = $this->getGroupMessage('data_choose');
$default_options['options']['read_only'] = false;
$default_options['options']['data_size'] = 6;
$default_options['options']['live_search'] = true;
$default_options['_search']['options']['blank_value'] = false;
$default_options['_search']['options']['default_value'] = '%';
$default_options['validation']['module'] = 'string';
