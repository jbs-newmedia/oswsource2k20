<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */
class osW_Tool_Template extends osW_Tool_Object {

	public $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	public function outputB3Alerts($messages=array()) {
		if ($messages==array()) {
			$messages=osW_Tool_Session::getInstance()->get('messages');
		}

		if ((isset($messages['success']))&&($messages['success']!=array())) {
			echo '<div class="alert alert-success"><strong>Success!</strong><ul class="list-unstyled">';
			foreach ($messages['success'] as $message) {
				echo '<li>'.$message.'</li>';
			}
			echo '</ul>';
			echo '</div><hr/>';
		}

		if ((isset($messages['info']))&&($messages['info']!=array())) {
			echo '<div class="alert alert-info"><strong>Info!</strong><ul class="list-unstyled">';
			foreach ($messages['info'] as $message) {
				echo '<li>'.$message.'</li>';
			}
			echo '</ul>';
			echo '</div><hr/>';
		}

		if ((isset($messages['warning']))&&($messages['warning']!=array())) {
			echo '<div class="alert alert-warning"><strong>Warning!</strong><ul class="list-unstyled">';
			foreach ($messages['warning'] as $message) {
				echo '<li>'.$message.'</li>';
			}
			echo '</ul>';
			echo '</div><hr/>';
		}

		if ((isset($messages['danger']))&&($messages['danger']!=array())) {
			echo '<div class="alert alert-danger"><strong>Danger!</strong><ul class="list-unstyled">';
			foreach ($messages['danger'] as $message) {
				echo '<li>'.$message.'</li>';
			}
			echo '</ul>';
			echo '</div><hr/>';
		}

		osW_Tool_Session::getInstance()->set('messages', array());
	}

	public function outputHeader($css, $js) {
		echo '<!doctype html>';
		echo '<html lang="en">';
		echo '<head>';
		echo '<meta charset="utf-8">';
		echo '<title>osWFrame - '.osW_Tool::getInstance()->getToolValue('name'). ' (version-'.osW_Tool::getInstance()->getToolValue('version').'-'.osW_Tool::getInstance()->getToolValue('release').')</title>';
		echo '<meta name="author" content="osWFrame.com">';
		echo '<link rel="stylesheet" href="../resources/css/layout.css">';
		if (!empty($css)) {
			foreach ($css as $file) {
				echo '<link rel="stylesheet" href="'.$file.'">';
			}
		}
		if (!empty($js)) {
			foreach ($js as $file) {
				echo '<script src="'.$file.'"></script>';
			}
		}
		echo '</head>';
		echo '<body id="'.osW_Tool::getInstance()->getToolValue('id').'">';
	}

	public function outputB3Header($navigation, $css, $js, $script='', $fluid=false) {
		echo '<!DOCTYPE html>';
		echo '<html lang="en">';
		echo '<head>';
		echo '<title>osWFrame:'.osW_Tool::getInstance()->getToolValue('name'). ' | '.osW_Tool::getInstance()->getToolValue('version').'-'.osW_Tool::getInstance()->getToolValue('release').'</title>';
		echo '<meta charset="utf-8">';
		echo '<meta name="author" content="osWFrame.com">';
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
		echo '<link rel="shortcut icon" href="../resources/img/favicon.ico">';
		echo '<link rel="icon" type="image/png" href="../resources/img/oswtools32.png" sizes="32x32">';
		if (!empty($css)) {
			foreach ($css as $file) {
				echo '<link rel="stylesheet" href="'.$file.'">';
			}
		}
		if (!empty($js)) {
			foreach ($js as $file) {
				echo '<script src="'.$file.'"></script>';
			}
		}
		if ($script!='') {
			echo '<script>'.$script.'</script>';
		}
		echo '</head>';
		echo '<body>';

		echo '<nav class="navbar navbar-default navbar-fixed-top">';
		if ($fluid===true) {
			echo '<div class="container-fluid">';
		} else {
			echo '<div class="container">';
		}
		echo '<div class="navbar-header">';
		echo '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#NavbarHeader">';
		echo '<span class="icon-bar"></span>';
		echo '<span class="icon-bar"></span>';
		echo '<span class="icon-bar"></span>';
		echo '</button>';
		echo '<span class="navbar-brand"><a title="Back to osWTools:Main" href="../tools.main.stable/index.php?session='.osW_Tool_Session::getInstance()->getId().'"><i></i><strong>osW</strong>Tools</a>:'.osW_Tool::getInstance()->getToolValue('name').'</span>';
		echo '</div>';
		echo '<div class="collapse navbar-collapse" id="NavbarHeader">';
		if($navigation!=array()) {
			echo '<ul class="nav navbar-nav navbar-right">';
			foreach($navigation as $link_main) {
				if (in_array(osW_Tool::getInstance()->getAction(), $link_main['actions'])) {
					$active=' class="active"';
					$active_class=' active';
				} else {
					$active='';
					$active_class='';
				}
				if($link_main['links']==array()) {
					echo '<li'.$active.'>';
					if ($link_main['action']!='') {
						$action='?action='.$link_main['action'].'&session='.osW_Tool_Session::getInstance()->getId();
					} else {
						$action='?session='.osW_Tool_Session::getInstance()->getId();
					}
					echo '<a href="index.php'.$action.'"><i class="fa fa-'.$link_main['icon'].' fa-fw"></i>'.$link_main['title'].'</a>';
					echo '</li>';
				} else {
					echo '<li class="dropdown'.$active_class.'">';
					echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-'.$link_main['icon'].' fa-fw"></i>'.$link_main['title'].' <span class="caret"></span></a>';
					echo '<ul class="dropdown-menu">';
					foreach($link_main['links'] as $link_sub) {
						if (osW_Tool::getInstance()->getAction()==$link_sub['action']) {
							$active_sub=' class="active"';
						} else {
							$active_sub='';
						}

						if ($link_sub['action']!='') {
							$action_sub='?action='.$link_sub['action'].'&session='.osW_Tool_Session::getInstance()->getId();
						} else {
							$action='?session='.osW_Tool_Session::getInstance()->getId();
						}

						echo '<li'.$active_sub.'><a href="index.php'.$action_sub.'"><i class="fa fa-'.$link_sub['icon'].' fa-fw"></i>'.$link_sub['title'].'</a></li>';
					}
					echo '</ul>';
					echo '</li>';
				}
			}
			echo '</ul>';
		}
		echo '</div>';
		echo '</div>';
		echo '</nav>';

		/*
		echo '<div class="container">';
		echo '<div class="alert alert-danger"><strong>Warning!</strong>osWTools are unprotected.</div>';
		echo '</div>';
		*/
	}

	public function outputB3Footer($fluid=false) {
		echo '<footer class="navbar navbar-default navbar-fixed-bottom">';
		if ($fluid===true) {
			echo '<div class="container-fluid">';
		} else {
			echo '<div class="container">';
		}
		echo '<p class="text-muted text-center"><strong>osW</strong>Tools:'.osW_Tool::getInstance()->getToolValue('name').' - Author:'.osW_Tool::getInstance()->getToolValue('author').' - Copyright:'.osW_Tool::getInstance()->getToolValue('copyright').'</p>';
		echo '</div>';
		echo '</footer>';
		echo '<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Click to return on the top page" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>';
		echo '</body>';
		echo '</html>';
	}

	public function outputInfo() {
		echo '<div class="toolbox toolbox_info">';
		echo '<p><strong>Name</strong>: '.osW_Tool::getInstance()->getToolValue('name').'</p>';
		echo '<p><strong>Author</strong>: '.osW_Tool::getInstance()->getToolValue('author').'</p>';
		echo '<p><strong>Copyright</strong>: '.osW_Tool::getInstance()->getToolValue('copyright').'</p>';
		echo '<p><strong>Version</strong>: '.osW_Tool::getInstance()->getToolValue('version').'</p>';
		echo '<p><strong>Release</strong>: '.osW_Tool::getInstance()->getToolValue('release').'</p>';
		echo '<p><strong>Link</strong>: '.osW_Tool::getInstance()->getToolValue('link').'</p>';
		echo '<p><strong>License</strong>: '.osW_Tool::getInstance()->getToolValue('license').'</p>';
		echo '</div>';
	}

	public function outputFooter() {
		echo '</body>';
		echo '</html>';
	}

	public function buildhrefLink($module='', $parameters='', $replace_amp=true) {
		if (($module=='')||($module=='default')) {
			$module=$_SERVER['PHP_SELF'];
		}
		if ($module=='current') {
			$module=$_SERVER['PHP_SELF'];
		}

		$url=$module;
		if ($parameters!='') {
			$url.='?'.$parameters;
		}
		if ($replace_amp===true) {
			return str_replace('&', '&amp;', $url);
		} else {
			return $url;
		}
	}

	public function outputString($str) {
		return nl2br(htmlentities($str, ENT_COMPAT, 'UTF-8'));
	}

	public function outputUrlString($str) {
		$german_search = array("Ä", "ä", "Ü", "ü", "Ö", "ö", "ß");
		$german_replace = array("Ae", "ae", "Ue", "ue", "Oe", "oe", "ss");
		$str = str_replace($german_search, $german_replace, $str);
		$str = preg_replace('/[^a-zA-Z0-9]/', ' ', $str);
		$str = preg_replace('/\s\s+/', ' ', $str);
		$str = str_replace(' ', '-', trim($str));
		return urlencode($str);
	}

	/**
	 *
	 * @return osW_Tool_Template
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>