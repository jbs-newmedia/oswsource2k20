<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), JBS New Media UG
 * @package JBS New Media - Synchronize
 * @link http://jbs-newmedia.de
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */
class osW_Tool_Session extends osW_Tool_Object {

	public $data=array();

	function __construct() {
		$this->clear();
		if ($this->load()===false) {
			$this->check();
		}
	}

	function __destruct() {
		$this->save(true);
	}

	private function setNullSession() {
		$this->data=array();
	}

	public function start() {
		$this->setNullSession();
		$this->data['id']=md5(microtime(true).$_SERVER['REMOTE_ADDR'].time().$_SERVER['HTTP_USER_AGENT']);
		$this->data['addr']=$_SERVER['REMOTE_ADDR'];
		$this->data['ua']=$_SERVER['HTTP_USER_AGENT'];
		$this->data['time']=time();
		$this->data['uri']=$_SERVER['REQUEST_URI'];
		$this->save();
	}

	public function getId() {
		if (!isset($this->data['id'])) {
			return '';
		}
		return $this->data['id'];
	}

	public function load() {
		if ((!isset($_GET['session']))&&((!isset($_POST['session'])))) {
			return false;
		}
		if (isset($_POST['session'])) {
			$id=$_POST['session'];
		} else {
			$id=$_GET['session'];
		}

		if (!preg_match('/^[a-f0-9]{32}$/', $id)) {
			return false;
		}

		$filename=abs_path.'resources/session/'.$id;
		if (!file_exists($filename)) {
			return false;
		}

		$this->data=unserialize(file_get_contents($filename));
		return true;
	}

	public function save($check=false) {
		$filename=abs_path.'resources/session/'.$this->getId();
		if ($check===true) {
			if (!file_exists($filename)) {
				return false;
			}
		}
		file_put_contents($filename, serialize($this->data));
		return true;
	}

	public function delete() {
		$filename=abs_path.'resources/session/'.$this->getId();
		if (file_exists($filename)) {
			return unlink($filename);
		}
		return true;
	}

	public function clear() {
		$exclude_list=array('.','..','.htaccess');
		$directories=array_diff(scandir(abs_path.'resources/session/'), $exclude_list);
		foreach ($directories as $file) {
			$filename=abs_path.'resources/session/'.$file;
			if (filemtime($filename)<time()-(60*30)) {
				unlink($filename);
			}
		}
	}

	public function check() {
		if ((!isset($_GET['session']))&&((!isset($_POST['session'])))) {
			$this->start();
			return false;
		}

		if (isset($_POST['session'])) {
			$id=$_POST['session'];
		} else {
			$id=$_GET['session'];
		}

		if ((@$this->data['id']==$id)&&($this->data['addr']==$_SERVER['REMOTE_ADDR'])&&($this->data['ua']==$_SERVER['HTTP_USER_AGENT'])) {
			return true;
		}
		$this->start();
		return false;
	}

	public function setTool($tool, $key, $val) {
		if (!isset($this->data['values_'.$tool])) {
			$this->data['values_'.$tool]=array();
		}
		$this->data['values_'.$tool][$key]=$val;
		$this->save();
	}

	public function getTool($tool, $key) {
		if (!isset($this->data['values_'.$tool][$key])) {
			return '';
		}
		return $this->data['values_'.$tool][$key];
	}

	public function set($key, $val) {
		if (!isset($this->data['values'])) {
			$this->data['values']=array();
		}
		$this->data['values'][$key]=$val;
		$this->save();
	}

	public function get($key) {
		if (!isset($this->data['values'][$key])) {
			return '';
		}
		return $this->data['values'][$key];
	}

	/**
	 *
	 * @return JBSNM_Sync_Session
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>