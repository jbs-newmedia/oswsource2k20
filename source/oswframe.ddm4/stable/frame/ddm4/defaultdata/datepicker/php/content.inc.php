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
$default_options['options']['orientation']='auto';
$default_options['options']['weekStart']='1';
$default_options['options']['startDate']='01.01.1900';
$default_options['options']['endDate']='31.12.'.(date('Y')+1);
$default_options['options']['format']='%d.%m.%Y';
$default_options['options']['month_asname']=false;
$default_options['options']['read_only']=false;
$default_options['validation']['module']='datepicker';

?>