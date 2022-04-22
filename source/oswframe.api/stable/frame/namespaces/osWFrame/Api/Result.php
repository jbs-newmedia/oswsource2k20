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

namespace osWFrame\Api;

use \osWFrame\Core as osWFrame;

class Result {

	use osWFrame\BaseConnectionTrait;
	use BaseReturnTrait;

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
	 * @var array
	 */
	protected array $result=[];

	/**
	 * Result constructor.
	 */
	public function __construct() {

	}

	/**
	 * @param array $result
	 * @return bool
	 */
	public function setResult(array $result):bool {
		$this->result=$result;

		return true;
	}

	/**
	 * @return array
	 */
	public function getResult():array {
		return $this->result;
	}

	/**
	 * @return array
	 */
	public function getData():array {
		return ['result'=>$this->getResult(), 'error'=>$this->getError(), 'error_message'=>$this->getErrorMessage(), 'success_message'=>$this->getSuccessMessage()];
	}

}

?>