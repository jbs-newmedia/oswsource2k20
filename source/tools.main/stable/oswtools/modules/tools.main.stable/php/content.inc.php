<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$Tool=new \osWFrame\Tools\Tool\Main('oswframe2k20', 'tools.main', 'stable');
if (\osWFrame\Core\Settings::getAction()=='noupdate') {
	$Tool->blockUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}
if (\osWFrame\Core\Settings::getAction()=='update') {
	$Tool->installUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}
if ($Tool->hasUpdate()===true) {
	$osW_Template->addJSCodeHead($Tool->getUpdateConfirm($osW_Template->buildhrefLink('current', 'action=update'), $osW_Template->buildhrefLink('current', 'action=noupdate')));
}

$Tool->addNavigationElement('start', ['action'=>'start', 'title'=>'Start', 'icon'=>'fa fa-home fa-fw']);
$Tool->addNavigationElement('more', ['title'=>'More', 'icon'=>'fas fa-cog fa-fw']);
$Tool->addNavigationElement('protecttools', ['action'=>'protecttools', 'title'=>'Protect Tools', 'icon'=>'fas fa-sign-in-alt fa-fw'], 'more');
$Tool->addNavigationElement('changelog', ['action'=>'changelog', 'title'=>'Changelog', 'icon'=>'fas fa-list fa-fw'], 'more');
$Tool->addNavigationElement('about', ['action'=>'about', 'title'=>'About', 'icon'=>'fas fa-info fa-fw'], 'more');
\osWFrame\Core\Settings::setAction($Tool->validateAction(\osWFrame\Core\Settings::getAction()));
$Tool->checkNavigation(\osWFrame\Core\Settings::getAction());

if (in_array(\osWFrame\Core\Settings::getAction(), ['about'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'about.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'changelog.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['protecttools'])) {
	$part=\osWFrame\Core\Settings::catchStringValue('part', '', 'pg');
	if (!in_array($part, ['new', 'manage'])) {
		$part='manage';
	}

	if (\osWFrame\Tools\Helper::getDoAction()=='domanage') {
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


	print_a($part);
	die();
	$osW_Template->setVar('part', $part);
} else {
	$jsfiles=['resources'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'tools.main.js'];
	$osW_Template->addTemplateJSFiles('head', $jsfiles);
}

$osW_Template->setVar('Tool', $Tool);

?>