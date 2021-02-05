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

$tools=array();
foreach (scandir(abs_path) as $node) {
	if ((is_dir(abs_path.$node))&&($node!='.')&&($node!='..')&&file_exists(abs_path.$node.'/index.php')) {
		$tools[$node]=$node;
	}
}

$package=package.'.'.release;
unset($tools[$package]);

$manager_tools=array();

foreach(osW_Tool_Server::getInstance()->getPackageList() as $serverlist => $server_packages) {
	foreach(osW_Tool_Main::getInstance()->checkPackageList($server_packages) as $package_name => $package_data) {
		$package=$package_data['package'].'.'.$package_data['release'];
		if (isset($tools[$package])) {
			if (isset($package_data['info']['name'])) {
				$manager_tools[$serverlist][$package]=$package_data;
				unset($tools[$package]);
			}
		}
	}
}

if ($tools!=array()) {
	foreach ($tools as $package) {
		$file=abs_path.$package.'/info.json';
		if (file_exists($file)) {
			$manager_tools['custom'][$package]=json_decode(file_get_contents($file), true);
		} else {
			$manager_tools['custom'][$package]=$package;
		}
		unset($tools[$package]);
	}
}

?>
<div class="container">
	<table id="oswtools_main" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Tool</th>
				<th>Release</th>
				<th>Information</th>
			</tr>
		</thead>
		<tbody>
<?php if($manager_tools!=array()):?>
<?php foreach ($manager_tools as $serverlist => $tools):?>
<?php $i=1;foreach($tools as $package_name => $package_data):$i++;?>
			<tr>
				<td><a href="../<?php echo $package_name?>/index.php?&session=<?php echo osW_Tool_Session::getInstance()->getId()?>"><?php if(isset($package_data['info']['name'])):?><?php echo $package_data['info']['name'];?><?php else:?><?php echo $package_name;?><?php endif?></a></td>
				<td><?php if($serverlist!='custom'):?><?php echo $package_data['release']?><?php else:?>custom<?php endif?></td>
				<td>-----</td>
			</tr>
<?php endforeach?>
<?php endforeach?>
<?php endif?>
		</tbody>
	</table>
</div>