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
 * TOOL - ProtectTools
 */

$part=osW_Tool::getInstance()->_catch('part', 'new', 'pg');
if (!in_array($part, array('new', 'manage'))) {
	$part='manage';
}

$htpasswd_file=abs_path.'.htpasswd';

$users=array();
if (file_exists($htpasswd_file)) {
	$htpasswd=file($htpasswd_file);

	if (count($htpasswd)>0) {
		foreach ($htpasswd as $user) {
			if (strlen($user)>3) {
				$ar_user=explode(':', $user);
				if (count($ar_user)>=2) {
					$users[$ar_user[0]]=trim($user);
				}
			}
		}
	}
} else {
	$htpasswd=array();
}

?>

<div class="container">

	<ul class="nav nav-tabs">
		<li<?php if($part=='new'):?> class="active"<?php endif?>><a href="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=<?php echo osW_Tool::getInstance()->getAction()?>&part=new">New User</a></li>
		<li<?php if($part=='manage'):?> class="active"<?php endif?>><a href="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=<?php echo osW_Tool::getInstance()->getAction()?>&part=manage">Manage User</a></li>
	</ul>

	<br>


<?php

/*
 * TOOL - New
 */

if($part=='new') {

	$error=array();
	$messages=array();

	if (osW_Tool::getInstance()->getDoAction()=='donew') {
		$htaccess_user=osW_Tool::getInstance()->_catch('htaccess_user', '', 'pg');
		$htaccess_password=osW_Tool::getInstance()->_catch('htaccess_password', '', 'pg');
		$htaccess_confirm=osW_Tool::getInstance()->_catch('htaccess_confirm', '', 'pg');

		if (strlen($htaccess_user)==0) {
			$error['htaccess_user']='Please enter your username';
		}
		if (strlen($htaccess_password)==0) {
			$error['htaccess_password']='Please enter your password';
		}
		if (strlen($htaccess_confirm)==0) {
			$error['htaccess_confirm']='Please confirm your password';
		}
		if ($htaccess_password!=$htaccess_confirm) {
			$error['htaccess_confirm']='The passwords must be the same';
		}

		if(count($error)==0) {
			$config = array();
			$config['htaccess'] = abs_path.'.htaccess';
			$config['htpasswd'] = abs_path.'.htpasswd';

			$users[$htaccess_user]=$htaccess_user.':'.crypt($htaccess_password, crypt('pass12$hz', 'oswtools'));

			if (file_exists($config['htaccess'])) {
				$htaccess=file_get_contents($config['htaccess']);
			} else {
				$htaccess='';
			}
			if ((!file_exists($config['htaccess']))||(!strstr($htaccess, '#osWFrame tpf#'))) {
				$file = fopen($config['htaccess'], "w+");
				$rules = array( '#osWFrame tpf#',
								'AuthType Basic',
								'AuthName "osWTools"',
								'AuthUserFile "'.$config['htpasswd'].'"',
								'require valid-user');

				foreach($rules as $line) {
					fputs($file,$line."\n");
				}
				fclose($file);
				chmod($config['htaccess'], osW_Tool::getInstance()->chmodFile());
			}

			$file = fopen($config['htpasswd'], "w+");
			foreach($users as $line) {
				fputs($file,$line."\n");
			}
			fclose($file);
			chmod($config['htpasswd'], osW_Tool::getInstance()->chmodFile());

			$users=array();
			if (file_exists($htpasswd_file)) {
				$htpasswd=file($htpasswd_file);

				if (count($htpasswd)>0) {
					foreach ($htpasswd as $user) {
						if (strlen($user)>3) {
							$ar_user=explode(':', $user);
							if (count($ar_user)>=2) {
								$users[$ar_user[0]]=trim($user);
							}
						}
					}
				}
			} else {
				$htpasswd=array();
			}

			$messages['success'][]='User was created successfully in the htaccess.';
			osW_Tool_Session::getInstance()->set('messages', $messages);
			osW_Tool::getInstance()->_direct('index.php?session='.osW_Tool_Session::getInstance()->getId().'&action=protecttools&part=manage');
		}
	}

?>

	<p>Please enter the requested data and confirm your input by pressing the button "create user in .htaccess".</p>

	<hr/>

	<form id="protecttools_new" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=protecttools&part=new">

	<div class="form-group<?php if(isset($error['htaccess_user'])):?> has-error<?php endif?>">
		<label for="htaccess_user" class="control-label">Username</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user fa-lg" aria-hidden="true"></i></span>
				<input type="text" class="form-control" name="htaccess_user" id="htaccess_user" placeholder="Enter your username" value="<?php echo osW_Tool::getInstance()->_catch('htaccess_user', '', 'pg')?>"/>
			</div>
			<?php if(isset($error['htaccess_user'])):?><span class="help-block"><?php echo $error['htaccess_user']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['htaccess_password'])):?> has-error<?php endif?>">
		<label for="htaccess_password" class="control-label">Password</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
				<input type="password" class="form-control" name="htaccess_password" id="htaccess_password" placeholder="Enter your password" value="<?php echo osW_Tool::getInstance()->_catch('htaccess_password', '', 'pg')?>"/>
			</div>
			<?php if(isset($error['htaccess_password'])):?><span class="help-block"><?php echo $error['htaccess_password']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['htaccess_confirm'])):?> has-error<?php endif?>">
		<label for="htaccess_confirm" class="control-label">Confirm Password</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
				<input type="password" class="form-control" name="htaccess_confirm" id="htaccess_confirm" placeholder="Confirm your password" value="<?php echo osW_Tool::getInstance()->_catch('htaccess_confirm', '', 'pg')?>"/>
			</div>
			<?php if(isset($error['htaccess_confirm'])):?><span class="help-block"><?php echo $error['htaccess_confirm']?></span><?php endif?>
		</div>
	</div>

	<hr/>

	<div class="form-group ">
		<button type="button" class="btn btn-primary btn-block" onclick="$('#protecttools_new').submit()" form="protecttools_new">Create user in .htaccess</button>
	</div>

	<input type="hidden" name="doaction" value="donew"/>
	</form>

<?php

}

/*
 * TOOL - Manage
 */

if($part=='manage') {
	if (osW_Tool::getInstance()->getDoAction()=='domanage') {

		$config = array();
		$config['htaccess'] = abs_path.'.htaccess';
		$config['htpasswd'] = abs_path.'.htpasswd';

		if (count($users)>0) {
			foreach ($users as $user => $blank) {
				if (isset($_POST['updtusers'][$user])) {
					unset($users[$user]);
				}
			}
		}

		if (file_exists($config['htaccess'])) {
			$htaccess=file_get_contents($config['htaccess']);
		} else {
			$htaccess='';
		}
		if ((!file_exists($config['htaccess']))||(!strstr($htaccess, '#osWFrame tpf#'))) {
			$file = fopen($config['htaccess'], "w+");
			$rules = array( '#osWFrame tpf#',
					'AuthType Basic',
					'AuthName "osWTools"',
					'AuthUserFile "'.$config['htpasswd'].'"',
					'require valid-user');

			foreach($rules as $line) {
				fputs($file,$line."\n");
			}
			fclose($file);
			chmod($config['htaccess'], osW_Tool::getInstance()->chmodFile());
		}

		$file = fopen($config['htpasswd'], "w+");
		foreach($users as $line) {
			fputs($file,$line."\n");
		}
		fclose($file);
		chmod($config['htpasswd'], osW_Tool::getInstance()->chmodFile());

		$users=array();
		if (file_exists($htpasswd_file)) {
			$htpasswd=file($htpasswd_file);

			if (count($htpasswd)>0) {
				foreach ($htpasswd as $user) {
					if (strlen($user)>3) {
						$ar_user=explode(':', $user);
						if (count($ar_user)>=2) {
							$users[$ar_user[0]]=trim($user);
						}
					}
				}
			} else {
				unlink($config['htaccess']);
				unlink($config['htpasswd']);
			}
		} else {
			$htpasswd=array();
		}

		$messages['success'][]='.htaccess has been successfully updated.';
		osW_Tool_Session::getInstance()->set('messages', $messages);
		osW_Tool::getInstance()->_direct('index.php?session='.osW_Tool_Session::getInstance()->getId().'&action=protecttools&part=manage');
	}

?>

	<p>Here you can see an overview of all created users. Select "remove" and then the "remove selected users from .htaccess" button to delete the users.</p>

	<hr/>
<?php
$messages=osW_Tool_Session::getInstance()->get('messages');

osW_Tool_Template::getInstance()->outputB3Alerts($messages);


?>

	<form id="protecttools_manage" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=protecttools&part=manage">

	<table id="oswtools_main_protecttools_manager" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Username</th>
				<th>Option</th>
			</tr>
		</thead>
		<tbody>
<?php if(count($htpasswd)>0):?>
<?php $i=1;foreach ($users as $user => $blank):$i++?>
			<tr>
				<td><?php echo outputString($user)?></td>
				<td><label class="checkbox-inline"><input type="checkbox" name="updtusers[<?php echo $user;?>]" value="1"/> remove</label></td>
			</tr>
<?php endforeach?>
<?php else:?>
			<tr>
				<td colspan="2">No users available in table</td>
			</tr>
<?php endif?>
		</tbody>
	</table>

<?php if(count($htpasswd)>0):?>

	<hr/>



	<div class="form-group ">
		<button type="button" class="btn btn-primary btn-block" onclick="$('#protecttools_manage').submit()" form="protecttools_manage">Remove selected users from .htaccess</button>
	</div>

	<input type="hidden" name="doaction" value="domanage"/>

<?php endif?>

</form>



<?php

}

?>


</div>
