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
 */


$default_options['enabled'] = true;
$default_options['options']['required'] = false;
$default_options['options']['order'] = false;
$default_options['options']['default_value'] = '';
$default_options['options']['file_dir'] = 'data';
$default_options['options']['file_dir_tmp'] = '.tmp';
$default_options['options']['file_name'] = 'original';
$default_options['options']['temp_suffix'] = '___osw_tmp';
$default_options['options']['delete_suffix'] = '___osw_delete';
$default_options['options']['text_file_select'] = $this->getGroupMessage('text_file_select');
$default_options['options']['text_file_view'] = $this->getGroupMessage('text_file_view');
$default_options['options']['text_file_delete'] = $this->getGroupMessage('text_file_delete');
$default_options['options']['text_blank'] = $this->getGroupMessage('text_blank');
$default_options['options']['store_name'] = false;
$default_options['options']['store_type'] = false;
$default_options['options']['store_size'] = false;
$default_options['options']['store_md5'] = false;
$default_options['options']['store_sha1'] = false;
$default_options['validation']['module'] = 'file';
$default_options['_search']['enabled'] = false;
