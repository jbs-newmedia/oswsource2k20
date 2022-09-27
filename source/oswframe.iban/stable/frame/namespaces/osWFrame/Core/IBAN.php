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

class IBAN extends \PHP_IBAN\IBAN {

	use BaseStaticTrait;

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
	 * IBAN constructor.
	 *
	 * @param string $iban
	 */
	public function __construct(string $iban = '') {
		parent::__construct();
		$this->setIBAN($iban);
	}

	/**
	 * @param string $iban
	 */
	public function setIBAN(string $iban):void {
		$this->iban=$iban;
	}

	/**
	 * @return string
	 */
	public function getIBAN():string {
		return $this->iban;
	}

}

?>