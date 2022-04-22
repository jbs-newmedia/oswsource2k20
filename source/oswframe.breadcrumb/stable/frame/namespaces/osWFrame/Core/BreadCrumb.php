<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

namespace osWFrame\Core;

class BreadCrumb {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=1;

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
	 * BreadCrumb Data
	 *
	 * @var array
	 */
	protected array $data=[];

	/**
	 * Zähler
	 *
	 * @var int
	 */
	protected int $count=0;

	/**
	 * BreadCrumb constructor.
	 */
	public function __construct() {

	}

	/**
	 * @param string $name
	 * @param string $module
	 * @param string $parameters
	 * @param array $options
	 * @return void
	 */
	public function add(string $name='', string $module='', string $parameters='', array $options=[]):void {
		if (($module=='')||($module=='default')) {
			$module=Settings::getStringVar('project_default_module');
		}
		if ($module=='current') {
			$module=Settings::getStringVar('frame_current_module');
		}
		$this->data[]=['name'=>$name, 'module'=>$module, 'parameters'=>$parameters, 'options'=>$options];
		$this->addCount();
	}

	/**
	 * @return void
	 */
	public function clear():void {
		$this->data=[];
	}

	/**
	 * @param $i
	 * @return bool
	 */
	public function removePosition($i):bool {
		if (isset($this->data[$i])) {
			unset($this->data[$i]);

			return true;
		}

		return false;
	}

	/**
	 * @param int $id
	 * @return array|null
	 */
	public function get(int $id=0):?array {
		if ($id>0) {
			if (isset($this->data[$id])) {
				return $this->data[$id];
			} else {
				return null;
			}
		}

		return $this->data;
	}

	/**
	 * @return array
	 */
	public function getReverse():array {
		$r_array=$this->data;
		krsort($r_array);

		return $r_array;
	}

	/**
	 * @return void
	 */
	protected function addCount():void {
		$this->count++;
	}

	/**
	 * @return void
	 */
	protected function clearCount():void {
		$this->count=0;
	}

	/**
	 * @return int
	 */
	public function getCount():int {
		return $this->count;
	}

}

?>