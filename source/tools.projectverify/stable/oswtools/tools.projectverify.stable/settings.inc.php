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
 * TOOL - Settings
*/

if (!isset($settings['ignore'])) {
	$settings['ignore']=array();
}
if (!isset($settings['ignore']['dirs'])) {
	$settings['ignore']['dirs']=array();
}
if (!isset($settings['ignore']['files'])) {
	$settings['ignore']['files']=array();
}

if (osW_Tool::getInstance()->getDoAction()=='doignore') {
	if (isset($settings['ignore'])) {
		$clear_list=osW_Tool_ProjectVerify::getInstance()->getList($settings['ignore']);
	} else {
		$clear_list=osW_Tool_ProjectVerify::getInstance()->getList(array());
	}

	$element=osW_Tool::getInstance()->_catch('element', '', 'pg');
	ob_clean();
	if (isset($clear_list[$element])) {
		if (is_dir(root_path.$element)) {
			$settings['ignore']['dirs'][]='/'.$element;
			$messages['success'][]='"'.$element.'" were ignored in the future.';
		} elseif (is_file(root_path.$element)) {
			$settings['ignore']['files'][]='/'.$element;
			$messages['success'][]='"'.$element.'" were ignored in the future.';
		}
		$path=root_path.'oswtools/resources/settings/';
		if (!is_dir($path)) {
			mkdir($path);
		}
		@chmod($path, osW_Tool::getInstance()->chmodDir());
		$file=$path.serverlist.'-'.package.'-'.release.'.json';
		file_put_contents($file, json_encode($settings));
		osW_Tool_Session::getInstance()->set('messages', $messages);
		chmod($file, osW_Tool::getInstance()->chmodFile());
		die(json_encode(array('status'=>true)));
	} else {
		die(json_encode(array('status'=>false)));
	}
}

$error=array();
$messages=array();

if (osW_Tool::getInstance()->getDoAction()=='doit') {
	$projectclear_files=osW_Tool::getInstance()->_catch('projectclear_files', '', 'pg');
	$projectclear_dirs=osW_Tool::getInstance()->_catch('projectclear_dirs', '', 'pg');

	$projectclear_files=explode("\n", $projectclear_files);
	$projectclear_dirs=explode("\n", $projectclear_dirs);

	foreach ($projectclear_files as $key => $value) {
		$projectclear_files[$key]=trim($value);
	}

	foreach ($projectclear_dirs as $key => $value) {
		$projectclear_dirs[$key]=trim($value);
	}

	$settings['ignore']['files']=$projectclear_files;
	$settings['ignore']['dirs']=$projectclear_dirs;

	$path=root_path.'oswtools/resources/settings/';
	if (!is_dir($path)) {
		mkdir($path);
	}
	@chmod($path, osW_Tool::getInstance()->chmodDir());

	$file=$path.serverlist.'-'.package.'-'.release.'.json';
	file_put_contents($file, json_encode($settings));
	osW_Tool::getInstance()->chmodFile($file);

	$messages['success'][]='Settings were saved successfully.';

	osW_Tool_Session::getInstance()->set('messages', $messages);
	osW_Tool::getInstance()->_direct('index.php?session='.osW_Tool_Session::getInstance()->getId().'&action=settings');
}

?>

<div class="container">

	<form id="projectclear_settings" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=settings">

	<?php osW_Tool_Template::getInstance()->outputB3Alerts()?>

	<div class="form-group">
		<label for="projectclear_dirs" class="control-label">Ignored directories</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-sitemap fa-lg" aria-hidden="true"></i></span>
				<textarea name="projectclear_dirs" rows="10" class="form-control"><?php echo implode("\n", $settings['ignore']['dirs'])?></textarea>
			</div>
		</div>
	</div>

	<hr/>

	<div class="form-group">
		<label for="projectclear_files" class="control-label">Ignored files</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-file fa-lg" aria-hidden="true"></i></span>
				<textarea name="projectclear_files" rows="10" class="form-control"><?php echo implode("\n", $settings['ignore']['files'])?></textarea>
			</div>
		</div>
	</div>

	<hr/>

	<div class="form-group ">
		<button type="button" class="btn btn-primary btn-block" onclick="$('#projectclear_settings').submit()" form="projectclear_settings">Save settings</button>
	</div>

	<input type="hidden" name="doaction" value="doit"/>
	</form>

</div>
