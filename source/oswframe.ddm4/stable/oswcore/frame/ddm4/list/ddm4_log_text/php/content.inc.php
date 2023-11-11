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
 * @var array $_order
 * @var array $_search
 * @var array $_hidden
 *
 */


if ((isset($options['name'])) && ($options['name'] !== '')) {
    $_columns[$options['name']] = [
        'name' => $options['name'],
        'order' => (isset($_order[$options['name']])) ? true : false,
        'search' => (isset($_search[$options['name']])) ? true : false,
        'hidden' => (isset($_hidden[$options['name']])) ? true : false,
    ];
}

$this->incCounter('list_view_elements');
