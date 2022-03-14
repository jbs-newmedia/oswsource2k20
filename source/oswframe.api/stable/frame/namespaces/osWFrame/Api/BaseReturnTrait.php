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

trait BaseReturnTrait {

	/**
	 * @var bool
	 */
	protected bool $error=false;

	/**
	 * @var string
	 */
	protected string $error_message='';

	/**
	 * @var string
	 */
	protected string $success_message='';

	/**
	 * @param bool $error
	 * @return bool
	 */
	public function setError(bool $error):bool {
		$this->error=$error;

		return true;
	}

	/**
	 * @return bool
	 */
	public function getError():bool {
		return $this->error;
	}

	/**
	 * @param string $error_message
	 * @return bool
	 */
	public function setErrorMessage(string $error_message):bool {
		$this->error_message=$error_message;

		return true;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage():string {
		return $this->error_message;
	}

	/**
	 * @param string $success_message
	 * @return bool
	 */
	public function setSuccessMessage(string $success_message):bool {
		$this->success_message=$success_message;

		return true;
	}

	/**
	 * @return string
	 */
	public function getSuccessMessage():string {
		return $this->success_message;
	}

}

?>