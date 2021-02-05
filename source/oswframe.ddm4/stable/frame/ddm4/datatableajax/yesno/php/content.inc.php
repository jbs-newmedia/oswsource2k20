<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$datatableajax_rule='($("input[name=\''.$values['rule_key'].'\']:checked").val()=='.implode(' || $("input[name=\''.$values['rule_key'].'\']:checked").val()==', $values['rule_value']).')';
$this->setElementStorage('datatableajax_rule', $datatableajax_rule);

$datatableajax_selected='$("input[name=\''.$values['rule_key'].'\']").change(function(){ddm4formular_'.$ddm_group.'();});';
$this->setElementStorage('datatableajax_selected', $datatableajax_selected);

?>