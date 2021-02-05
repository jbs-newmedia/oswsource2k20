<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$default_options['enabled']=true;
$default_options['options']['required']=false;
$default_options['options']['order']=false;
$default_options['options']['default_value']='';
$default_options['options']['file_dir']='data';
$default_options['options']['file_dir_tmp']='.tmp';
$default_options['options']['file_name']='original';
$default_options['options']['temp_suffix']='___osw_tmp';
$default_options['options']['delete_suffix']='___osw_delete';
$default_options['options']['text_file_select']=$this->getGroupMessage('text_image_select');
$default_options['options']['text_file_view']=$this->getGroupMessage('text_image_view');
$default_options['options']['text_file_delete']=$this->getGroupMessage('text_image_delete');
$default_options['options']['text_file_show']=$this->getGroupMessage('text_image_show');
$default_options['options']['text_file_edit']=$this->getGroupMessage('text_image_edit');
$default_options['options']['text_blank']=$this->getGroupMessage('text_blank');
$default_options['options']['store_name']=false;
$default_options['options']['store_type']=false;
$default_options['options']['store_size']=false;
$default_options['options']['store_md5']=false;
$default_options['options']['store_sha1']=false;
$default_options['options']['edit_enabled']=false;
$default_options['options']['edit_store_org']=true;
$default_options['options']['edit_del_files']=[];
$default_options['options']['edit_clear_dirs']=[];
$default_options['options']['edit_crop_x']=0;
$default_options['options']['edit_crop_y']=0;
$default_options['validation']['module']='fileimage';
$default_options['_search']['enabled']=false;

?>