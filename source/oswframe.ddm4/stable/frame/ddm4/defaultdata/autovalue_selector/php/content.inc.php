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
$default_options['options']['default_value']=1;
$default_options['options']['selector_use']=true;
$default_options['validation']['module']='integer';
$default_options['validation']['filter']['unique_selector']=[];
$default_options['_search']['options']['default_value']='';
$default_options['_edit']['enabled']=false;
$default_options['_delete']['enabled']=false;
$default_options['_send']['enabled']=false;

?>