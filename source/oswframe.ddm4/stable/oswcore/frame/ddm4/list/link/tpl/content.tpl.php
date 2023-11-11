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
 * @var array $view_data
 *
 */

use osWFrame\Core\HTML;

$__link = $this->getListElementOption($element, 'link');

$_link = '<a' . ((isset($__link['target'])) ? ' target="' . $__link['target'] . '"' : '') . ' href="' . $this->getTemplate(
)->buildhrefLink(
    ((isset($__link['module'])) ? $__link['module'] : $this->getDirectModule()),
    $this->getGroupOption('index', 'database') . '=' . $this->getListStorageValue(
        $this->getGroupOption('index', 'database')
    ) . ((isset($__link['parameter'])) ? '&' . $__link['parameter'] : '')
) . '">' . (HTML::outputString($this->getListStorageValue($this->getListElementValue($element, 'name')))) . '</a>';

$view_data[$this->getListElementValue($element, 'name')] = $_link;
