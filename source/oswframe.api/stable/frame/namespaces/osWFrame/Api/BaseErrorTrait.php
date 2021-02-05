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

namespace osWFrame\Api;

trait BaseErrorTrait {

	/**
	 * @var bool
	 */
	private bool $error=false;

	/**
	 * @var string
	 */
	private string $error_message='';

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
	public function getError() {
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

}

?>