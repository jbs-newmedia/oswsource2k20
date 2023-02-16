<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

$default_options['enabled']=true;
$default_options['options']['required']=false;
$default_options['options']['order']=false;
$default_options['options']['default_value']='';
$default_options['options']['year_min']='1900';
$default_options['options']['year_max']=(date('Y')+1);
$default_options['options']['date_format']='%d.%m.%Y';
$default_options['options']['month_asname']=false;
$default_options['options']['year_sortorder']='desc';
$default_options['options']['read_only']=false;
$default_options['validation']['module']='date';

?>