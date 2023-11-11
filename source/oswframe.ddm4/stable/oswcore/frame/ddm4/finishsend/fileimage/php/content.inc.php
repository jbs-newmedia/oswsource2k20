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

use osWFrame\Core\Filesystem;
use osWFrame\Core\Settings;

if ($this->getDoSendElementStorage($element . $this->getSendElementOption($element, 'temp_suffix')) !== '') {
    rename(
        Settings::getStringVar('settings_abspath') . $this->getDoSendElementStorage(
            $element . $this->getSendElementOption($element, 'temp_suffix')
        ),
        Settings::getStringVar('settings_abspath') . $this->getDoSendElementStorage($element)
    );
    if (($this->getSendElementStorage($element) !== '') && ($this->getSendElementStorage(
        $element
    ) !== $this->getDoSendElementStorage($element))
    ) {
        if ($this->getGroupOption('enable_log') !== true) {
            Filesystem::unlink(Settings::getStringVar('settings_abspath') . $this->getSendElementStorage($element));
            $this->setSendElementStorage($element, '');
        }
    }
} elseif (Settings::catchValue($element . $this->getSendElementOption($element, 'delete_suffix'), '', 'p') === '1') {
    if ($this->getDoSendElementStorage($element) !== '') {
        if ($this->getGroupOption('enable_log') !== true) {
            Filesystem::unlink(Settings::getStringVar('settings_abspath') . $this->getDoSendElementStorage($element));
            $this->setDoSendElementStorage($element, '');
        }

        if ($this->getSendElementOption($element, 'store_name') === true) {
            $this->setDoSendElementStorage($element . '_name', '');
            $this->addDataElement($element . '_name', [
                'module' => 'hidden',
                'name' => $this->getSendElementValue($element, 'name') . '_name',
            ]);
        }

        if ($this->getSendElementOption($element, 'store_type') === true) {
            $this->setDoSendElementStorage($element . '_type', '');
            $this->addDataElement($element . '_type', [
                'module' => 'hidden',
                'name' => $this->getSendElementValue($element, 'name') . '_type',
            ]);
        }

        if ($this->getSendElementOption($element, 'store_size') === true) {
            $this->setDoSendElementStorage($element . '_size', 0);
            $this->addDataElement($element . '_size', [
                'module' => 'hidden',
                'name' => $this->getSendElementValue($element, 'name') . '_size',
            ]);
        }

        if ($this->getSendElementOption($element, 'store_md5') === true) {
            $this->setDoSendElementStorage($element . '_md5', '');
            $this->addDataElement($element . '_md5', [
                'module' => 'hidden',
                'name' => $this->getSendElementValue($element, 'name') . '_md5',
            ]);
        }

        if ($this->getSendElementOption($element, 'store_sha1') === true) {
            $this->setDoSendElementStorage($element . '_sha1', '');
            $this->addDataElement($element . '_sha1', [
                'module' => 'hidden',
                'name' => $this->getSendElementValue($element, 'name') . '_sha1',
            ]);
        }
    }
}
