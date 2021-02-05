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
 * TOOL - HTAccess
 */

?>

<div class="container">


<?php

$dirs = osW_Tool_HTWriter::getInstance()->listdir(realpath('../../'));

asort($dirs);

array_unshift($dirs, 'select dir', '/');

$config = array();
$config['htwriter'] = abs_path.'.htaccess';
$config['htpasswd'] = abs_path.'.htpasswd';

$error=array();
$success=array();
if (osW_Tool::getInstance()->getDoAction()=='doit') {
	$htwriter_directory=osW_Tool::getInstance()->_catch('htwriter_directory', '', 'pg');
	$htwriter_user=osW_Tool::getInstance()->_catch('htwriter_user', '', 'pg');
	$htwriter_password=osW_Tool::getInstance()->_catch('htwriter_password', '', 'pg');
	$htwriter_confirm=osW_Tool::getInstance()->_catch('htwriter_confirm', '', 'pg');


	if (strlen($htwriter_directory)==0) {
		$error['htwriter_directory']='Please select your directory';
	} elseif (!is_dir(realpath('../../').$htwriter_directory)) {
		$error['htwriter_directory']='Please select your directory';
	}

	if (strlen($htwriter_user)==0) {
		$error['htwriter_user']='Please enter your username';
	}
	if (strlen($htwriter_password)==0) {
		$error['htwriter_password']='Please enter your password';
	}
	if (strlen($htwriter_confirm)==0) {
		$error['htwriter_confirm']='Please confirm your password';
	}
	if ($htwriter_password!=$htwriter_confirm) {
		$error['htwriter_confirm']='The passwords must be the same';
	}

	if(count($error)==0) {
		$config = array();
		$config['htwriter'] = root_path.substr($htwriter_directory, 1).'.htaccess';
		$config['htpasswd'] = root_path.substr($htwriter_directory, 1).'.htpasswd';

		$htwriter_password = crypt($htwriter_password, crypt('pass12$hz', time()));
		$users[$htwriter_user]=$htwriter_user.':'.$htwriter_password;

		// create htaccess file if not exist
		if (file_exists($config['htwriter'])) {
			$htaccess=file_get_contents($config['htwriter']);
		} else {
			$htaccess='';
		}

		if ((!file_exists($config['htwriter']))||(!strstr($htaccess, '#osWFrame tpf#'))) {
			$file = fopen($config['htwriter'], "w+");
			$rules = array( '#osWFrame tpf#',
					'AuthType Basic',
					'AuthName "Login osWTools"',
					'AuthUserFile "'.$config['htpasswd'].'"',
					'require valid-user');

			foreach($rules as $line) {
				fputs($file,$line."\n");
			}
			fclose($file);
			@chmod($config['htwriter'], osW_Tool::getInstance()->chmodFile());
		}

		// create htpasswd file if not exist
		$file = fopen($config['htpasswd'], "w+");
		foreach($users as $line) {
			fputs($file,$line."\n");
		}
		fclose($file);
		@chmod($config['htpasswd'], osW_Tool::getInstance()->chmodFile());

		$messages['success'][]='htaccess/htpasswd was created/updated wassuccessfully.';
		osW_Tool_Session::getInstance()->set('messages', $messages);

		$_POST['htwriter_directory']='';
		$_POST['htwriter_user']='';
		$_POST['htwriter_password']='';
		$_POST['htwriter_confirm']='';
	}
}

$messages=osW_Tool_Session::getInstance()->get('messages');

osW_Tool_Template::getInstance()->outputB3Alerts($messages);

?>
	<form id="htwriter_new" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=start">

	<div class="form-group<?php if(isset($error['htwriter_directory'])):?> has-error<?php endif?>">
		<label for="htwriter_directory" class="control-label">Directory</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-folder fa-lg" aria-hidden="true"></i></span>
				<select title="Select directory" name="htwriter_directory" class="form-control selectpicker">
				<?php foreach($dirs as $dir):?>
					<option value="<?php echo $dir?>"<?php if(isset($_POST['htwriter_directory'])&&($_POST['htwriter_directory']==$dir)):?> selected="selected"<?php endif?>><?php echo $dir?></option>
				<?php endforeach?>
				</select>
			</div>
			<?php if(isset($error['htwriter_directory'])):?><span class="help-block"><?php echo $error['htwriter_directory']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['htwriter_user'])):?> has-error<?php endif?>">
		<label for="htwriter_user" class="control-label">Username</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user fa-lg" aria-hidden="true"></i></span>
				<input type="text" class="form-control" name="htwriter_user" id="htwriter_user" placeholder="Enter your username" value="<?php echo osW_Tool::getInstance()->_catch('htwriter_user', '', 'pg')?>"/>
			</div>
			<?php if(isset($error['htwriter_user'])):?><span class="help-block"><?php echo $error['htwriter_user']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['htwriter_password'])):?> has-error<?php endif?>">
		<label for="htwriter_password" class="control-label">Password</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
				<input type="password" class="form-control" name="htwriter_password" id="htwriter_password" placeholder="Enter your password" value="<?php echo osW_Tool::getInstance()->_catch('htwriter_password', '', 'pg')?>"/>
			</div>
			<?php if(isset($error['htwriter_password'])):?><span class="help-block"><?php echo $error['htwriter_password']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['htwriter_confirm'])):?> has-error<?php endif?>">
		<label for="htwriter_confirm" class="control-label">Confirm Password</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
				<input type="password" class="form-control" name="htwriter_confirm" id="htwriter_confirm" placeholder="Confirm your password" value="<?php echo osW_Tool::getInstance()->_catch('htwriter_confirm', '', 'pg')?>"/>
			</div>
			<?php if(isset($error['htwriter_confirm'])):?><span class="help-block"><?php echo $error['htwriter_confirm']?></span><?php endif?>
		</div>
	</div>

	<hr/>

	<div class="form-group ">
		<button type="button" class="btn btn-primary btn-block" onclick="$('#htwriter_new').submit()" form="htwriter_new">Create/Update htaccess/htpasswd</button>
	</div>

	<input type="hidden" name="doaction" value="doit"/>
	</form>
</div>
