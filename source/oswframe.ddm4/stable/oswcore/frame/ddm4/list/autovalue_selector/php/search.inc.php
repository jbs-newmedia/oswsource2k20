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
 * @var string $key
 * @var array $search
 * @var array $ddm_search_case_array
 *
 */


$ddm_search_case_array[] = $this->getGroupOption('alias', 'database') . '.' . $key . ' LIKE ' . $this::getConnection(
)->escapeString('%' . $search['value'] . '%');
