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

trait BaseConnectionTrait {

	/**
	 * @var object|null
	 */
	private static ?object $connection=null;

	/**
	 * @param string $alias
	 * @return object
	 */
	public static function getConnection($alias='default'):object {
		if (self::$connection===null) {
			self::$connection=new \osWFrame\Core\Database($alias);
		}

		return self::$connection;
	}

}

?>