<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var \osWFrame\Core\Result $osW_Result
 *
 */

use osWFrame\Api\Database\DBStruct;
use osWFrame\Core\Result;
use osWFrame\Core\Settings;

$DBStruct = new DBStruct();
$DBStruct->updateColumn(
    Settings::catchStringValue('table'),
    Settings::catchStringValue('name'),
    Settings::catchStringValue('type'),
    Settings::catchIntValue('length'),
    Settings::catchIntValue('position'),
    Settings::catchStringValue('collation'),
    Settings::catchStringValue('options'),
    Settings::catchIntValue('setnull'),
    Settings::catchIntValue('autoincrement')
);

$osW_Result->setError($DBStruct->getError());
$osW_Result->setErrorMessage($DBStruct->getErrorMessage());
$osW_Result->setSuccessMessage($DBStruct->getSuccessMessage());
