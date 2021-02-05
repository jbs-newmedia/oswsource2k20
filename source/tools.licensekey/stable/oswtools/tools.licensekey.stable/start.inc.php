<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link http://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

/*
 * TOOL - Start
 */


$data=osW_Tool_Server::getInstance()->getLicenseInfo(serverlist);
$sl=osW_Tool::getInstance()->getInstance()->_catch('sl', 'oswframe', 'pg');
if (!isset($data[$sl])) {
	$sl='oswframe';
}

$count=count($data);

?>

<div class="container">

	<ul class="nav nav-tabs">
	<?php foreach($data as $serverlist => $serverlist_packages):?>
		<li<?php if($sl==$serverlist):?> class="active"<?php endif?>><a href="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=<?php echo osW_Tool::getInstance()->getAction()?>&sl=<?php echo $serverlist?>"><?php echo $serverlist_packages['server_list']?></a></li>
	<?php endforeach?>
	</ul>

	<br>

	<table id="oswtools_licensekey" class="table table-bordered">
		<tbody>
			<tr>
				<th class="col-sm-2">Servername</th>
				<td class="col-sm-10"><?php echo $data[$sl]['server_name']?></td>
			</tr>
			<tr>
				<th class="col-sm-2">ServerAddress</th>
				<td class="col-sm-10"><?php echo $data[$sl]['server_addr']?></td>
			</tr>
			<tr>
				<th class="col-sm-2">ServerMac</th>
				<td class="col-sm-10"><?php echo $data[$sl]['server_mac']?></td>
			</tr>
			<tr>
				<th class="col-sm-2">LicenseKey</th>
				<td class="col-sm-10"><?php echo $data[$sl]['licensekey']?></td>
			</tr>
			<tr>
				<th class="col-sm-2">LicenseKeyDev</th>
				<td class="col-sm-10"><?php echo $data[$sl]['licensekeydev']?></td>
			</tr>
		</tbody>
	</table>

</div>