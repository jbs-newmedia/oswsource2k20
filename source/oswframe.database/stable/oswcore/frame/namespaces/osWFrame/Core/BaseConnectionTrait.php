<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace osWFrame\Core;

trait BaseConnectionTrait
{
    protected static ?array $connection = null;

    /**
     * @param string $alias
     *
     * @return static
     */
    public static function getConnection($alias = 'default'): Database
    {
        if ($alias === '') {
            $alias = 'default';
        }

        return new Database($alias);
    }

    /**
     * @param string $alias
     *
     * @return static
     */
    public static function getConnectionRef($alias = 'default'): Database
    {
        if ($alias === '') {
            $alias = 'default';
        }
        if ((!isset(self::$connection[$alias])) || (self::$connection[$alias] === null)) {
            self::$connection[$alias] = new Database($alias);
        }

        return self::$connection[$alias];
    }
}
