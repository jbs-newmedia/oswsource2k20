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

use osWFrame\Core\Settings;

Settings::setStringVar('frame_default_engine', 'scripts');
Settings::setStringVar('frame_default_output', 'scripts');
if (Settings::catchIntValue('session_enabled', 0, 'pg') === 0) {
    Settings::setBoolVar('session_enabled', false);
}
