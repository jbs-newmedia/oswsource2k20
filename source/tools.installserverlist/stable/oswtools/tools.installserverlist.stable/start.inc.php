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


$config=array();
$config['error']='';
$config['message']='';

if (osW_Tool::getInstance()->getDoAction()=='doit') {
	$url=osW_Tool::getInstance()->_catch('conf_url', '', 'pg');
	$file=abs_path.'resources/caches/tool_serverlist.zip';
	if (defined('root_path')) {
		$dir=root_path;
	} else {
		$dir=abs_path;
	}

	$package_data=osW_Tool_Server::getInstance()->getUrlData($url.'/index.php?action=get_serverlist');
	file_put_contents($file, $package_data);
	if (osW_Tool_Zip::getInstance()->unpackDir($file, $dir)==true) {
		$config['message']='Serverlist "'.htmlspecialchars($url).'" installed successfully';
	} else {
		$config['error']='Serverlist "'.htmlspecialchars($url).'" could not be installed';
	}
	osW_Tool::getInstance()->delFile($file);
}

?>

<div class="container">

	<form name="serverlist" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=start">

	<p>Please enter the requested url from your serverlist and confirm your input by pressing the button "Install serverlist". The current list of available servers is available at <a href="https://oswframe.com/serverlist" target="_blank">oswframe.com/serverlist</a>.</p>

	<hr/>

<?php if(!empty($config['error'])):?>
<?php osW_Tool_Template::getInstance()->outputB3Alerts(array('danger'=>array($config['error'])))?>
<?php endif?>

<?php if(!empty($config['message'])):?>
<?php osW_Tool_Template::getInstance()->outputB3Alerts(array('success'=>array($config['message'])))?>
<?php endif?>

	<div class="form-group<?php if(($config['error']!='')):?> has-error<?php endif?>">
		<label for="chmod_directory" class="control-label">Serverlist<span style="float:right;">*</span></label>
		<div>
			<input class="form-control" name="conf_url" type="text" value="" />
			<?php if(($config['error']!='')):?><span class="help-block"><?php echo $config['error']?></span><?php endif?>
		</div>
	</div>

	<hr/>

	<div class="form-group ">
		<input type="submit" class="btn btn-primary btn-block" name="install" value="Install serverlist" />
	</div>

	<input type="hidden" name="doaction" value="doit"/>
	</form>

</div>