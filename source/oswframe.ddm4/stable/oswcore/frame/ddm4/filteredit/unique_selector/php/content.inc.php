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

if ($this->getDoEditElementStorage($element) !== '') {
    $database_where_string = '';
    $ddm_selector_array = $this->getGroupOption('selector', 'database');
    if (!empty($ddm_selector_array)) {
        $ar_values = [];
        foreach ($ddm_selector_array as $key => $value) {
            if (is_int($value) === true) {
                $ar_values[] = $this->getGroupOption('alias', 'database') . '.' . $key . '=' . $value;
            } else {
                $ar_values[] = $this->getGroupOption('alias', 'database') . '.' . $key . '=\'' . $value . '\'';
            }
        }
        $database_where_string .= ' AND (' . implode(' AND ', $ar_values) . ')';
    }

    $QcheckData = $this::getConnection($this->getGroupOption('connection', 'database'));
    $QcheckData->prepare(
        'SELECT :formdata_name: FROM :table: AS :alias: WHERE :formdata_name: LIKE :value: AND :name_index:!=:value_index: :where:'
    );
    $QcheckData->bindTable(':table:', $this->getGroupOption('table', 'database'));
    $QcheckData->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
    $QcheckData->bindRaw(
        ':name_index:',
        $this->getGroupOption('alias', 'database') . '.' . $this->getGroupOption('index', 'database')
    );
    if ($this->getGroupOption('db_index_type', 'database') === 'string') {
        $QcheckData->bindString(':value_index:', $this->getIndexElementStorage());
    } else {
        $QcheckData->bindInt(':value_index:', (int)($this->getIndexElementStorage()));
    }
    $QcheckData->bindRaw(
        ':formdata_name:',
        $this->getGroupOption('alias', 'database') . '.' . $this->getEditElementValue($element, 'name')
    );
    $QcheckData->bindString(':value:', $this->getDoEditElementStorage($element));
    $QcheckData->bindRaw(':where:', $database_where_string);
    if ($QcheckData->exec() > 0) {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_unique'),
                $this->getFilterElementStorage($element)
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}
