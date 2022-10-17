<?php

/**
 * This file is part of the VIS2:Manager package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:Manager
 * @link https://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

namespace osWFrame\Api\Importer;

use osWFrame\Api\BaseReturnTrait;
use osWFrame\Core\BaseConnectionTrait;
use osWFrame\Core\BaseStaticTrait;

class XML {

	use BaseStaticTrait;
	use BaseConnectionTrait;
	use BaseReturnTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=2;

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
	private const CLASS_EXTRA_VERSION='beta';

	private string $json='';

	/**
	 * XML constructor.
	 */
	public function __construct() {
	}

	public function setJSON(string $json) {
		$this->json=$json;
	}

	public function createStruct() {


	}


}

?>