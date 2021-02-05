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
class osW_Tool_Server extends osW_Tool_Object {

	public $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	public function getMac() {
		if (!isset($this->data['mac'])) {
			ob_start();
			system('netstat -ei');
			$mycom=ob_get_contents();
			ob_clean();

			preg_match_all('/HWaddr ([a-fA-F0-9]{2}[:|\-]?){6}/Uis', $mycom, $result);

			$this->data['mac']=array();
			foreach ($result[0] as $var) {
				$var=str_replace('HWaddr ', '', $var);
				$this->data['mac'][$var]=$var;
			}
		}
		return $this->data['mac'];
	}

	public function getUrlData($file) {
		if (!isset($_SERVER['SERVER_NAME'])) {

		}
		if (!strpos($file, '?')) {
			$file.='?server_name='.urlencode($_SERVER['SERVER_NAME']);
		} else {
			$file.='&server_name='.urlencode($_SERVER['SERVER_NAME']);
		}
		$file.='&server_mac='.urlencode(implode(';', $this->getMac()));
		if (function_exists('curl_init')) {
			$res=curl_init();
			curl_setopt($res, CURLOPT_URL, $file);
			curl_setopt($res, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($res, CURLOPT_SSL_VERIFYPEER, 0);
			$file=curl_exec($res);
			curl_close($res);
		} else {
			$res=@fopen($file, 'r');
			if (!$res) {
				$file='';
			} else {
				$file='';
				while (feof($res)!=1) {
					$file.=fgets($res, 1024);
				}
				fclose($res);
			}
		}
		return $file;
	}

	public function connectServer($serverlist='oswframe2k20') {
		$this->readServerList($serverlist);
		$name='server_'.$serverlist;
		$name_list='serverlist';
		foreach ($this->data[$name_list][$serverlist]['data'] as $server_id=>$server_data) {
			$_content=$this->getUrlData($server_data['server_url']);
			if ((strlen($_content)>=26)&&(strlen($_content)<=128)) {
				if (stristr($_content, 'osWFrame Release Server')) {
					$this->data[$name][$serverlist]=$server_data;
					$this->data[$name][$serverlist]['connected']=true;
					$this->data[$name][$serverlist]['server_name_real']=$_content;
					return true;
				}
			}
		}
		return false;
	}

	public function getConnectedServer($serverlist='oswframe2k20') {
		$name='server_'.$serverlist;
		if (!isset($this->data[$name][$serverlist])) {
			$this->connectServer($serverlist);
		}

		if (!isset($this->data[$name][$serverlist])) {
			return array();
		}

		return $this->data[$name][$serverlist];
	}

	public function readServerList($serverlist='') {
		$name='serverlist';
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$directory=abs_path.'resources/json/serverlist/';
			$handle=opendir($directory);
			while ($file=readdir($handle)) {
				if (($file!='.')&&($file!='..')) {
					$currentserverlist=substr($file, 0, -5);
					$jsonfile=$directory.$file;
					$this->data[$name][$currentserverlist]=json_decode(file_get_contents($jsonfile), true);
				}
			}
			ksort($this->data[$name]);
		}
		if ($serverlist!='') {
			return $this->data[$name][$serverlist];
		}

		return $this->data[$name];
	}

	public function setServerList($json, $serverlist='') {
		$name='serverlist';
		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
			$this->data[$name][$serverlist]=json_decode($json, true);
			ksort($this->data[$name]);
		}
		if ($serverlist!='') {
			return $this->data[$name][$serverlist];
		}
		return $this->data[$name];
	}

	public function getPackageList($serverlist='') {
		$name='packagelist_'.$serverlist;
		$name_list='serverlist';
		if (!isset($this->data[$name])) {
			$this->readServerList($serverlist);
			foreach ($this->data[$name_list] as $serverlist_name=>$serverlist_value) {
				$server_data=$this->getConnectedServer($serverlist_name);
				if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
					$file=abs_path.'resources/json/packagelist/'.$serverlist_name.'.json';
					$this->data[$name][$serverlist_name]=json_decode(file_get_contents($file), true);
				}
			}
		}
		if ($serverlist!='') {
			return $this->data[$name][$serverlist];
		}
		return $this->data[$name];
	}

	public function checkPackageList($packagelist) {
		$installed=array();
		foreach ($packagelist as $key=>$package) {
			$file=abs_path.'resources/json/package/'.$package['package'].'-'.$package['release'].'.json';

			if (isset($package['info']['name'])) {
				$packagelist[$key]['key']=$package['info']['name'].'-'.$key;
			} else {
				$packagelist[$key]['key']=$key;
			}

			if (file_exists($file)) {
				$info=json_decode(file_get_contents($file), true);
				$packagelist[$key]['version_installed']=$info['info']['version'];
				$installed[$info['info']['package']]=true;
			} else {
				$packagelist[$key]['version_installed']='0.0';
			}
		}

		foreach ($packagelist as $key=>$package) {
			$packagelist[$key]['options']=array();
			$packagelist[$key]['options']['install']=false;
			$packagelist[$key]['options']['update']=false;
			$packagelist[$key]['options']['remove']=false;
			$packagelist[$key]['options']['blocked']=false;
			$packagelist[$key]['options']['index']=false;
			if ($packagelist[$key]['version_installed']=='0.0') {
				if (!isset($installed[$packagelist[$key]['package']])) {
					$packagelist[$key]['options']['install']=true;
				}
			} elseif (osW_Tool::getInstance()->checkVersion($packagelist[$key]['version_installed'], $packagelist[$key]['version'])) {
				$packagelist[$key]['options']['update']=true;
				$packagelist[$key]['options']['remove']=true;
			} else {
				$packagelist[$key]['options']['remove']=true;
			}

			$file=abs_path.$package['package'].'.'.$package['release'].'/index.php';
			if (file_exists($file)) {
				$packagelist[$key]['options']['index']=true;
			}
		}

		uasort($packagelist, array($this,'comparePackageList'));
		return $packagelist;
	}

	public function comparePackageList($a, $b) {
		return strcmp(strtolower($a['key']), strtolower($b['key']));
	}

	public function updatePackageList($serverlist='') {
		$name='packagelist_'.$serverlist;
		$name_list='serverlist';
		if (!isset($this->data[$name])) {
			$this->readServerList($serverlist);
			foreach ($this->data[$name_list] as $serverlist_name=>$serverlist_value) {
				$server_data=$this->getConnectedServer($serverlist_name);
				if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
					$file=abs_path.'resources/json/packagelist/'.$serverlist_name.'.json';
					$data=$this->getUrlData($server_data['server_url'].'?action=server_packages');
					file_put_contents($file, $data);
					$this->data[$name][$serverlist_name]=json_decode($data, true);
				}
			}

			if ((isset($this->data[$name_list][$serverlist]['info']))&&(isset($this->data[$name_list][$serverlist]['info']['package']))) {
				osW_Tool::getInstance()->installPackage($this->data[$name_list][$serverlist]['info']['package'], 'stable', $serverlist);
			}
		}
		if ($serverlist!='') {
			return $this->data[$name][$serverlist];
		}
		return $this->data[$name];
	}

	public function getLicenseInfo() {
		$name='licenseinfo_';
		$name_list='serverlist';

		if (!isset($this->data[$name])) {
			$this->readServerList();
			foreach ($this->data[$name_list] as $serverlist_name=>$serverlist_value) {
				$server_data=$this->getConnectedServer($serverlist_name);
				if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
					$server_addr=$this->getUrlData($server_data['server_url'].'?action=license_server_addr');
					$server_name=$_SERVER['SERVER_NAME'];
					$this->data[$name][$serverlist_name]=array();
					$this->data[$name][$serverlist_name]['server_list']=$serverlist_value['info']['name'];
					$this->data[$name][$serverlist_name]['server_addr']=$server_addr;
					$this->data[$name][$serverlist_name]['server_name']=$server_name;
					$this->data[$name][$serverlist_name]['server_mac']=implode(';', $this->getMac());
					$this->data[$name][$serverlist_name]['licensekey']=sha1($serverlist_name.'#'.$this->data[$name][$serverlist_name]['server_name'].'#'.$this->data[$name][$serverlist_name]['server_addr'].'#'.$this->data[$name][$serverlist_name]['server_mac']);
					$this->data[$name][$serverlist_name]['licensekeydev']=sha1($serverlist_name.'#'.$this->data[$name][$serverlist_name]['server_name'].'#'.$this->data[$name][$serverlist_name]['server_mac']);
				}
			}
		}
		return $this->data[$name];
	}

	/**
	 *
	 * @return osW_Tool_Server
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>