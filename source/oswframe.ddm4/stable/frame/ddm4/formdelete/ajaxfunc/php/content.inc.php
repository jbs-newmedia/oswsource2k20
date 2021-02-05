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

$ajax=$this->getDeleteElementOption($element, 'data');

$js_code='';
$css_code='';
$css_init=[];
$js_ajax=[];
$js_ajax_init=[];
$js_rules=[];
$js_clear=[];
$_function_ajax=[];
$_function_elements=[];
$elements_ok=[];

foreach ($this->getDeleteElements() as $element=>$options) {
	if (!in_array($element, $ajax['init'])) {
		$css_init[$element]='.ddm_element_'.$element.' {display:none;}';
		$js_clear[$element]='elements["'.$element.'"]=0;';
	} else {
		$elements_ok[$element]=$element;
	}
}

if (\osWFrame\Core\Settings::getAction()=='dodelete') {
	foreach ($ajax['logic'] as $group=>$group_data) {
		$ajax['logic'][$group]['jsrule']=[];

		$rule=true;
		foreach ($group_data['rule'] as $rule_key=>$rule_values) {
			$_rule=false;
			foreach ($rule_values as $rule_value) {
				$ajax['logic'][$group]['jsrule'][$rule_key][]=$rule_value;
				if ((string) $this->getDoDeleteElementStorage($rule_key)==(string) $rule_value) {
					$_rule=true;
				}
			}

			if ($_rule!==true) {
				$rule=false;
			}
		}

		if ($rule===true) {
			foreach ($group_data['view'] as $view_value) {
				$js_ajax_init[$view_value]='$(".ddm_element_'.$view_value.'").fadeIn(0);';
				$elements_ok[$view_value]=$view_value;
			}
		}
	}

	foreach ($this->getDeleteElements() as $element=>$options) {
		if (!isset($elements_ok[$element])) {
			$this->setDeleteElementValidation($element, 'module', 'ajaxfunc');
			// ???????
			$this->setDeleteElementValue($element, 'name', '');
		}
	}
} else {
	foreach ($ajax['logic'] as $group=>$group_data) {
		$ajax['logic'][$group]['jsrule']=[];

		$rule=true;
		foreach ($group_data['rule'] as $rule_key=>$rule_values) {
			$_rule=false;
			foreach ($rule_values as $rule_value) {
				$ajax['logic'][$group]['jsrule'][$rule_key][]=$rule_value;
				if ((string) $this->getDeleteElementStorage($rule_key)==(string) $rule_value) {
					$_rule=true;
				}
			}

			if ($_rule!==true) {
				$rule=false;
			}
		}

		if ($rule===true) {
			foreach ($group_data['view'] as $view_value) {
				$js_ajax_init[$view_value]='$(".ddm_element_'.$view_value.'").fadeIn(0);';
			}
		}
	}
}

foreach ($ajax['logic'] as $group=>$group_data) {
	foreach ($group_data['rule'] as $rule_key=>$rule_value) {
		$this->parseElementPHP('datatableajax', $this->getDeleteElementValue($rule_key, 'module'), ['module'=>$this->getDeleteElementValue($rule_key, 'module'), 'rule_key'=>$rule_key, 'rule_value'=>$rule_value]);
		$datatableajax_rule=$this->getElementStorage('datatableajax_rule');
		$ajax['logic'][$group]['jsrule'][$rule_key]=$datatableajax_rule;
		$datatableajax_selected=$this->getElementStorage('datatableajax_selected');
		$js_ajax[$rule_key]=$datatableajax_selected;
	}

	$ajax['logic'][$group]['jsrule']='('.implode(' && ', $ajax['logic'][$group]['jsrule']).')';

	$_function_ajax[]='if ('.$ajax['logic'][$group]['jsrule'].') {';
	foreach ($ajax['logic'][$group]['view'] as $element) {
		$_function_elements[$element]='elements["'.$element.'"]=0;';
		$_function_ajax[]='	elements["'.$element.'"]=1;';
		if (isset($ajax['logic'][$group]['reset'])) {
			foreach ($ajax['logic'][$group]['reset'] as $reset_key) {
				$this->parseElementPHP('datatableajax', $this->getDeleteElementValue($reset_key, 'module'), ['module'=>$this->getDeleteElementValue($reset_key, 'module'), 'reset_key'=>$reset_key]);
				$_function_ajax[]=$this->getElementStorage('datatableajax_reset');
			}
		}
		if (isset($ajax['logic'][$group]['set'])) {
			foreach ($ajax['logic'][$group]['set'] as $set_key=>$set_value) {
				$this->parseElementPHP('datatableajax', $this->getDeleteElementValue($set_key, 'module'), ['module'=>$this->getDeleteElementValue($set_key, 'module'), 'set_key'=>$set_key, 'set_value'=>$set_value]);
				$_function_ajax[]=$this->getElementStorage('datatableajax_set');
			}
		}
	}
	$_function_ajax[]='}';
}

foreach ($css_init as $element=>$code) {
	if (!isset($js_ajax_init[$element])) {
		$css_code.=$code."\n";
	}
}

$js_code.='

function ddm4formular_'.$ddm_group.'() {
	var elements={};
	'.implode("\n	", $js_clear).'

	'.implode("\n	", $_function_ajax).'

	$.each(elements, function(key, value) {
		if (value==1) {
			$(".ddm_element_"+key).fadeIn(0);
		} else {
			$(".ddm_element_"+key).fadeOut(0);
		}
	});
}

$(window).on("load", function (e) {
	'.implode("\n	", $js_ajax).'
});

';

$this->getTemplate()->addJSCodeHead($js_code);
$this->getTemplate()->addCSSCodeHead($css_code);

?>