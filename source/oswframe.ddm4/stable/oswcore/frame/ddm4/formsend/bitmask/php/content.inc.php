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
 * @var string $element
 * @var \osWFrame\Core\DDM4 $this
 *
 */

use osWFrame\Core\Settings;

if (Settings::getAction() === 'dosend') {
    $bitmask = '';
    $data = $this->getSendElementOption($element, 'data');
    ksort($data);
    $i = 0;
    foreach ($data as $key => $value) {
        if ($i < $key) {
            while ($i < $key) {
                $bitmask .= '0';
                $i++;
            }
        }
        $_value = Settings::catchValue($element . '_' . $key, 0, 'p');
        if ($_value === 1) {
            $bitmask .= '1';
        } else {
            $bitmask .= '0';
        }
        $i++;
    }
    $this->setDoSendElementStorage($element, $bitmask);
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');
