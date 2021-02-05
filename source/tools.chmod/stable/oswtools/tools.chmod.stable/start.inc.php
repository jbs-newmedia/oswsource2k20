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

$dirs = osW_Tool_CHmod::getInstance()->listdir(realpath('../../'));

asort($dirs);

array_unshift($dirs, 'select directory', '/');

$chmods_file=array(
	'0664'=>'0664',
	'0644'=>'0644',
	'0660'=>'0660',
	'0640'=>'0640',
);

$chmods_dir=array(
	'0775'=>'0775',
	'0755'=>'0755',
	'0770'=>'0770',
	'0750'=>'0750',
);

//look to you config
$chmod_file=osW_Tool::getInstance()->chmodFile();
$chmod_dir=osW_Tool::getInstance()->chmodDir();

switch ($chmod_file) {
	case 436:
		$chmod_file='0664';
		break;
	case 420:
		$chmod_file='0644';
		break;
	case 432:
		$chmod_file='0660';
		break;
	case 416:
		$chmod_file='0640';
		break;
	default:
		$chmod_file='0664';
		break;
}

switch ($chmod_dir) {
	case 509:
		$chmod_dir='0775';
		break;
	case 493:
		$chmod_dir='0755';
		break;
	case 504:
		$chmod_dir='0770';
		break;
	case 488:
		$chmod_dir='0750';
		break;
	default:
		$chmod_dir='0775';
		break;
}

$chmod_directory=osW_Tool::getInstance()->_catch('chmod_directory', '', 'pg');
$chmod_files=osW_Tool::getInstance()->_catch('chmod_files', $chmod_file, 'pg');
$chmod_dirs=osW_Tool::getInstance()->_catch('chmod_dirs', $chmod_dir, 'pg');

$error=array();
$success=array();
if (osW_Tool::getInstance()->getDoAction()=='doit') {
	if ($chmod_directory=='') {
		$error['chmod_directory']='Please select a directory';
	} elseif (!is_dir(realpath('../../').$chmod_directory)) {
		$error['chmod_directory']='Please select correct directory';
	}

	if ($chmod_files=='') {
		$error['chmod_files']='Please select the access permissions for files';
	} elseif (!isset($chmods_file[$chmod_files])) {
		$error['chmod_files']='Please select correct access permissions for files';
	}

	if ($chmod_dirs=='') {
		$error['chmod_dirs']='Please select the access permissions for directories';
	} elseif (!isset($chmods_dir[$chmod_dirs])) {
		$error['chmod_dirs']='Please select correct access permissions for directories';
	}

	if(count($error)==0) {
		exec('cd '.realpath('../../').$chmod_directory.'; find -type f -print0 | xargs -0 chmod '.intval($chmods_file[$chmod_files]).'; find -type d -print0 | xargs -0 chmod '.intval($chmods_dir[$chmod_dirs]).';');
		$messages['success'][]='Access permissions are changed successfully.';
		osW_Tool_Session::getInstance()->set('messages', $messages);
		osW_Tool::getInstance()->_direct('index.php?session='.osW_Tool_Session::getInstance()->getId().'&action=start');
	}
}

#		$messages['success'][]='User was created successfully in the htaccess.';
#		osW_Tool_Session::getInstance()->set('messages', $messages);
#		osW_Tool::getInstance()->_direct('index.php?session='.osW_Tool_Session::getInstance()->getId().'&action=protecttools&part=manage');


?>

<div class="container">

	<p>Please enter the requested data and confirm your input by pressing the button "create user in .htaccess".</p>

	<hr/>

	<?php osW_Tool_Template::getInstance()->outputB3Alerts()?>

	<form id="chmod_start" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=start">

	<div class="form-group<?php if(isset($error['chmod_directory'])):?> has-error<?php endif?>">
		<label for="chmod_directory" class="control-label">Directory</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-sitemap fa-lg" aria-hidden="true"></i></span>
				<select title="select directory" name="chmod_directory" class="form-control selectpicker">
				<?php foreach($dirs as $dir):?>
					<option value="<?php echo $dir?>"<?php if($chmod_directory==$dir):?> selected="selected"<?php endif?>><?php echo $dir?></option>
				<?php endforeach?>
				</select>
			</div>
			<?php if(isset($error['chmod_directory'])):?><span class="help-block"><?php echo $error['chmod_directory']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['chmod_files'])):?> has-error<?php endif?>">
		<label for="chmod_files" class="control-label">Files (mode)</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-file fa-lg" aria-hidden="true"></i></span>
				<select title="select chmod for files" name="chmod_files" class="form-control selectpicker">
				<?php foreach($chmods_file as $chmod):?>
					<option value="<?php echo $chmod?>"<?php if($chmod_files==$chmod):?> selected="selected"<?php endif?>><?php echo $chmod?></option>
				<?php endforeach?>
				</select>
			</div>
			<?php if(isset($error['chmod_files'])):?><span class="help-block"><?php echo $error['chmod_files']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['chmod_dirs'])):?> has-error<?php endif?>">
		<label for="chmod_dirs" class="control-label">Directories (mode)</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-folder fa-lg" aria-hidden="true"></i></span>
				<select title="select chmod for directories" name="chmod_dirs" class="form-control selectpicker">
				<?php foreach($chmods_dir as $chmod):?>
					<option value="<?php echo $chmod?>"<?php if($chmod_dirs==$chmod):?> selected="selected"<?php endif?>><?php echo $chmod?></option>
				<?php endforeach?>
				</select>
			</div>
			<?php if(isset($error['chmod_dirs'])):?><span class="help-block"><?php echo $error['chmod_dirs']?></span><?php endif?>
		</div>
	</div>

	<hr/>

	<div class="form-group ">
		<button type="button" class="btn btn-primary btn-block" onclick="$('#chmod_start').submit()" form="chmod_start">Change access permissions</button>
	</div>

	<input type="hidden" name="doaction" value="doit"/>
	</form>

</div>
