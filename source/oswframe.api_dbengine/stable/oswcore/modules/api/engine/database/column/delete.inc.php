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
$DBStruct->deleteColumn(Settings::catchStringValue('table'), Settings::catchStringValue('name'));

$osW_Result->setError($DBStruct->getError());
$osW_Result->setErrorMessage($DBStruct->getErrorMessage());
$osW_Result->setSuccessMessage($DBStruct->getSuccessMessage());
