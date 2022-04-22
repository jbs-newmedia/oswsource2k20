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
$default_options['options']['text_char']=$this->getGroupMessage('text_char');
$default_options['options']['text_chars']=$this->getGroupMessage('text_chars');
$default_options['options']['read_only']=false;
$default_options['options']['show_output']=false;
$default_options['options']['show_dialog']=true;
$default_options['validation']['module']='string';

?>