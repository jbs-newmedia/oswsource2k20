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

namespace osWFrame\Core;

class SCSSCompiler extends \ScssPhp\ScssPhp\Compiler {

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
	 * SCSSCompiler constructor.
	 *
	 * @param array|null $cacheOptions
	 */
	public function __construct($cacheOptions=null) {
		parent::__construct($cacheOptions);
	}

	/**
	 * @param string $content
	 * @return string
	 */
	public function getCompressed(string $content):string {
		$this->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);

		return $this->compile($content);
	}

	/**
	 * @param string $content
	 * @return string
	 */
	public function getExpanded(string $content):string {
		$this->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::EXPANDED);

		return $this->compile($content);
	}

}

?>