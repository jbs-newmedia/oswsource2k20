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
 * @var \osWFrame\Core\DDM4 $this
 * @var array $options
 * @var array $_columns
 *
 */


if ((isset($options['name'])) && ($options['name'] !== '')) {
    $_columns[$options['name']] = [
        'name' => $options['name'],
        'order' => false,
        'search' => false,
    ];
}

$this->incCounter('list_view_elements');
