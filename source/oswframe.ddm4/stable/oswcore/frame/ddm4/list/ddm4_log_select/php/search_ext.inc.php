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
 * @var array $data
 * @var array $ddm_search_case_array
 * @var array $options
 *
 */


if ((isset($data[$element])) && (!in_array($data[$element], ['%'], true))) {
    if ($this->getSearchElementValidation($element, 'search_like') === false) {
        $ddm_search_case_array[] = $this->getGroupOption(
            'alias',
            'database'
        ) . '.' . $options['name'] . ' = ' . $this::getConnection()->escapeString($data[$element]) . '';
    } else {
        $ddm_search_case_array[] = $this->getGroupOption(
            'alias',
            'database'
        ) . '.' . $options['name'] . ' LIKE ' . $this::getConnection()->escapeString('%' . $data[$element] . '%') . '';
    }
}
