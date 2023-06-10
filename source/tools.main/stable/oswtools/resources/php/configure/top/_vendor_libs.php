<?php

$this->settings=['page_title'=>'Vendor Libs'];

	$vendor_settings=[];

	foreach (glob(\osWFrame\Core\Settings::getStringVar('settings_framepath').'vendor'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'*') as $vendor) {
		$vendor_name=strtolower(str_replace(['.'], ['_'], basename($vendor)));
		$vendor_settings[$vendor_name]=[];
		foreach (glob($vendor.DIRECTORY_SEPARATOR.'*') as $vendor_version) {
			$vendor_version=basename($vendor_version);
			if ($vendor_version!='plugins') {
				$vendor_settings[$vendor_name][$vendor_version]=$vendor_version;
			} else {
				foreach (glob($vendor.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'*') as $plugin) {
					$plugin_name=strtolower(str_replace(['.'], ['_'], basename($plugin)));
					$vendor_settings[$vendor_name.'_'.$plugin_name]=[];
					foreach (glob($plugin.DIRECTORY_SEPARATOR.'*') as $plugin_version) {
						if (\osWFrame\Core\Filesystem::isDir($plugin_version)!==true) {
							continue;
						}
						$plugin_version=basename($plugin_version);
						$vendor_settings[$vendor_name.'_'.$plugin_name][$plugin_version]=$plugin_version;
					}
					if ($vendor_settings[$vendor_name.'_'.$plugin_name]==[]) {
						unset($vendor_settings[$vendor_name.'_'.$plugin_name]);
					} else {
						krsort($vendor_settings[$vendor_name.'_'.$plugin_name]);
					}
				}
			}
		}
		if ($vendor_settings[$vendor_name]==[]) {
			unset($vendor_settings[$vendor_name]);
		} else {
			krsort($vendor_settings[$vendor_name]);
		}
	}

	foreach ($vendor_settings as $v=>$vv) {
		$this->fields['vendor_lib_'.$v.'_version']=['default_name'=>$v, 'default_type'=>'select', 'default_select'=>array_merge(['0'=>'newest'], $vv), 'default_value'=>'', 'valid_type'=>'vendor', 'valid_min_length'=>1, 'valid_max_length'=>32, 'configure_write'=>true];
		$this->fields['vendor_lib_'.$v.'_versions']=['default_name'=>$v.'_versions', 'default_type'=>'hidden', 'default_value'=>implode(';', $vv),  'valid_type'=>'string', 'valid_min_length'=>1, 'valid_max_length'=>1024, 'configure_write'=>true];
	}

?>