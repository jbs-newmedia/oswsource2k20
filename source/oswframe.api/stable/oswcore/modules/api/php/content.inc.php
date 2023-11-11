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

use osWFrame\Api\Controller;
use osWFrame\Api\Result;
use osWFrame\Core\Settings;

$osW_Controller = new Controller(
    Settings::catchStringValue('api', '', 'gp'),
    Settings::catchStringValue('section', '', 'gp'),
    Settings::catchStringValue('function', '', 'gp')
);

$dir = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_default_module'
) . \DIRECTORY_SEPARATOR . 'engine' . \DIRECTORY_SEPARATOR;
$file = $dir . $osW_Controller->getApi() . \DIRECTORY_SEPARATOR . $osW_Controller->getSection(
) . \DIRECTORY_SEPARATOR . $osW_Controller->getFunction() . '.inc.php';
$file_header = $dir . $osW_Controller->getApi() . \DIRECTORY_SEPARATOR . 'header.inc.php';

$osW_Result = new Result();

if (file_exists($file)) {
    $api_go = true;
    if (file_exists($file_header)) {
        require_once $file_header;
    }
    if ($api_go === true) {
        require_once $file;
    }
} else {
    $osW_Result->setError(true);
    $osW_Result->setErrorMessage(
        'Api not found. (- API: ' . $osW_Controller->getApi('undefined') . ' -|- SECTION: ' . $osW_Controller->getSection(
            'undefined'
        ) . ' -|- FUNCTION: ' . $osW_Controller->getFunction('undefined') . ' -)'
    );
}
