<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

/*
 * TOOL - Start
 */

$custom_tools=array();

foreach (scandir(abs_path) as $node) {
	if ((is_dir(abs_path.$node))&&($node!='.')&&($node!='..')&&file_exists(abs_path.$node.'/index.php')) {
		$custom_tools[$node]=$node;
	}
}
$package=package.'.'.release;
unset($custom_tools[$package]);

$package_available=array();
foreach(osW_Tool_Server::getInstance()->getPackageList() as $serverlist => $server_packages) {
	foreach(osW_Tool_Server::getInstance()->checkPackageList($server_packages) as $package_name => $package_data) {
		$package=$package_data['package'].'.'.$package_data['release'];
		$package_available[$package_data['package'].'-'.$package_data['release']]=$package_data['package'].'-'.$package_data['release'];
		if (isset($custom_tools[$package])) {
			unset($custom_tools[$package]);
		}
	}
}

$package_installed=array();
$_package_installed=glob(abs_path.'resources/json/package/*.json');
foreach ($_package_installed as $key => $value) {
	$package_installed[substr(str_replace(abs_path.'resources/json/package/', '', $value), 0, -5)]=substr(str_replace(abs_path.'resources/json/package/', '', $value), 0, -5);
}
$_package_installed=glob(abs_path.'resources/json/filelist/*.json');
foreach ($_package_installed as $key => $value) {
	$package_installed[substr(str_replace(abs_path.'resources/json/filelist/', '', $value), 0, -5)]=substr(str_replace(abs_path.'resources/json/filelist/', '', $value), 0, -5);
}

foreach ($package_installed as $value) {
	if (isset($package_available[$value])) {
		unset($package_installed[$value]);
	}
}

function outputOption($i, $package_data, $sl) {
	$output='';
	if($package_data['options']['install']==true) {
		$output.='<a title="Install" href="javascript:manager(\''.$i.'\', \''.osW_Tool_Session::getInstance()->getId().'\', \'install\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="install buttonosw"><i class="fa fa-plus fa-fw"></i></a>';
	} else {
		$output.='<a title="Install" href="javascript:manager(\''.$i.'\', \''.osW_Tool_Session::getInstance()->getId().'\', \'install\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="install buttonosw disabled"><i class="fa fa-plus fa-fw"></i></a>';
	}
	$output.=' ';
	if($package_data['options']['update']==true) {
		$output.='<a title="Update" href="javascript:manager(\''.$i.'\', \''.osW_Tool_Session::getInstance()->getId().'\', \'update\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="update buttonosw"><i class="fa fa-refresh fa-fw"></i></a>';
	} else {
		$output.='<a title="Update" href="javascript:manager(\''.$i.'\', \''.osW_Tool_Session::getInstance()->getId().'\', \'update\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="update buttonosw disabled"><i class="fa fa-refresh fa-fw"></i></a>';
	}
	$output.=' ';
	if($package_data['options']['remove']==true) {
		$output.='<a title="Remove" href="javascript:manager(\''.$i.'\', \''.osW_Tool_Session::getInstance()->getId().'\', \'remove\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="remove buttonosw"><i class="fa fa-remove fa-fw"></i></a>';
	} else {
		$output.='<a title="Remove" href="javascript:manager(\''.$i.'\', \''.osW_Tool_Session::getInstance()->getId().'\', \'remove\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="remove buttonosw disabled"><i class="fa fa-remove fa-fw"></i></a>';
	}
	return $output;
}

$packages=osW_Tool_Server::getInstance()->getPackageList();

if (count($packages)==1) {
	$sl=osW_Tool::getInstance()->getInstance()->_catch('sl', 'oswframe2k20', 'pg');
	if ( (!isset($packages[$sl])) && ($custom_tools==array()&&($sl!='custom')) ) {
		$sl='oswframe2k20';
	}
} else {
	$sl=osW_Tool::getInstance()->getInstance()->_catch('sl', '', 'pg');
	if ( (!isset($packages[$sl])) && ($custom_tools==array()&&($sl!='custom')) ) {
		$sl='';
	}
}

$doaction=osW_Tool::getInstance()->getInstance()->_catch('doaction', '', 'pg');
$manager_package=osW_Tool::getInstance()->getInstance()->_catch('manager_package', '', 'pg');
$manager_release=osW_Tool::getInstance()->getInstance()->_catch('manager_release', '', 'pg');

if (($doaction=='install')||($doaction=='update')) {
	osW_Tool_Server::getInstance()->readServerList($sl);
	osW_Tool_Server::getInstance()->updatePackageList($sl);
	ob_clean();
	osW_Tool::getInstance()->installPackage($manager_package, $manager_release, $sl);

	$data=osW_Tool_Server::getInstance()->checkPackageList($packages[$sl]);
	$data_proceed=osW_Tool::getInstance()->getPackagesProceed();

	$return=array();
	foreach ($data_proceed as $block) {
		if (isset($data[$block['package'].'-'.$block['release']])) {
			$return[md5($block['serverlist'].'#'.$block['package'].'#'.$block['release'])]=$data[$block['package'].'-'.$block['release']];
		}
	}
	die(json_encode($return));
}

if ($doaction=='remove') {
	osW_Tool::getInstance()->removePackage($manager_package, $manager_release);
	ob_clean();
	$data=osW_Tool_Server::getInstance()->checkPackageList($packages[$sl]);
	die(json_encode(array(md5($sl.'#'.$manager_package.'#'.$manager_release)=>$data[$manager_package.'-'.$manager_release])));
}


if ($doaction=='removecustom') {
	if (file_exists(abs_path.'resources/json/package/'.$manager_package.'.json')) {
		unlink(abs_path.'resources/json/package/'.$manager_package.'.json');
	}
	if (file_exists(abs_path.'resources/json/filelist/'.$manager_package.'.json')) {
		unlink(abs_path.'resources/json/filelist/'.$manager_package.'.json');
	}
	ob_clean();
	die(json_encode(array(md5($sl.'#'.$manager_package)=>'removed')));
}

?>

<?php if ($sl==''):?>
<script>var unsorted=5;</script>
<?php elseif($sl=='custom'):?>
<script>var unsorted=1;</script>
<?php else:?>
<script>var unsorted=4;</script>
<?php endif?>

<div class="container">

	<ul class="nav nav-tabs">
	<?php if(count($packages)>1):?>
		<li<?php if($sl==''):?> class="active"<?php endif?>><a href="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=<?php echo osW_Tool::getInstance()->getAction()?>&sl=">*</a></li>
	<?php endif?>
	<?php foreach($packages as $serverlist => $server_packages):?>
		<?php $serverlist_data=osW_Tool_Server::getInstance()->readServerList($serverlist);?>
		<li<?php if($sl==$serverlist):?> class="active"<?php endif?>><a href="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=<?php echo osW_Tool::getInstance()->getAction()?>&sl=<?php echo $serverlist?>"><?php echo $serverlist_data['info']['name']?></a></li>
	<?php endforeach?>
	<?php if ($package_installed!=array()):?>
		<li<?php if($sl=='custom'):?> class="active"<?php endif?>><a href="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=<?php echo osW_Tool::getInstance()->getAction()?>&sl=custom">Custom</a></li>
	<?php endif?>
	</ul>

	<br>

	<table id="oswtools_manager" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Package</th>
				<?php if($sl!='custom'):?>
				<th>Release</th>
				<th>Version (installed)</th>
				<th>Version (available)</th>
				<?php if ($sl==''):?>
				<th>Serverlist</th>
				<?php endif?>
				<?php endif?>
				<th class="text-center">Options</th>
			</tr>
		</thead>
		<tbody>
<?php if(($sl=='')&&(count($packages)>1)):?>
<?php foreach ($packages as $sl => $foo):?>
<?php $serverlist_data=osW_Tool_Server::getInstance()->readServerList($sl);?>
<?php $i=0;foreach(osW_Tool_PackageManager::getInstance()->checkPackageList($packages[$sl]) as $package_name => $package_data):$i++;?>
		<tr id="package_<?php echo md5($sl.'#'.$package_data['package'].'#'.$package_data['release'])?>">
			<td><?php echo $package_data['info']['name'];?></td>
			<td><?php echo $package_data['release'];?></td>
			<td class="manager_release"><?php if($package_data['version_installed']=='0.0'):?>-----<?php else:?><?php echo $package_data['version_installed'];?><?php endif?></td>
			<td><?php echo $package_data['version'];?></td>
			<td><?php echo $serverlist_data['info']['name']?></td>
			<td class="manager_options text-center"><?php echo outputOption(md5($sl.'#'.$package_data['package'].'#'.$package_data['release']), $package_data, $sl)?></td>
		</tr>
<?php endforeach?>
<?php endforeach?>
<?php elseif($sl=='custom'):?>
<?php $i=0;foreach($package_installed as $package_name => $package_data):$i++;?>
		<tr id="package_<?php echo md5($sl.'#'.$package_name)?>">
			<td class="manager_link"><?php echo $package_name;?></td>
			<td class="manager_options text-center"><a title="Remove" href="javascript:removeCustom('<?php echo md5($sl.'#'.$package_name)?>', '<?php echo osW_Tool_Session::getInstance()->getId()?>', '<?php echo $package_name?>')" class="remove buttonosw"><i class="fa fa-remove fa-fw"></i></a></td>
		</tr>
<?php endforeach?>
<?php elseif(count(osW_Tool_PackageManager::getInstance()->checkPackageList($packages[$sl]))>0):?>
<?php $i=0;foreach(osW_Tool_PackageManager::getInstance()->checkPackageList($packages[$sl]) as $package_name => $package_data):$i++;?>
		<tr id="package_<?php echo md5($sl.'#'.$package_data['package'].'#'.$package_data['release'])?>">
			<td><?php echo $package_data['info']['name'];?></td>
			<td><?php echo $package_data['release'];?></td>
			<td class="manager_release"><?php if($package_data['version_installed']=='0.0'):?>-----<?php else:?><?php echo $package_data['version_installed'];?><?php endif?></td>
			<td><?php echo $package_data['version'];?></td>
			<td class="manager_options text-center"><?php echo outputOption(md5($sl.'#'.$package_data['package'].'#'.$package_data['release']), $package_data, $sl)?></td>
		</tr>
<?php endforeach?>
<?php endif?>
		</tbody>
	</table>

<?php if($sl!='custom'):?>
	<hr/>

	<div class="form-group ">
		<button type="button" class="btn btn-primary btn-block" onclick="updateAll()" form="oswtools_manager">Update all packages</button>
	</div>
<?php endif?>

</div>