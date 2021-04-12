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

namespace osWFrame\Tools\Tool;

use osWFrame\Core as Frame;
use osWFrame\Tools as Tools;

class CoreTool {

	use Frame\BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=0;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var bool
	 */
	private bool $fluid_navigation=false;

	/**
	 * @var bool
	 */
	private bool $fluid_content=false;

	/**
	 * @var bool
	 */
	private bool $vh=false;

	/**
	 * @var string
	 */
	private string $serverlist='';

	/**
	 * @var string
	 */
	private string $package='';

	/**
	 * @var string
	 */
	private string $release='';

	/**
	 * @var array
	 */
	private array $details=[];

	/**
	 * @var array
	 */
	private array $navigation=[];

	/**
	 * @var array
	 */
	private array $actions=[];

	/**
	 * @var array
	 */
	private array $actions_names=[];

	/**
	 * @var array
	 */
	private array $used_software=[];

	/**
	 * Tool constructor.
	 *
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 */
	public function __construct(string $serverlist, string $package, string $release) {
		$this->setServerlist($serverlist);
		$this->setPackage($package);
		$this->setRelease($release);
		$this->initTool();
		$this->initUsedSoftware();
	}

	/**
	 * @param bool $vh
	 * @return object
	 */
	public function setVH(bool $vh):object {
		$this->vh=$vh;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getVH():bool {
		return $this->vh;
	}

	/**
	 * @param bool $fluid
	 * @return object
	 */
	public function setFluidNavigation(bool $fluid):object {
		$this->fluid_navigation=$fluid;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getFluidNavigation():bool {
		return $this->fluid_navigation;
	}

	/**
	 * @param bool $fluid
	 * @return object
	 */
	public function setFluidContent(bool $fluid):object {
		$this->fluid_content=$fluid;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getFluidContent():bool {
		return $this->fluid_content;
	}

	/**
	 * @param string $serverlist
	 * @return object
	 */
	public function setServerlist(string $serverlist):object {
		$this->serverlist=$serverlist;

		return $this;
	}

	/**
	 * @param string $package
	 * @return object
	 */
	public function setPackage(string $package):object {
		$this->package=$package;

		return $this;
	}

	/**
	 * @param string $release
	 * @return object
	 */
	public function setRelease(string $release):object {
		$this->release=$release;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getServerlist():string {
		return $this->serverlist;
	}

	/**
	 * @return string
	 */
	public function getPackage():string {
		return $this->package;
	}

	/**
	 * @return string
	 */
	public function getRelease():string {
		return $this->release;
	}

	/**
	 * @return object
	 */
	public function initTool():object {
		$file=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'package'.DIRECTORY_SEPARATOR.$this->getPackage().'-'.$this->getRelease().'.json';
		$this->details['name']='';
		$this->details['author']='';
		$this->details['copyright']='';
		$this->details['link']='';
		$this->details['license']='';
		$this->details['package']='';
		$this->details['release']='';
		$this->details['version']='';
		$this->details['version_update']='';
		if (file_exists($file)) {
			$info=json_decode(file_get_contents($file), true);
			foreach ($info['info'] as $key=>$value) {
				$this->details[$key]=$value;
			}
		}
		$this->details['server']='';
		$this->details['updtserver']='';
		$this->details['connected']=false;
		$this->details['updterror']=false;

		return $this;
	}

	/**
	 * @return object
	 */
	public function initUsedSoftware():object {
		$this->addUsedSoftware('jQuery', 'https://jquery.com', 'Write less, do more. JavaScript library');
		$this->addUsedSoftware('Bootstrap', 'https://getbootstrap.com', 'The most popular HTML, CSS, and JS library in the world');
		$this->addUsedSoftware('Font Awesome', 'https://fontawesome.com', 'the iconic font and CSS toolkit');
		$this->addUsedSoftware('DataTables', 'https://datatables.net', 'Table plug-in for jQuery/Bootstrap');
		$this->addUsedSoftware('Bootbox.js', 'http://bootboxjs.com', 'Bootstrap modals made easy');
		$this->addUsedSoftware('bootstrap-select', 'https://developer.snapappointments.com/bootstrap-select/', 'Bootstrap-select is a jQuery plugin that utilizes Bootstrap\'s dropdown.js to style and bring additional functionality to standard select elements');
		$this->addUsedSoftware('jQuery Easing', 'https://github.com/gdsmith/jquery.easing/', 'A jQuery plugin from GSGD to give advanced easing options');

		return $this;
	}

	/**
	 * @param string $name
	 * @param string $url
	 * @param string $description
	 * @return object
	 */
	public function addUsedSoftware(string $name, string $url, string $description):object {
		$this->used_software[md5($name)]=['name'=>$name, 'url'=>$url, 'description'=>$description];

		return $this;
	}

	/**
	 * @return array
	 */
	public function getUsedSoftware():array {
		return $this->used_software;
	}

	/**
	 * @param string $key
	 * @return string|null
	 */
	public function getStringValue(string $key):?string {
		if (isset($this->details[$key])) {
			return strval($this->details[$key]);
		}

		return null;
	}

	/**
	 * @param string $key
	 * @return bool|null
	 */
	public function getBoolValue(string $key):?bool {
		if (isset($this->details[$key])) {
			return boolval($this->details[$key]);
		}

		return null;
	}

	/**
	 * @param string $link
	 */
	public function blockUpdate(string $link):void {
		$update=Frame\Session::getArrayVar('update');
		if ($update==null) {
			$update=[];
		}
		if (!isset($update[$this->getServerlist().'#'.$this->getPackage().'#'.$this->getRelease()])) {
			$update[$this->getServerlist().'#'.$this->getPackage().'#'.$this->getRelease()]=time();
			Frame\Session::setArrayVar('update', $update);
		}

		Frame\Network::directHeader($link);
	}

	/**
	 * @return bool
	 */
	public function hasUpdate():bool {
		$file=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'package'.DIRECTORY_SEPARATOR.$this->getPackage().'-'.$this->getRelease().'.json';
		if (file_exists($file)) {
			$info=json_decode(file_get_contents($file), true);
			$server_data=Tools\Server::getConnectedServer($this->getServerlist());
			if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
				$package_version=Tools\Server::getUrlData($server_data['server_url'].'?action=get_version&package='.$this->getPackage().'&release='.$this->getRelease().'&version='.$info['info']['version']);
				$this->details['version_update']=$package_version;
				if (Tools\Helper::checkVersion($this->getStringValue('version'), $package_version)) {
					$update=Frame\Session::getArrayVar('update');
					if (($update==null)||(!isset($update[$this->getServerlist().'#'.$this->getPackage().'#'.$this->getRelease()]))) {
						Tools\Server::updatePackageList(true);
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * @param string $update_link
	 * @param string $update_no
	 * @return string
	 */
	public function getUpdateConfirm(string $update_link, string $update_no):string {
		return '$(function() { osWTools_confirmUpdate(\'Update <strong>'.$this->getStringValue('name').'</strong> to version <strong>'.$this->getStringValue('version_update').'</strong>\', \''.$update_link.'\', \''.$update_no.'\');});';
	}

	/**
	 * @param string $link
	 * @return void
	 */
	public function installUpdate(string $link):void {
		$Manager=new Tools\Manager();
		$Manager->installPackage($this->getServerlist(), $this->getPackage(), $this->getRelease());
		Frame\Network::directHeader($link);
	}

	/**
	 * @param string $element_name
	 * @param array $element_details
	 * @param string $position
	 * @return object
	 */
	public function addNavigationElement(string $element_name, array $element_details, string $position=''):object {
		if (isset($element_details['action'])) {
			$this->addAction($element_details['action']);
		}
		if ($position=='') {
			$this->navigation[$element_name]=$element_details;
			$this->navigation[$element_name]['active']=false;
			$this->navigation[$element_name]['links']=[];
		} else {
			$this->navigation[$position]['links'][$element_name]=$element_details;
			$this->navigation[$position]['links'][$element_name]['active']=false;
		}

		return $this;
	}

	/**
	 * @param string $action
	 * @return object
	 */
	public function checkNavigation(string $action):object {
		foreach ($this->navigation as $element_name=>$a) {
			if ((isset($this->navigation[$element_name]['action']))&&($this->navigation[$element_name]['action']==$action)) {
				$this->navigation[$element_name]['active']=true;
				$this->actions_names[$this->navigation[$element_name]['action']]=$this->navigation[$element_name]['title'];
			}

			if (isset($this->navigation[$element_name]['links'])) {
				foreach ($this->navigation[$element_name]['links'] as $element_link_name=>$b) {
					if ((isset($this->navigation[$element_name]['links'][$element_link_name]['action']))&&($this->navigation[$element_name]['links'][$element_link_name]['action']==$action)) {
						$this->navigation[$element_name]['active']=true;
						$this->navigation[$element_name]['links'][$element_link_name]['active']=true;
						$this->actions_names[$this->navigation[$element_name]['links'][$element_link_name]['action']]=$this->navigation[$element_name]['links'][$element_link_name]['title'];
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @param string $action
	 * @return string
	 */
	public function getActionName(string $action):string {
		if (isset($this->actions_names[$action])) {
			return $this->actions_names[$action];
		}

		return 'Unnamed';
	}

	/**
	 * @param string $action
	 * @return object
	 */
	public function addAction(string $action):object {
		if (!in_array($action, $this->actions)) {
			$this->actions[]=$action;
		}

		return $this;
	}

	/**
	 * @param string $action
	 * @return string
	 */
	public function validateAction(string $action):string {
		if (in_array($action, $this->actions)) {
			return $action;
		}

		return 'start';
	}

	/**
	 * @return array
	 */
	public function getNavigation():array {
		return $this->navigation;
	}

	/**
	 * @return array
	 */
	public function getActions():array {
		return $this->actions;
	}

}

?>