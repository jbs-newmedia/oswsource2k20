<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */
class osW_Tool_Form extends osW_Tool_Object {

	/* PROPERTIES */
	private $hidden_fields=array();

	private $forms=array();

	private $formerrors=array();

	private $count=array();

	/* METHODS CORE */
	public function __construct() {
	}

	public function __destruct() {
	}

	/* METHODS */

	// HiddenField
	function drawHiddenField($name, $value, $reinsert=false) {
		$this->hidden_fields[]=array('name'=>$name,'value'=>$value,'reinsert'=>$reinsert);
	}

	// TextField
	function drawTextField($name, $value='', $options=array ()) {
		$options['input_type']='text';
		return $this->createInputField($name, $value, $options);
	}

	// PasswordField
	function drawPasswordField($name, $value='', $options=array ()) {
		$options['input_type']='password';
		return $this->createInputField($name, $value, $options);
	}

	// FileField
	function drawFileField($name, $value='', $options=array ()) {
		$options['input_type']='file';
		return $this->createInputField($name, $value, $options);
	}

	// TextareaField
	function drawTextareaField($name, $value='', $options=array ()) {
		$options['input_type']='textarea';
		return $this->createTextField($name, $value, $options);
	}

	// RadioField
	function drawRadioField($name, $value, $selected='', $options=array ()) {
		$options['input_type']='radio';
		return $this->createSelectionField($name, $value, $selected, $options);
	}

	// CheckboxField
	function drawCheckboxField($name, $value, $selected='', $options=array ()) {
		$options['input_type']='checkbox';
		return $this->createSelectionField($name, $value, $selected, $options);
	}

	// SelectField
	function drawSelectField($name, $values, $selected='', $options=array ()) {
		$options['input_type']='select';
		return $this->createListField($name, $values, $selected, $options);
	}

	// ListField
	function drawListField($name, $values, $selected='', $options=array ()) {
		$options['input_type']='list';
		return $this->createListField($name, $values, $selected, $options);
	}

	// MultipleListField
	function drawMultipleListField($name, $values, $selected='', $options=array ()) {
		$options['input_type']='multilist';
		return $this->createListField($name, $values, $selected, $options);
	}

	// drawSubmit
	function drawSubmit($name, $value, $options=array ()) {
		$options['input_type']='submit';
		return $this->createSubmit($name, $value, $options);
	}

	// drawReset
	function drawReset($name, $value, $options=array ()) {
		$options['input_type']='reset';
		return $this->createSubmit($name, $value, $options);
	}

	// drawImageSubmit
	function drawImageSubmit($name, $value, $options=array ()) {
		$options['input_type']='image';
		return $this->createSubmit($name, $value, $options);
	}

	private function createInputField($name, $value, $options) {
		$field='';

		if (!isset($options['input_type'])) {
			$options['input_type']='text';
		} else {
			switch ($options['input_type']) {
				case 'password' :
					break;
				case 'file' :
					break;
				case 'text' :
				default :
					$options['input_type']='text';
			}
		}

		if (!isset($options['input_addid'])) {
			$options['input_addid']=true;
		}

		if (!isset($options['reinsert_value'])) {
			$options['reinsert_value']=true;
		}

		if (!isset($options['input_error'])) {
			$options['input_error']=true;
		}

		if ((!isset($options['input_id']))&&($options['input_addid']===true)) {
			$options['input_id']=osW_Tool_Template::getInstance()->outputString($name);
		}

		$field.='<input type="'.$options['input_type'].'" name="'.osW_Tool_Template::getInstance()->outputString($name).'"';

		if ($options['input_addid']===true) {
			$field.=' id="'.$options['input_id'].'"';
		}

		if (isset($options['input_class'])) {
			if ($options['input_error']===true) {
				if ($this->getFormError($name)===true) {
					$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).' oswerror"';
				} else {
					$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).'"';
				}
			} else {
				$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).'"';
			}
		} else {
			if ($this->getFormError($name)===true) {
				$field.=' class="oswerror"';
			}
		}

		if ($options['reinsert_value']===true) {
			if (isset($_GET[$name])) {
				$value=$_GET[$name];
			} elseif (isset($_POST[$name])) {
				$value=$_POST[$name];
			}
		}

		$value=trim($value);

		if (strlen($value)>0) {
			$field.=' value="'.osW_Tool_Template::getInstance()->outputString($value).'"';
		}

		if (isset($options['input_parameter'])) {
			$field.=' '.$options['input_parameter'];
		}

		$field.='/>';

		return $field;
	}

	private function createTextField($name, $value, $options) {
		$field='';

		if (!isset($options['input_type'])) {
			$options['input_type']='textarea';
		} else {
			switch ($options['input_type']) {
				case 'textarea' :
				default :
					$options['input_type']='textarea';
			}
		}

		if (!isset($options['input_addid'])) {
			$options['input_addid']=true;
		}

		if (!isset($options['reinsert_value'])) {
			$options['reinsert_value']=true;
		}

		if (!isset($options['input_error'])) {
			$options['input_error']=true;
		}

		if ((!isset($options['input_id']))&&($options['input_addid']===true)) {
			$options['input_id']=osW_Tool_Template::getInstance()->outputString($name);
		}

		$field.='<textarea name="'.osW_Tool_Template::getInstance()->outputString($name).'"';

		if ($options['input_addid']===true) {
			$field.=' id="'.$options['input_id'].'"';
		}
		$field.=' cols="0" rows="0"';
		if (isset($options['input_class'])) {
			if ($options['input_error']===true) {
				if ($this->getFormError($name)===true) {
					$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).' oswerror"';
				} else {
					$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).'"';
				}
			} else {
				$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).'"';
			}
		} else {
			if ($this->getFormError($name)===true) {
				$field.=' class="oswerror"';
			}
		}

		if ($options['reinsert_value']===true) {
			if (isset($_GET[$name])) {
				$value=$_GET[$name];
			} elseif (isset($_POST[$name])) {
				$value=$_POST[$name];
			}
		}

		$value=trim($value);

		if (isset($options['input_parameter'])) {
			$field.=' '.$options['input_parameter'];
		}

		$field.='>';

		if (strlen($value)>0) {
			$field.=htmlspecialchars($value);
		}

		$field.='</textarea>';

		return $field;
	}

	private function createSelectionField($name, $value, $selected, $options) {
		$field='';

		if (!isset($options['input_type'])) {
			$options['input_type']='checkbox';
		} else {
			switch ($options['input_type']) {
				case 'radio' :
					break;
				case 'checkbox' :
				default :
					$options['input_type']='checkbox';
			}
		}

		if (!isset($this->count[$name])) {
			$this->count[$name]=0;
		} else {
			$this->count[$name]++;
		}

		if (!isset($options['input_addid'])) {
			$options['input_addid']=true;
		}

		if (!isset($options['reinsert_value'])) {
			$options['reinsert_value']=true;
		}

		if (!isset($options['input_error'])) {
			$options['input_error']=true;
		}

		if ((!isset($options['input_id']))&&($options['input_addid']===true)) {
			$options['input_id']=osW_Tool_Template::getInstance()->outputString($name).$this->count[$name];
		}

		$field.='<input type="'.$options['input_type'].'" name="'.osW_Tool_Template::getInstance()->outputString($name).'"';

		if ($options['input_addid']===true) {
			$field.=' id="'.$options['input_id'].'"';
		}

		if (isset($options['input_class'])) {
			if ($options['input_error']===true) {
				if ($this->getFormError($name)===true) {
					$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).' oswerror"';
				} else {
					$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).'"';
				}
			} else {
				$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).'"';
			}
		} else {
			if ($this->getFormError($name)===true) {
				$field.=' class="oswerror"';
			}
		}

		if ($options['reinsert_value']===true) {
			if (isset($_GET[$name])) {
				$selected=$_GET[$name];
			} elseif (isset($_POST[$name])) {
				$selected=$_POST[$name];
			}
		} else {
		}

		$value=trim($value);

		if (strlen($value)>0) {
			$field.=' value="'.osW_Tool_Template::getInstance()->outputString($value).'"';
		}

		if ($selected==$value) {
			$field.=' checked="checked"';
		}

		if (isset($options['input_parameter'])) {
			$field.=' '.$options['input_parameter'];
		}

		$field.='/>';

		return $field;
	}

	private function createListField($name, $values, $selected, $options) {
		$field='';

		if (!isset($options['input_type'])) {
			$options['input_type']='select';
		} else {
			switch ($options['input_type']) {
				case 'list' :
					if (!isset($options['input_listsize'])) {
						$options['input_listsize']=count($values);
						if (!isset($options['input_parameter'])) {
							$options['input_parameter']=' size="'.$options['input_listsize'].'"';
						} else {
							$options['input_parameter'].=' size="'.$options['input_listsize'].'"';
						}
					}
					break;
				case 'multilist' :
					if (!isset($options['input_parameter'])) {
						$options['input_parameter']=' multiple="multiple"';
					} else {
						$options['input_parameter'].=' multiple="multiple"';
					}
					break;
				case 'select' :
				default :
					$options['input_type']='select';
			}
		}

		if (!isset($options['input_addid'])) {
			$options['input_addid']=true;
		}

		if (!isset($options['reinsert_value'])) {
			$options['reinsert_value']=true;
		}

		if (!isset($options['input_error'])) {
			$options['input_error']=true;
		}

		if ((!isset($options['input_id']))&&($options['input_addid']===true)) {
			$options['input_id']=osW_Tool_Template::getInstance()->outputString($name);
		}

		switch ($options['input_type']) {
			case 'multilist' :
				$field.='<select name="'.osW_Tool_Template::getInstance()->outputString($name).'[]"';
				break;
			case 'list' :
			case 'select' :
			default :
				$field.='<select name="'.osW_Tool_Template::getInstance()->outputString($name).'"';
		}

		if ($options['input_addid']===true) {
			$field.=' id="'.$options['input_id'].'"';
		}

		if (isset($options['input_parameter'])) {
			$field.=' '.$options['input_parameter'];
		}

		if (isset($options['input_class'])) {
			if ($options['input_error']===true) {
				if ($this->getFormError($name)===true) {
					$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).' oswerror"';
				} else {
					$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).'"';
				}
			} else {
				$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).'"';
			}
		} else {
			if ($this->getFormError($name)===true) {
				$field.=' class="oswerror"';
			}
		}

		if ($options['reinsert_value']===true) {
			if (isset($_GET[$name])) {
				$selected=$_GET[$name];
			} elseif (isset($_POST[$name])) {
				$selected=$_POST[$name];
			}
		}

		$field.='>';

		if ($options['input_type']=='multilist') {
			if (is_array($selected)) {
				$selected=array_flip($selected);
				foreach ($values as $key=>$value) {
					if ((is_array($value))&&(isset($value['value']))&&(isset($value['text']))) {
						$key=$value['value'];
						$value=$value['text'];
					}
					if (isset($selected[$key])) {
						$field.='<option value="'.osW_Tool_Template::getInstance()->outputString($key).'" selected="selected">'.osW_Tool_Template::getInstance()->outputString($value).'&nbsp;</option>';
					} else {
						$field.='<option value="'.osW_Tool_Template::getInstance()->outputString($key).'">'.osW_Tool_Template::getInstance()->outputString($value).'&nbsp;</option>';
					}
				}
			} else {
				foreach ($values as $key=>$value) {
					if ((is_array($value))&&(isset($value['value']))&&(isset($value['text']))) {
						$key=$value['value'];
						$value=$value['text'];
					}
					if ($selected==$key) {
						$field.='<option value="'.osW_Tool_Template::getInstance()->outputString($key).'" selected="selected">'.osW_Tool_Template::getInstance()->outputString($value).'&nbsp;</option>';
					} else {
						$field.='<option value="'.osW_Tool_Template::getInstance()->outputString($key).'">'.osW_Tool_Template::getInstance()->outputString($value).'&nbsp;</option>';
					}
				}
			}
		} else {
			$_selected=false;
			foreach ($values as $key=>$value) {
				if ((is_array($value))&&(isset($value['value']))&&(isset($value['text']))) {
					$key=$value['value'];
					$value=$value['text'];
				}
				if ((strval($selected)==strval($key))&&($_selected!==true)) {
					$_selected=true;
					$field.='<option value="'.osW_Tool_Template::getInstance()->outputString($key).'" selected="selected">'.osW_Tool_Template::getInstance()->outputString($value).'&nbsp;</option>';
				} else {
					$field.='<option value="'.osW_Tool_Template::getInstance()->outputString($key).'">'.osW_Tool_Template::getInstance()->outputString($value).'&nbsp;</option>';
				}
			}
		}

		$field.='</select>';

		return $field;
	}

	private function createSubmit($name, $value, $options) {
		$field='';

		if (!isset($options['input_type'])) {
			$options['input_type']='submit';
		} else {
			switch ($options['input_type']) {
				case 'image' :
					if (!isset($options['input_image'])) {
						$options['input_type']='submit';
					} else {
						if (!isset($options['input_parameter'])) {
							$options['input_parameter']=' src="'.osW_Template::getInstance()->getImagePath($options['input_image']).'"';
						} else {
							$options['input_parameter'].=' src="'.osW_Template::getInstance()->getImagePath($options['input_image']).'"';
						}
					}
					break;
				case 'reset' :
					$options['input_type']='reset';
					break;
				case 'submit' :
				default :
					$options['input_type']='submit';
			}
		}

		if (!isset($options['input_addid'])) {
			$options['input_addid']=true;
		}

		if (!isset($options['input_error'])) {
			$options['input_error']=true;
		}

		if ((!isset($options['input_id']))&&($options['input_addid']===true)) {
			$options['input_id']=osW_Tool_Template::getInstance()->outputString($name);
		}

		$field.='<input type="'.$options['input_type'].'" name="'.osW_Tool_Template::getInstance()->outputString($name).'"';

		if ($options['input_addid']===true) {
			$field.=' id="'.$options['input_id'].'"';
		}

		if (isset($options['input_class'])) {
			if ($options['input_error']===true) {
				if ($this->getFormError($name)===true) {
					$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).' oswerror"';
				} else {
					$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).'"';
				}
			} else {
				$field.=' class="'.osW_Tool_Template::getInstance()->outputString($options['input_class']).'"';
			}
		} else {
			if ($this->getFormError($name)===true) {
				$field.=' class="oswerror"';
			}
		}

		if ($options['input_type']=='image') {
		} else {
			$field.=' title="'.osW_Tool_Template::getInstance()->outputString($value).'" value="'.osW_Tool_Template::getInstance()->outputString($value).'"';
		}

		if (isset($options['input_parameter'])) {
			$field.=' '.$options['input_parameter'];
		}

		$field.='/>';

		return $field;
	}

	function formStart($name, $action='current', $parameters='', $options=array ()) {
		if (!isset($options['form_method'])) {
			$options['form_method']='post';
		} else {
			switch ($options['form_method']) {
				case 'get' :
					break;
				case 'post' :
				default :
					$options['form_method']='post';
			}
		}

		if (!isset($options['form_parameter'])) {
			$options['form_parameter']='';
		}

		if (!isset($options['input_addid'])) {
			$options['input_addid']=true;
		}

		if ((!isset($options['input_id']))&&($options['input_addid']===true)) {
			$options['input_id']=osW_Tool_Template::getInstance()->outputString($name);
		}

		$form='<form name="'.osW_Tool_Template::getInstance()->outputString($name).'"';
		if ($options['input_addid']===true) {
			$form.=' id="'.$options['input_id'].'"';
		}
		$form.=' action="'.osW_Tool_Template::getInstance()->buildhrefLink($action, $parameters).'"';
		$form.=' method="'.osW_Tool_Template::getInstance()->outputString($options['form_method']).'"';
		$form.=' '.$options['form_parameter'];
		$form.='>';

		return $form;
	}

	function formEnd() {
		$fieldstring='';

		if ((is_array($this->hidden_fields))&&(sizeof($this->hidden_fields)>0)) {
			foreach ($this->hidden_fields as $field) {
				if ($field['reinsert']===true) {
					if (isset($_GET[$field['name']])) {
						$field['value']=$_GET[$field['name']];
					} elseif (isset($_POST[$field['name']])) {
						$field['value']=$_POST[$field['name']];
					}
				}
				$fieldstring.='<input type="hidden" name="'.osW_Tool_Template::getInstance()->outputString($field['name']).'"';
				if (isset($field['value'])) {
					$fieldstring.=' value="'.osW_Tool_Template::getInstance()->outputString($field['value']).'"';
				}
				if (!empty($field['parameters'])) {
					$fieldstring.=' '.$field['parameters'];
				}
				$fieldstring.='/>';
			}
		}
		$this->hidden_fields=array();
		return $fieldstring.'</form>';
	}

	function addFormError($formfield) {
		$this->formerrors[$formfield]=true;
	}

	function getFormError($formfield) {
		if (isset($this->formerrors[$formfield])) {
			return true;
		}
		return false;
	}

	/**
	 *
	 * @return osW_Tool_Form
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>