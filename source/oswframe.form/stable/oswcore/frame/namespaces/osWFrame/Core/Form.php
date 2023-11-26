<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace osWFrame\Core;

class Form
{
    use BaseStaticTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 2;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 0;

    /**
     * Release-Version der Klasse.
     */
    private const CLASS_RELEASE_VERSION = 0;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    /**
     */
    protected array $hidden_fields = [];

    /**
     */
    protected array $errors = [];

    /**
     */
    protected array $count = [];

    /**
     */
    public function __construct()
    {
    }

    /**
     * Verstecktes-Feld
     *
     */
    public function drawHiddenField(string $name, string $value, bool $reinsert = false): string
    {
        $this->hidden_fields[] = ['name' => $name, 'value' => $value, 'reinsert' => $reinsert];

        return '';
    }

    /**
     */
    public function drawInputField(string $name, string $value = '', array $options = [], string $input_type = 'text'): string
    {
        $options['input_type'] = $input_type;

        return $this->createInputField($name, $value, $options);
    }

    /**
     * Text-Feld
     *
     */
    public function drawTextField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options);
    }

    /**
     * Passwort-Feld
     *
     */
    public function drawPasswordField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'password');
    }

    /**
     * Farb-Feld
     *
     */
    public function drawColorField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'color');
    }

    /**
     * Datum-Feld
     *
     */
    public function drawDateField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'date');
    }

    /**
     * Lokales Datum-Zeit-Feld
     *
     */
    public function drawDateTimeLocalField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'datetime-local');
    }

    /**
     * E-Mail-Feld
     *
     */
    public function drawEMailField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'email');
    }

    /**
     * Monat-Feld
     *
     */
    public function drawMonthField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'month');
    }

    /**
     * Zahlen-Feld
     *
     */
    public function drawNumberField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'number');
    }

    /**
     * Such-Feld
     *
     */
    public function drawSearchField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'search');
    }

    /**
     * Telefon-Feld
     *
     */
    public function drawTelField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'tel');
    }

    /**
     * Zeit-Feld
     *
     */
    public function drawTimeField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'time');
    }

    /**
     * Bereich-Feld
     *
     */
    public function drawRangeField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'range');
    }

    /**
     * Url-Feld
     *
     */
    public function drawUrlField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'url');
    }

    /**
     * Woche-Feld
     *
     */
    public function drawWeekField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'week');
    }

    /**
     * Datei-Feld
     *
     */
    public function drawFileField(string $name, string $value = '', array $options = []): string
    {
        return $this->drawInputField($name, $value, $options, 'file');
    }

    /**
     * Textarea-Feld
     *
     */
    public function drawTextareaField(string $name, string $value = '', array $options = []): string
    {
        return $this->createTextField($name, $value, $options);
    }

    /**
     * Radio-Feld
     *
     */
    public function drawRadioField(string $name, string $value, string $selected = '', array $options = []): string
    {
        $options['input_type'] = 'radio';

        return $this->createSelectionField($name, $value, $selected, $options);
    }

    /**
     * Checkbox-Feld
     *
     */
    public function drawCheckboxField(string $name, string $value, string $selected = '', array $options = []): string
    {
        $options['input_type'] = 'checkbox';

        return $this->createSelectionField($name, $value, $selected, $options);
    }

    /**
     * Select-Feld
     *
     */
    public function drawSelectField(string $name, array $values, string $selected = '', array $options = []): string
    {
        $options['input_type'] = 'select';

        return $this->createListField($name, $values, $selected, $options);
    }

    /**
     * List-Feld
     *
     */
    public function drawListField(string $name, array $values, string $selected = '', array $options = []): string
    {
        $options['input_type'] = 'list';

        return $this->createListField($name, $values, $selected, $options);
    }

    /**
     * MultipleList-Feld
     *
     */
    public function drawMultipleListField(string $name, array $values, string $selected = '', array $options = []): string
    {
        $options['input_type'] = 'multilist';

        return $this->createListField($name, $values, $selected, $options);
    }

    /**
     * Submit-Button
     *
     */
    public function drawSubmit(string $name, string $value, array $options = []): string
    {
        $options['input_type'] = 'submit';

        return $this->createSubmit($name, $value, $options);
    }

    /**
     * Reset-Button
     *
     */
    public function drawReset(string $name, string $value, array $options = []): string
    {
        $options['input_type'] = 'reset';

        return $this->createSubmit($name, $value, $options);
    }

    /**
     *
     */
    public function startForm(string $name, string $module = 'current', string $parameters = '', array $options = []): string
    {
        if (!isset($options['form_method'])) {
            $options['form_method'] = 'post';
        } else {
            switch ($options['form_method']) {
                case 'get':
                    break;
                case 'post':
                default:
                    $options['form_method'] = 'post';
            }
        }
        if (!isset($options['form_parameter'])) {
            $options['form_parameter'] = '';
        }
        if (!isset($options['input_addid'])) {
            $options['input_addid'] = true;
        }
        if ((!isset($options['input_id'])) && ($options['input_addid'] === true)) {
            $options['input_id'] = HTML::outputString($name);
        }
        $form = '<form name="' . HTML::outputString($name) . '"';
        if ($options['input_addid'] === true) {
            $form .= ' id="' . $options['input_id'] . '"';
        }
        if (isset($options['form_action'])) {
            $form .= ' action="' . $options['form_action'] . '"';
        } else {
            $form .= ' action="' . Navigation::buildUrl($module, $parameters) . '"';
        }
        $form .= ' method="' . HTML::outputString($options['form_method']) . '"';
        $form .= ' ' . $options['form_parameter'];
        if (isset($options['input_class'])) {
            $form .= ' class="' . HTML::outputString($options['input_class']) . '"';
        }
        $form .= '>';

        return $form;
    }

    /**
     *
     */
    public function endForm(): string
    {
        $fieldstring = '';
        if ($this->hidden_fields !== []) {
            foreach ($this->hidden_fields as $field) {
                if ($field['reinsert'] === true) {
                    if (isset($_GET[$field['name']])) {
                        $field['value'] = $_GET[$field['name']];
                    } elseif (isset($_POST[$field['name']])) {
                        $field['value'] = $_POST[$field['name']];
                    }
                }
                $fieldstring .= '<input type="hidden" name="' . HTML::outputString($field['name']) . '"';
                if (isset($field['value'])) {
                    $fieldstring .= ' value="' . HTML::outputString($field['value'], false) . '"';
                }
                if (!empty($field['parameters'])) {
                    $fieldstring .= ' ' . $field['parameters'];
                }
                $fieldstring .= '/>';
            }
        }
        $fieldstring .= '</form>';
        $this->initClass();

        return $fieldstring;
    }

    /**
     *
     */
    public function hasErrorMessages(): bool
    {
        if ($this->errors !== []) {
            return true;
        }

        return false;
    }

    /**
     *
     */
    public function addErrorMessage(string $formfield, string $message = ''): bool
    {
        $this->errors[$formfield] = $message;

        return true;
    }

    /**
     *
     */
    public function isErrorMessage(string $formfield): bool
    {
        if (isset($this->errors[$formfield])) {
            return true;
        }

        return false;
    }

    /**
     *
     */
    public function getErrorMessage(string $formfield): ?string
    {
        if (isset($this->errors[$formfield])) {
            return $this->errors[$formfield];
        }

        return null;
    }

    /**
     */
    public function getErrorMessages(): array
    {
        return $this->errors;
    }

    /**
     * @return $this
     */
    protected function initClass(): self
    {
        $this->hidden_fields = [];
        $this->errors = [];
        $this->count = [];

        return $this;
    }

    /**
     * Erstellt ein Input-Feld
     *
     */
    protected function createInputField(string $name, string $value, array $options = []): string
    {
        $field = '';
        if (!isset($options['input_type'])) {
            $options['input_type'] = 'text';
        }
        if (!isset($options['input_defaultclass'])) {
            $options['input_defaultclass'] = '';
        }
        if (!isset($options['input_errorclass'])) {
            $options['input_errorclass'] = 'oswerror';
        }
        if (!isset($options['input_addid'])) {
            $options['input_addid'] = true;
        }
        if (!isset($options['reinsert_value'])) {
            $options['reinsert_value'] = true;
        }
        if (!isset($options['input_error'])) {
            $options['input_error'] = true;
        }
        if ((!isset($options['input_id'])) && ($options['input_addid'] === true)) {
            $options['input_id'] = HTML::outputString($name);
        }
        $field .= '<input type="' . $options['input_type'] . '" name="' . HTML::outputString($name) . '"';
        if ($options['input_addid'] === true) {
            $field .= ' id="' . $options['input_id'] . '"';
        }
        if (isset($options['input_class'])) {
            if ($options['input_error'] === true) {
                if ($this->getErrorMessage($name) !== null) {
                    $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_errorclass'] . '"';
                } else {
                    $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_defaultclass'] . '"';
                }
            } else {
                $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_defaultclass'] . '"';
            }
        } else {
            if ($this->getErrorMessage($name) !== null) {
                $field .= ' class="' . $options['input_errorclass'] . '"';
            } else {
                $field .= ' class="' . $options['input_defaultclass'] . '"';
            }
        }
        if ($options['reinsert_value'] === true) {
            if (isset($_GET[$name])) {
                $value = $_GET[$name];
            } elseif (isset($_POST[$name])) {
                $value = $_POST[$name];
            }
        }
        $value = trim($value);
        if ($value !== '') {
            $field .= ' value="' . HTML::outputString($value) . '"';
        }
        if (isset($options['input_parameter'])) {
            $field .= ' ' . $options['input_parameter'];
        }
        $field .= '/>';

        return $field;
    }

    /**
     * Erstellt ein Textarea-Feld
     *
     */
    protected function createTextField(string $name, string $value, array $options = []): string
    {
        $field = '';
        Settings::setBoolVar('template_textarea_used', true);
        if (!isset($options['input_type'])) {
            $options['input_type'] = 'textarea';
        } else {
            switch ($options['input_type']) {
                case 'textarea':
                default:
                    $options['input_type'] = 'textarea';
            }
        }

        if (!isset($options['input_defaultclass'])) {
            $options['input_defaultclass'] = '';
        }
        if (!isset($options['input_errorclass'])) {
            $options['input_errorclass'] = 'oswerror';
        }
        if (!isset($options['input_addid'])) {
            $options['input_addid'] = true;
        }
        if (!isset($options['input_rows'])) {
            $options['input_rows'] = 4;
        }
        if (!isset($options['reinsert_value'])) {
            $options['reinsert_value'] = true;
        }
        if (!isset($options['input_error'])) {
            $options['input_error'] = true;
        }
        if ((!isset($options['input_id'])) && ($options['input_addid'] === true)) {
            $options['input_id'] = HTML::outputString($name);
        }
        $field .= '<textarea name="' . HTML::outputString($name) . '"';
        if ($options['input_addid'] === true) {
            $field .= ' id="' . $options['input_id'] . '"';
        }
        if (isset($options['input_class'])) {
            if ($options['input_error'] === true) {
                if ($this->getErrorMessage($name) !== null) {
                    $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_errorclass'] . '"';
                } else {
                    $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_defaultclass'] . '"';
                }
            } else {
                $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_defaultclass'] . '"';
            }
        } else {
            if ($this->getErrorMessage($name) !== null) {
                $field .= ' class="' . $options['input_errorclass'] . '"';
            } else {
                $field .= ' class="' . $options['input_defaultclass'] . '"';
            }
        }
        $field .= ' rows="' . $options['input_rows'] . '"';
        if ($options['reinsert_value'] === true) {
            if (isset($_GET[$name])) {
                $value = $_GET[$name];
            } elseif (isset($_POST[$name])) {
                $value = $_POST[$name];
            }
        }
        $value = trim($value);
        if (isset($options['input_parameter'])) {
            $field .= ' ' . $options['input_parameter'];
        }
        $field .= '>';
        if ($value !== '') {
            $field .= htmlspecialchars($value);
        }
        $field .= '</textarea>';

        return $field;
    }

    /**
     * Erstellt ein Selections-Feld
     *
     */
    protected function createSelectionField(string $name, string $value, string $selected, array $options = []): string
    {
        $field = '';
        if (!isset($options['input_type'])) {
            $options['input_type'] = 'checkbox';
        } else {
            switch ($options['input_type']) {
                case 'radio':
                    break;
                case 'checkbox':
                default:
                    $options['input_type'] = 'checkbox';
            }
        }
        if (!isset($this->count[$name])) {
            $this->count[$name] = 0;
        } else {
            $this->count[$name]++;
        }
        if (!isset($options['input_defaultclass'])) {
            $options['input_defaultclass'] = '';
        }
        if (!isset($options['input_errorclass'])) {
            $options['input_errorclass'] = 'oswerror';
        }
        if (!isset($options['input_addid'])) {
            $options['input_addid'] = true;
        }
        if (!isset($options['reinsert_value'])) {
            $options['reinsert_value'] = true;
        }
        if (!isset($options['input_error'])) {
            $options['input_error'] = true;
        }
        if ((!isset($options['input_id'])) && ($options['input_addid'] === true)) {
            $options['input_id'] = HTML::outputString($name) . $this->count[$name];
        }
        $field .= '<input type="' . $options['input_type'] . '" name="' . HTML::outputString($name) . '"';
        if ($options['input_addid'] === true) {
            $field .= ' id="' . $options['input_id'] . '"';
        }
        if (isset($options['input_class'])) {
            if ($options['input_error'] === true) {
                if ($this->getErrorMessage($name) !== null) {
                    $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_errorclass'] . '"';
                } else {
                    $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_defaultclass'] . '"';
                }
            } else {
                $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_defaultclass'] . '"';
            }
        } else {
            if ($this->getErrorMessage($name) !== null) {
                $field .= ' class="' . $options['input_errorclass'] . '"';
            } else {
                $field .= ' class="' . $options['input_defaultclass'] . '"';
            }
        }
        if ($options['reinsert_value'] === true) {
            if (isset($_GET[$name])) {
                $selected = $_GET[$name];
            } elseif (isset($_POST[$name])) {
                $selected = $_POST[$name];
            }
        }
        $value = trim($value);
        if ($value !== '') {
            $field .= ' value="' . HTML::outputString($value) . '"';
        }
        if (($selected === $value) && (\strlen($selected) === \strlen($value))) {
            $field .= ' checked="checked"';
        }
        if (isset($options['input_parameter'])) {
            $field .= ' ' . $options['input_parameter'];
        }
        $field .= '/>';

        return $field;
    }

    /**
     * Erstellt ein List-Feld
     *
     */
    protected function createListField(string $name, array $values, string $selected, array $options): string
    {
        $field = '';
        if (\count($values) === 0) {
            $values = [];
        }
        if (!isset($options['input_type'])) {
            $options['input_type'] = 'select';
        } else {
            switch ($options['input_type']) {
                case 'list':
                    if (!isset($options['input_listsize'])) {
                        $options['input_listsize'] = \count($values);
                        if (!isset($options['input_parameter'])) {
                            $options['input_parameter'] = ' size="' . $options['input_listsize'] . '"';
                        } else {
                            $options['input_parameter'] .= ' size="' . $options['input_listsize'] . '"';
                        }
                    }

                    break;
                case 'multilist':
                    if (!isset($options['input_parameter'])) {
                        $options['input_parameter'] = ' multiple="multiple"';
                    } else {
                        $options['input_parameter'] .= ' multiple="multiple"';
                    }

                    break;
                case 'select':
                default:
                    $options['input_type'] = 'select';
            }
        }

        if (!isset($options['input_defaultclass'])) {
            $options['input_defaultclass'] = '';
        }
        if (!isset($options['input_errorclass'])) {
            $options['input_errorclass'] = 'oswerror';
        }
        if (!isset($options['input_addid'])) {
            $options['input_addid'] = true;
        }
        if (!isset($options['input_option_value'])) {
            $options['input_option_value'] = 'value';
        }
        if (!isset($options['reinsert_value'])) {
            $options['reinsert_value'] = true;
        }
        if (!isset($options['input_error'])) {
            $options['input_error'] = true;
        }
        if (!isset($options['input_label'])) {
            $options['input_label'] = 'Bitte wählen...';
        }
        if ((!isset($options['input_id'])) && ($options['input_addid'] === true)) {
            $options['input_id'] = HTML::outputString($name);
        }
        switch ($options['input_type']) {
            case 'multilist':
                $field .= '<select name="' . HTML::outputString($name) . '[]"';

                break;
            case 'list':
            case 'select':
            default:
                $field .= '<select name="' . HTML::outputString($name) . '"';
        }
        if ($options['input_addid'] === true) {
            $field .= ' id="' . $options['input_id'] . '"';
        }
        if (isset($options['input_parameter'])) {
            $field .= ' ' . $options['input_parameter'];
        }
        if (isset($options['input_class'])) {
            if ($options['input_error'] === true) {
                if ($this->getErrorMessage($name) !== null) {
                    $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_errorclass'] . '"';
                } else {
                    $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_defaultclass'] . '"';
                }
            } else {
                $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_defaultclass'] . '"';
            }
        } else {
            if ($this->getErrorMessage($name) !== null) {
                $field .= ' class="' . $options['input_errorclass'] . '"';
            } else {
                $field .= ' class="' . $options['input_defaultclass'] . '"';
            }
        }
        if ($options['reinsert_value'] === true) {
            if (isset($_GET[$name])) {
                $selected = $_GET[$name];
            } elseif (isset($_POST[$name])) {
                $selected = $_POST[$name];
            }
        }
        $field .= '>';
        if ($options['input_type'] === 'multilist') {
            if (\is_array($selected)) {
                $selected = array_flip($selected);
                foreach ($values as $key => $value) {
                    if ((\is_array($value)) && (isset($value['value'])) && (isset($value['text']))) {
                        $key = $value['value'];
                        $value = $value['text'];
                    }
                    $label = '';
                    if ($value === '') {
                        $label = ' label="' . HTML::outputString($options['input_label']) . '"';
                    }
                    if (isset($selected[$key])) {
                        $field .= '<option ' . $options['input_option_value'] . '="' . HTML::outputString($key) . '"' . $label . ' selected="selected">' . HTML::outputString($value) . Settings::getStringVar('form_spacer') . '</option>';
                    } else {
                        $field .= '<option ' . $options['input_option_value'] . '="' . HTML::outputString($key) . '"' . $label . '>' . HTML::outputString($value) . Settings::getStringVar('form_spacer') . '</option>';
                    }
                }
            } else {
                foreach ($values as $key => $value) {
                    if ((\is_array($value)) && (isset($value['value'])) && (isset($value['text']))) {
                        $key = $value['value'];
                        $value = $value['text'];
                    }
                    $label = '';
                    if ($value === '') {
                        $label = ' label="' . HTML::outputString($options['input_label']) . '"';
                    }
                    if ((string)$selected === (string)$key) {
                        $field .= '<option ' . $options['input_option_value'] . '="' . HTML::outputString($key) . '"' . $label . ' selected="selected">' . HTML::outputString($value) . Settings::getStringVar('form_spacer') . '</option>';
                    } else {
                        $field .= '<option ' . $options['input_option_value'] . '="' . HTML::outputString($key) . '"' . $label . '>' . HTML::outputString($value) . Settings::getStringVar('form_spacer') . '</option>';
                    }
                }
            }
        } else {
            $_selected = false;
            foreach ($values as $key => $value) {
                if ((\is_array($value)) && (isset($value['value'])) && (isset($value['text']))) {
                    $key = $value['value'];
                    $value = $value['text'];
                }
                $label = '';
                if ($value === '') {
                    $label = ' label="' . HTML::outputString($options['input_label']) . '"';
                }
                if (((string)$selected === (string)$key)) {
                    $_selected = true;
                    $field .= '<option ' . $options['input_option_value'] . '="' . HTML::outputString($key) . '"' . $label . ' selected="selected">' . HTML::outputString($value) . Settings::getStringVar('form_spacer') . '</option>';
                } else {
                    $field .= '<option ' . $options['input_option_value'] . '="' . HTML::outputString($key) . '"' . $label . '>' . HTML::outputString($value) . Settings::getStringVar('form_spacer') . '</option>';
                }
            }
        }
        $field .= '</select>';

        return $field;
    }

    /**
     * Erstellt ein Submit-Feld
     *
     */
    protected function createSubmit(string $name, string $value, array $options): string
    {
        $field = '';
        if (!isset($options['input_type'])) {
            $options['input_type'] = 'submit';
        } else {
            switch ($options['input_type']) {
                case 'image':
                    if (!isset($options['input_image'])) {
                        $options['input_type'] = 'submit';
                    } else {
                        if (!isset($options['input_parameter'])) {
                            $options['input_parameter'] = ' src="' . $options['input_image'] . '"';
                        } else {
                            $options['input_parameter'] .= ' src="' . $options['input_image'] . '"';
                        }
                    }

                    break;
                case 'reset':
                    $options['input_type'] = 'reset';

                    break;
                case 'submit':
                default:
                    $options['input_type'] = 'submit';
            }
        }

        if (!isset($options['input_defaultclass'])) {
            $options['input_defaultclass'] = '';
        }
        if (!isset($options['input_errorclass'])) {
            $options['input_errorclass'] = 'oswerror';
        }
        if (!isset($options['input_addid'])) {
            $options['input_addid'] = true;
        }
        if (!isset($options['input_error'])) {
            $options['input_error'] = true;
        }
        if ((!isset($options['input_id'])) && ($options['input_addid'] === true)) {
            $options['input_id'] = HTML::outputString($name);
        }
        $field .= '<input type="' . $options['input_type'] . '" name="' . HTML::outputString($name) . '"';
        if ($options['input_addid'] === true) {
            $field .= ' id="' . $options['input_id'] . '"';
        }
        if (isset($options['input_class'])) {
            if ($options['input_error'] === true) {
                if ($this->getErrorMessage($name) !== null) {
                    $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_errorclass'] . '"';
                } else {
                    $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_defaultclass'] . '"';
                }
            } else {
                $field .= ' class="' . HTML::outputString($options['input_class']) . ' ' . $options['input_defaultclass'] . '"';
            }
        } else {
            if ($this->getErrorMessage($name) !== null) {
                $field .= ' class="' . $options['input_errorclass'] . '"';
            } else {
                $field .= ' class="' . $options['input_defaultclass'] . '"';
            }
        }
        if ($options['input_type'] !== 'image') {
            $field .= ' title="' . HTML::outputString($value) . '" value="' . HTML::outputString($value) . '"';
        }
        if (isset($options['input_parameter'])) {
            $field .= ' ' . $options['input_parameter'];
        }
        $field .= '/>';

        return $field;
    }
}
