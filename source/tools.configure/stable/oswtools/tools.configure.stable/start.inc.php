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
if ((isset($_POST['next']))&&($_POST['next']=='Next step')) {
	$_POST['next']='next';
}

if ((isset($_POST['prev']))&&($_POST['prev']=='Previous step')) {
	$_POST['prev']='prev';
}

$ready_status=0;

osW_Tool_Configure::getInstance()->getFiles();

$load=false;

if ((isset($_POST['next']))&&($_POST['next']=='next')) {
	osW_Tool_Configure::getInstance()->getValuesFromJSON();
	osW_Tool_Configure::getInstance()->loadFile('validate');
	osW_Tool_Configure::getInstance()->setDefaultValuesFromJSON();
	$load=true;

	osW_Tool_Configure::getInstance()->getValuesFromJSON();
	osW_Tool_Configure::getInstance()->validateFields();

	if (osW_Tool_Configure::getInstance()->hasError()!==true) {
		osW_Tool_Configure::getInstance()->writeValuesToJSON();
		osW_Tool_Configure::getInstance()->incPage();
		$ready_status=osW_Tool_Configure::getInstance()->writeConfigure();
		if (osW_Tool_Configure::getInstance()->isLastPage()!==true) {
			$load=false;
		}
	}
}

if ($load!==true) {
	osW_Tool_Configure::getInstance()->getValuesFromJSON();
	osW_Tool_Configure::getInstance()->loadFile('run');
	osW_Tool_Configure::getInstance()->setDefaultValuesFromJSON();
}

$config=osW_Tool_Configure::getInstance()->get();

$message_type='info';

if (osW_Tool_Configure::getInstance()->isLastPage()===true) {
	$config['settings']=[];

	$config['settings']['data']=['page_title'=>'Write configure.project.php',];

	$message_type='success';

	if ($ready_status==0) {
		$config['messages'][]='modules/configure.project.php is up to date';
	}
	if ($ready_status==1) {
		$config['messages'][]='modules/configure.project.php created succesfully';
	}
	if ($ready_status==2) {
		$config['messages'][]='modules/configure.project.php updated succesfully';
	}
}

?>

<div class="container">

	<form name="configure" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId() ?>&action=start">

		<h3>Step <?php echo($config['info']['page']+1) ?>/<?php echo $config['info']['pages'] ?>: <?php echo outputString($config['settings']['data']['page_title']) ?></h3>

		<hr/>

		<?php if (!empty($config['error'])): ?><?php osW_Tool_Template::getInstance()->outputB3Alerts(['danger'=>$config['error']]) ?><?php endif ?>
		<?php if (!empty($config['messages'])): ?><?php osW_Tool_Template::getInstance()->outputB3Alerts([$message_type=>$config['messages']]) ?><?php endif ?>
		<?php if (!empty($config['settings']['fields'])): ?><?php $i=1;
			foreach ($config['settings']['fields'] as $config_element=>$config_data):$i++ ?><?php $__file=abs_path.'resources/php/configure/template/'.$config_data['default_type'].'.tpl.php' ?><?php if (file_exists($__file)): ?><?php include $__file; ?><?php endif ?><?php endforeach ?>
			<hr/>
		<?php endif ?>

		<div class="form-group ">
			<?php if ($config['info']['page']>0): ?>
				<input type="submit" class="btn btn-default" style="width:150px" name="prev" value="Previous step"/>
			<?php endif; ?>
			<?php if (osW_Tool_Configure::getInstance()->isLastPage()!==true): ?>
				<input type="submit" class="btn btn-primary" style="float:right; width:150px" name="next" value="Next step"/>
			<?php endif; ?>
		</div>

		<input type="hidden" name="page" value="<?php echo $config['info']['page']; ?>"/>

	</form>

</div>