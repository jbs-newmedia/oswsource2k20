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

use osWFrame\Core\Filesystem;
use osWFrame\Core\IconCreator;
use osWFrame\Core\Network;
use osWFrame\Core\Settings;

$file = Settings::getStringVar('favicon_file');
$sizes = Settings::getArrayVar('favicon_sizes');
$filename = Settings::getStringVar('settings_abspath') . $file;
if (Filesystem::existsFile($filename)) {
    if (IconCreator::existsCache($file, $sizes) !== true) {
        $osW_IconCreator = new IconCreator($file, $sizes);
        $osW_IconCreator->writeCache($file, $sizes);
    }

    Network::sendHeader('Content-Type: image/vnd.microsoft.icon');
    echo IconCreator::readCache($file, $sizes);
} else {
    Network::sendHeader('Content-Type: image/vnd.microsoft.icon');
    echo file_get_contents(Settings::getStringVar('settings_abspath') . 'favicon.ico');
}
