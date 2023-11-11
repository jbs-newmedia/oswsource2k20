<?php

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

declare(strict_types=0);

use osWFrame\Api\Database\DBStruct;
use osWFrame\Core\Result;

$DBStruct = new DBStruct();
$DBStruct->runStruct();

$osW_Result->setSuccessMessage('Datenbank-Struktur wurde aktualisiert.');
