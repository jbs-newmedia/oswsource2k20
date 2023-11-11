<?php declare(strict_types=0);

/**
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace osWFrame\Core;

class DDM4
{
    use BaseStaticTrait;
    use BaseConnectionTrait;
    use BaseTemplateBridgeTrait;

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

    protected string $name = '';

    protected array $ddm = [];

    public function __construct(
        object $Template,
        string $name = 'ddm4_group',
        array  $options = []
    ) {
        $this->name = $name;
        $this->setTemplate($Template);
        $this->addGroup($options);
    }

    public function reset(string $name = 'ddm4_group'): bool
    {
        $this->name = $name;
        $this->ddm = [];

        return true;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return str_replace('_log_log', '_log', $this->name);
    }

    public function addGroup(array $options): bool
    {
        if (isset($this->ddm['options'])) {
            return false;
        }
        if ((isset($options['database'])) && (isset($options['database']['connection'])) && (!isset($options['database']['connection_lock']))) {
            $options['database']['connection_lock'] = $options['database']['connection'];
        }
        if ((isset($options['database'])) && (isset($options['database']['connection'])) && (!isset($options['database']['connection_log']))) {
            $options['database']['connection_log'] = $options['database']['connection'];
        }
        if (!isset($options['messages'])) {
            $options['messages'] = [];
        }
        $options['messages'] = $this->loadDefaultMessages($options['messages']);
        $this->ddm['options'] = $options;
        if (!isset($this->ddm['options']['theme'])) {
            $this->ddm['options']['theme'] = 'default';
        }
        if ((!isset($this->ddm['options']['layout_loaded'])) || ($this->ddm['options']['layout_loaded'] !== true)) {
            if (!isset($this->ddm['options']['layout'])) {
                $this->ddm['options']['layout'] = 'default';
            }
            $this->ddm['options']['layout_loaded'] = true;
            $version = self::getVersion();
            $dir = strtolower(self::getClassName());
            $name = $version . '-' . $this->ddm['options']['layout'] . '.resource';
            if (Resource::existsResource($dir, $name) !== true) {
                $files = [];
                foreach (glob(
                    Settings::getStringVar(
                        'settings_abspath'
                    ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'layout' . \DIRECTORY_SEPARATOR . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR . '*.*'
                ) as $filename) {
                    $files[] = str_replace(
                        Settings::getStringVar(
                            'settings_abspath'
                        ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'layout' . \DIRECTORY_SEPARATOR . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR,
                        '',
                        $filename
                    );
                }
                Resource::copyResourcePath(
                    'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'layout' . \DIRECTORY_SEPARATOR . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR,
                    $dir . \DIRECTORY_SEPARATOR . $version . '-' . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR,
                    $files
                );
                foreach (glob(
                    Settings::getStringVar(
                        'settings_abspath'
                    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'layout' . \DIRECTORY_SEPARATOR . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR . '*.*'
                ) as $filename) {
                    $files[] = str_replace(
                        Settings::getStringVar(
                            'settings_abspath'
                        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'layout' . \DIRECTORY_SEPARATOR . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR,
                        '',
                        $filename
                    );
                }
                Resource::copyResourcePath(
                    'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'layout' . \DIRECTORY_SEPARATOR . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR,
                    $dir . \DIRECTORY_SEPARATOR . $version . '-' . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR,
                    $files
                );
                $path = Resource::getRelDir(
                ) . $dir . \DIRECTORY_SEPARATOR . $version . '-' . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR;
                $content = file_get_contents($path . \DIRECTORY_SEPARATOR . 'default.css');
                $content = str_replace('url("/__imageloader__/loader.gif")', 'url("/' . $path . 'loader.gif")', $content);
                file_put_contents($path . \DIRECTORY_SEPARATOR . 'default.css', $content);
                Resource::writeResource($dir, $name, 'time:' . time());
            }

            foreach (glob(
                Resource::getRelDir(
                ) . $dir . \DIRECTORY_SEPARATOR . $version . '-' . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR . '*.css'
            ) as $filename) {
                $this->getTemplate()->addCSSFileHead($filename);
            }
            foreach (glob(
                Resource::getRelDir(
                ) . $dir . \DIRECTORY_SEPARATOR . $version . '-' . $this->ddm['options']['layout'] . \DIRECTORY_SEPARATOR . '*.js'
            ) as $filename) {
                $this->getTemplate()->addJSFileHead($filename);
            }
        }
        $file = Settings::getStringVar(
            'settings_abspath'
        ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'loader' . \DIRECTORY_SEPARATOR . 'group' . \DIRECTORY_SEPARATOR . $this->getName(
        ) . '.inc.php';
        $file_core = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'loader' . \DIRECTORY_SEPARATOR . 'group' . \DIRECTORY_SEPARATOR . $this->getName(
        ) . '.inc.php';
        if (file_exists($file)) {
            include $file;
        } elseif (file_exists($file_core)) {
            include $file_core;
        }

        return true;
    }

    public function getElementsArrayInit(): array
    {
        return [
            'preview' => [],
            'view' => [],
            'data' => [],
            'send' => [],
            'finish' => [],
            'afterfinish' => [],
        ];
    }

    /**
     * @return string[]
     */
    public function loadDefaultMessages(array $messages): array
    {
        $default_messages = [];
        $default_messages['data_options'] = 'Optionen';
        $default_messages['form_submit'] = 'Absenden';
        $default_messages['form_search'] = 'Suchen';
        $default_messages['form_add'] = 'Erstellen';
        $default_messages['form_edit'] = 'Bearbeiten';
        $default_messages['form_delete'] = 'Löschen';
        $default_messages['form_reset'] = 'Zurücksetzen';
        $default_messages['form_close'] = 'Schließen';
        $default_messages['form_cancel'] = 'Abbrechen';
        $default_messages['form_delete'] = 'Löschen';
        $default_messages['form_send'] = 'Absenden';
        $default_messages['data_search'] = 'Suchen';
        $default_messages['data_add'] = 'Erstellen';
        $default_messages['data_edit'] = 'Bearbeiten';
        $default_messages['data_delete'] = 'Löschen';
        $default_messages['data_log'] = 'Log';
        $default_messages['data_choose'] = 'Bitte wählen ...';
        $default_messages['form_title_required_icon'] = '*';
        $default_messages['form_title_pages'] = 'Seiten';
        $default_messages['form_title_pages_single'] = 'Seite';
        $default_messages['form_title_pages_multi'] = 'Seiten';
        $default_messages['form_title_counter'] = 'Datensätze $elements_from$ - $elements_to$ ($elements_all$ insgesamt)';
        $default_messages['form_title_counter_single'] = 'Datensatz $elements_from$ - $elements_to$ ($elements_all$ insgesamt)';
        $default_messages['form_title_counter_multi'] = 'Datensätze $elements_from$ - $elements_to$ ($elements_all$ insgesamt)';
        $default_messages['form_title_asc'] = 'Aufsteigend sortieren';
        $default_messages['form_title_desc'] = 'Absteigend sortieren';
        $default_messages['form_title_sortorder_delete'] = 'Sortierung entfernen';
        $default_messages['form_title_closer'] = ':';
        $default_messages['form_required_notice'] = '* Pflichtfeld';
        $default_messages['form_required_notice_multi'] = '* Pflichtfelder';
        $default_messages['form_error'] = 'Eingabefehler';
        $default_messages['data_noresults'] = 'Keine Elemente vorhanden';
        $default_messages['text_hidden'] = 'Wird nicht angezeigt';
        $default_messages['text_char'] = 'Zeichen';
        $default_messages['text_chars'] = 'Zeichen';
        $default_messages['text_all'] = 'Alle';
        $default_messages['text_yes'] = 'Ja';
        $default_messages['text_no'] = 'Nein';
        $default_messages['text_blank'] = '---';
        $default_messages['text_action'] = 'Aktion';
        $default_messages['text_file_select'] = 'Datei auswählen';
        $default_messages['text_file_view'] = 'Datei anzeigen';
        $default_messages['text_file_delete'] = 'Datei löschen';
        $default_messages['text_image_select'] = 'Bild auswählen';
        $default_messages['text_image_view'] = 'Bild anzeigen';
        $default_messages['text_image_delete'] = 'Bild löschen';
        $default_messages['text_image_show'] = 'Bild einblenden';
        $default_messages['text_image_edit'] = 'Bild bearbeiten';
        $default_messages['log_char_true'] = '<i class="fas fa-plus-square"></i>';
        $default_messages['log_char_false'] = '<i class="fas fa-minus-square"></i>';
        $default_messages['text_clock'] = 'Uhr';
        $default_messages['text_search'] = 'Suche';
        $default_messages['text_filter'] = 'Filter';
        $default_messages['text_selected'] = 'Ausgewählte';
        $default_messages['text_notselected'] = 'Nicht Ausgewählte';
        $default_messages['text_selectall'] = 'Alle auswählen';
        $default_messages['text_deselectall'] = 'Keins auswählen';
        $default_messages['text_invertselection'] = 'Auswahl umkehren';
        $default_messages['text_all'] = 'Alle';
        $default_messages['create_time'] = 'Erstellt am';
        $default_messages['create_user'] = 'Erstellt von';
        $default_messages['update_time'] = 'Geändert am';
        $default_messages['update_user'] = 'Geändert von';
        $default_messages['search_title'] = 'Erweiterte Suche';
        $default_messages['edit_search_title'] = 'Erweiterte Suche bearbeiten';
        $default_messages['back_title'] = 'Zurück zur Übersicht';
        $default_messages['sortorder_title'] = 'Sortierung';
        $default_messages['createupdate_title'] = 'Datensatzinformationen';
        $default_messages['log_title'] = 'Datensatzhistorie';
        $default_messages['send_title'] = 'Datensatz übermitteln';
        $default_messages['send_success_title'] = 'Datensatz wurde erfolgreich übermittelt';
        $default_messages['add_title'] = 'Neuen Datensatz erstellen';
        $default_messages['add_success_title'] = 'Datensatz wurde erfolgreich erstellt';
        $default_messages['add_error_title'] = 'Datensatz konnte nicht erstellt werden';
        $default_messages['edit_title'] = 'Datensatz bearbeiten';
        $default_messages['edit_load_error_title'] = 'Datensatz wurde nicht gefunden';
        $default_messages['edit_success_title'] = 'Datensatz wurde erfolgreich bearbeitet';
        $default_messages['edit_error_title'] = 'Datensatz konnte nicht bearbeitet werden';
        $default_messages['delete_title'] = 'Datensatz löschen';
        $default_messages['delete_load_error_title'] = 'Datensatz wurde nicht gefunden';
        $default_messages['delete_success_title'] = 'Datensatz wurde erfolgreich gelöscht';
        $default_messages['delete_error_title'] = 'Datensatz konnte nicht gelöscht werden';
        $default_messages['lock_error'] = 'Datensatz durch "$user$" gesperrt. Keine Änderungen möglich.';
        $default_messages['validation_error'] = 'Es sind Fehler vorhanden.';
        $default_messages['validation_element_error'] = 'Fehler bei $element_title$.';
        $default_messages['validation_element_filtererror'] = 'Filter "$filter$" bei "$element_title$" wurde nicht gefunden.';
        $default_messages['validation_element_incorrect'] = 'Ihre Eingabe bei "$element_title$" ist nicht korrekt.';
        $default_messages['validation_element_toshort'] = 'Bitte korrekt angeben (Mindestens $length_min$ Zeichen)';
        $default_messages['validation_element_tolong'] = 'Bitte korrekt angeben (Maximal $length_max$ Zeichen)';
        $default_messages['validation_element_empty'] = 'Bitte angeben';
        $default_messages['validation_element_tosmall'] = 'Ihre Eingabe bei "$element_title$" ist zu klein.';
        $default_messages['validation_element_tobig'] = 'Ihre Eingabe bei "$element_title$" ist zu groß.';
        $default_messages['validation_element_regerror'] = 'Ihre Eingabe bei "$element_title$" ist nicht korrekt.';
        $default_messages['validation_element_double'] = 'Ihre Eingaben bei "$element_title$" stimmen nicht überein.';
        $default_messages['validation_element_unique'] = 'Ihre Eingabe bei "$element_title$" ist bereits vorhanden.';
        $default_messages['validation_element_miss'] = 'Ihre Eingabe bei "$element_title$" fehlt.';
        $default_messages['validation_file_uploaderror'] = 'Die Datei bei "$element_title$" konnte nicht hochgeladen werden.';
        $default_messages['validation_file_typeerror'] = 'Die Datei bei "$element_title$" ist vom falschen Typ.';
        $default_messages['validation_file_extensionerror'] = 'Die Datei bei "$element_title$" hat die falsche Endung.';
        $default_messages['validation_file_tosmall'] = 'Die Datei bei "$element_title$" ist zu klein.';
        $default_messages['validation_file_tobig'] = 'Die Datei bei "$element_title$" ist zu groß.';
        $default_messages['validation_file_miss'] = 'Keine Datei bei "$element_title$" hochgeladen.';
        $default_messages['validation_image_uploaderror'] = 'Die Datei bei "$element_title$" konnte nicht hochgeladen werden.';
        $default_messages['validation_image_fileerror'] = 'Die Datei bei "$element_title$" ist kein Bild.';
        $default_messages['validation_image_typeerror'] = 'Die Datei bei "$element_title$" ist vom falschen Typ.';
        $default_messages['validation_image_extensionerror'] = 'Die Datei bei "$element_title$" hat die falsche Endung.';
        $default_messages['validation_image_tosmall'] = 'Die Datei bei "$element_title$" ist zu klein.';
        $default_messages['validation_image_tobig'] = 'Die Datei bei "$element_title$" ist zu groß.';
        $default_messages['validation_imagewidth_tosmall'] = 'Die Breite bei "$element_title$" ist zu klein.';
        $default_messages['validation_imagewidth_tobig'] = 'Die Breite bei "$element_title$" ist zu groß.';
        $default_messages['validation_imageheight_tosmall'] = 'Die Höhe bei "$element_title$" ist zu klein.';
        $default_messages['validation_imageheight_tobig'] = 'Die Höhe bei "$element_title$" ist zu groß.';
        $default_messages['validation_image_miss'] = 'Keine Datei bei "$element_title$" hochgeladen.';
        $default_messages['module_not_found'] = 'Modul "$module$" in "$path$" nicht gefunden';
        if ($messages !== []) {
            foreach ($messages as $key => $value) {
                $default_messages[$key] = $value;
            }
        }

        return $default_messages;
    }

    /**
     * Setzt den Wert einer Group-Option
     *
     */
    public function setGroupOption(string $option, $value, string $group = 'general'): bool
    {
        $this->ddm['options'][$group][$option] = $value;

        return true;
    }

    public function setGroupMessages(array $values): bool
    {
        $this->ddm['options']['messages'] = $values;

        return true;
    }

    /**
     * Gibt den Wert einer Group-Option zurück, wenn nicht vorhanden liefert es '' zurück
     *
     */
    public function getGroupOption(string $option, string $group = 'general')
    {
        if (isset($this->ddm['options'][$group][$option])) {
            return $this->ddm['options'][$group][$option];
        }

        return '';
    }

    public function getGroupMessage(string $option): string
    {
        return $this->getGroupOption($option, 'messages');
    }

    public function addElement(string $type, string $element, array $options): bool
    {
        if ((!isset($options['enabled'])) || ($options['enabled'] !== true)) {
            $options['id'] = $element;
            if (isset($this->ddm[$type]['elements'][$element])) {
                return false;
            }
            if (!isset($this->ddm['counts'])) {
                $this->ddm['counts'] = [];
            }
            if ($type === 'data') {
                $_data = ['list', 'search', 'add', 'edit', 'delete'];
                $default_options = [];
                if ((isset($options['module'])) && ($options['module'] !== '')) {
                    $file = Settings::getStringVar(
                        'settings_abspath'
                    ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'defaultdata' . \DIRECTORY_SEPARATOR . $options['module'] . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
                    $file_core = Settings::getStringVar(
                        'settings_abspath'
                    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'defaultdata' . \DIRECTORY_SEPARATOR . $options['module'] . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
                    if (file_exists($file)) {
                        include $file;
                    } elseif (file_exists($file_core)) {
                        include $file_core;
                    }
                }
                $options = array_replace_recursive($default_options, $options);
                $_tmp = [];
                foreach ($_data as $_type) {
                    $_tmp[$_type] = [];
                    if (isset($options['_' . $_type])) {
                        $_tmp[$_type] = $options['_' . $_type];
                        unset($options['_' . $_type]);
                    }
                }
                foreach ($_data as $_type) {
                    $data = array_replace_recursive($options, $_tmp[$_type]);
                    if (((!isset($data['enabled'])) || ($data['enabled'] === true)) && ($this->getGroupOption(
                        'disable_' . $_type
                    ) !== true)
                    ) {
                        $this->ddm['elements'][$_type][$element] = $data;
                        $this->ddm['counts'][$_type . '_elements'] = \count($this->ddm['elements'][$_type]);
                        if ((isset($this->ddm['elements'][$_type][$element]['options']['check_required'])) && ($this->ddm['elements'][$_type][$element]['options']['check_required'] === true)) {
                            if (isset($this->ddm['elements'][$_type][$element]['validation'])) {
                                if ((isset($this->ddm['elements'][$_type][$element]['validation']['length_min'])) && ($this->ddm['elements'][$_type][$element]['validation']['length_min'] > 0)) {
                                    $this->ddm['elements'][$_type][$element]['options']['required'] = true;
                                }
                            }
                        }
                    }
                }
            } else {
                $default_options = [];
                if ((isset($options['module'])) && ($options['module'] !== '')) {
                    $file = Settings::getStringVar(
                        'settings_abspath'
                    ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'defaultdata' . \DIRECTORY_SEPARATOR . $options['module'] . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
                    $file_core = Settings::getStringVar(
                        'settings_abspath'
                    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'defaultdata' . \DIRECTORY_SEPARATOR . $options['module'] . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
                    if (file_exists($file)) {
                        include $file;
                    } elseif (file_exists($file_core)) {
                        include $file_core;
                    }
                }
                $options = array_replace_recursive($default_options, $options);
                if ($this->getGroupOption('disable_' . $type) !== true) {
                    $this->ddm['elements'][$type][$element] = $options;
                    $this->ddm['counts'][$type . '_elements'] = \count($this->ddm['elements'][$type]);
                }
            }

            return true;
        }

        return false;
    }

    public function clearStorageView(): void
    {
        $this->ddm['storage']['view'] = [];
    }

    public function clearStorageViewData(): void
    {
        $this->ddm['storage']['view']['data'] = [];
    }

    public function setStorageViewData(array $data): void
    {
        $this->ddm['storage']['view']['data'] = $data;
    }

    public function addStorageViewData(array $data): void
    {
        $this->ddm['storage']['view']['data'][] = $data;
    }

    public function getStorageViewData(): array
    {
        if (isset($this->ddm['storage']['view']['data'])) {
            return $this->ddm['storage']['view']['data'];
        }

        return [];
    }

    public function clearStorageViewLimitrows(): void
    {
        $this->ddm['storage']['view']['limitrows'] = [];
    }

    public function setStorageViewLimitrows(array $limitrows): void
    {
        $this->ddm['storage']['view']['limitrows'] = $limitrows;
    }

    public function getStorageViewLimitrows(): array
    {
        if (isset($this->ddm['storage']['view']['limitrows'])) {
            return $this->ddm['storage']['view']['limitrows'];
        }

        return [];
    }

    public function addPreViewElement(string $element, array $options): bool
    {
        return $this->addElement('preview', $element, $options);
    }

    public function addViewElement(string $element, array $options): bool
    {
        return $this->addElement('view', $element, $options);
    }

    public function addDataElement(string $element, array $options): bool
    {
        return $this->addElement('data', $element, $options);
    }

    public function addSendElement(string $element, array $options): bool
    {
        return $this->addElement('send', $element, $options);
    }

    public function addFinishElement(string $element, array $options): bool
    {
        return $this->addElement('finish', $element, $options);
    }

    public function addAfterFinishElement(string $element, array $options): bool
    {
        return $this->addElement('afterfinish', $element, $options);
    }

    /**
     * @return $this
     */
    public function setReadOnly(string $element, bool $status = true): self
    {
        if ((isset($this->ddm)) && (isset($this->ddm['elements']['add'][$element]))) {
            $this->ddm['elements']['add'][$element]['options']['read_only'] = $status;
        }
        if ((isset($this->ddm)) && (isset($this->ddm['elements']['edit'][$element]))) {
            $this->ddm['elements']['edit'][$element]['options']['read_only'] = $status;
        }
        if ((isset($this->ddm)) && (isset($this->ddm['elements']['delete'][$element]))) {
            $this->ddm['elements']['delete'][$element]['options']['read_only'] = $status;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function setCounter($counter, $value): self
    {
        $this->ddm['counts'][$counter] = $value;

        return $this;
    }

    public function incCounter(string $counter): bool|int
    {
        if (!isset($this->ddm['counts'][$counter])) {
            $this->setCounter($counter, 0);
        }
        $this->ddm['counts'][$counter] = $this->ddm['counts'][$counter] + 1;

        return $this->getCounter($counter);
    }

    public function decCounter(string $counter): bool|int
    {
        if (!isset($this->ddm['counts'][$counter])) {
            $this->setCounter($counter, 0);
        }
        $this->ddm['counts'][$counter] = $this->ddm['counts'][$counter] - 1;

        return $this->getCounter($counter);
    }

    public function getCounter(string $counter): bool|int
    {
        if (isset($this->ddm['counts'][$counter])) {
            return $this->ddm['counts'][$counter];
        }

        return false;
    }

    public function getOrderElementName(string $element): string
    {
        if (isset($this->ddm['orderelementnames'][$element])) {
            return $this->ddm['orderelementnames'][$element];
        }

        return '';
    }

    public function setOrderElementName(string $element, string $value): bool
    {
        $this->ddm['orderelementnames'][$element] = $value;

        return true;
    }

    public function getElements(string $type): array
    {
        if (isset($this->ddm['elements'][$type])) {
            return $this->ddm['elements'][$type];
        }

        return [];
    }

    public function getPreViewElements(): array
    {
        return $this->getElements('preview');
    }

    public function getViewElements(): array
    {
        return $this->getElements('view');
    }

    public function getListElements(): array
    {
        return $this->getElements('list');
    }

    public function getSearchElements(): array
    {
        return $this->getElements('search');
    }

    public function getAddElements(): array
    {
        return $this->getElements('add');
    }

    public function getEditElements(): array
    {
        return $this->getElements('edit');
    }

    public function getDeleteElements(): array
    {
        return $this->getElements('delete');
    }

    public function getSendElements(): array
    {
        return $this->getElements('send');
    }

    public function getFinishElements(): array
    {
        return $this->getElements('finish');
    }

    public function getAfterFinishElements(): array
    {
        return $this->getElements('afterfinish');
    }

    public function getElement(string $type, string $element): array
    {
        if ((isset($this->ddm['elements'][$type])) && (isset($this->ddm['elements'][$type][$element]))) {
            return $this->ddm['elements'][$type][$element];
        }

        return [];
    }

    public function getPreViewElement(string $element): array
    {
        return $this->getElement('preview', $element);
    }

    public function getViewElement(string $element): array
    {
        return $this->getElement('view', $element);
    }

    public function getListElement(string $element): array
    {
        return $this->getElement('list', $element);
    }

    public function getSearchElement(string $element): array
    {
        return $this->getElement('search', $element);
    }

    public function getAddElement(string $element): array
    {
        return $this->getElement('add', $element);
    }

    public function getEditElement(string $element): array
    {
        return $this->getElement('edit', $element);
    }

    public function getDeleteElement(string $element): array
    {
        return $this->getElement('delete', $element);
    }

    public function getSendElement(string $element): array
    {
        return $this->getElement('send', $element);
    }

    public function getFinishElement(string $element): array
    {
        return $this->getElement('finish', $element);
    }

    public function getAfterFinishElement(string $element): array
    {
        return $this->getElement('afterfinish', $element);
    }

    public function getElementsValue(string $type, string $key, string $group = ''): array
    {
        $ar_tmp = [];
        foreach ($this->getElements($type) as $id => $options) {
            if ($group !== '') {
            } else {
                if (isset($options[$key])) {
                    $ar_tmp[$id] = $options[$key];
                }
            }
        }

        return $ar_tmp;
    }

    public function getViewElementsValue(string $key, string $group = ''): array
    {
        return $this->getElementsValue('view', $key, $group);
    }

    public function getListElementsValue(string $key, string $group = ''): array
    {
        return $this->getElementsValue('list', $key, $group);
    }

    public function getSearchElementsValue(string $key, string $group = ''): array
    {
        return $this->getElementsValue('search', $key, $group);
    }

    public function getAddElementsValue(string $key, string $group = ''): array
    {
        return $this->getElementsValue('add', $key, $group);
    }

    public function getEditElementsValue(string $key, string $group = ''): array
    {
        return $this->getElementsValue('edit', $key, $group);
    }

    public function getDeleteElementsValue(string $key, string $group = ''): array
    {
        return $this->getElementsValue('delete', $key, $group);
    }

    public function getSendElementsValue(string $key, string $group = ''): array
    {
        return $this->getElementsValue('send', $key, $group);
    }

    public function getFinishElementsValue(string $key, string $group = ''): array
    {
        return $this->getElementsValue('finish', $key, $group);
    }

    public function getAfterFinishElementsValue(string $key, string $group = ''): array
    {
        return $this->getElementsValue('afterfinish', $key, $group);
    }

    public function getElementsName(string $type, string $group = ''): array
    {
        $ar_tmp = [];
        foreach ($this->getElements($type) as $id => $options) {
            if ($group === '') {
                if ((isset($options['enabled'])) && ($options['enabled'] === true)) {
                    if (isset($options['name'])) {
                        $ar_tmp[] = $options['name'];
                    }
                    if (isset($options['name_array'])) {
                        foreach ($options['name_array'] as $name) {
                            if ($options['options']['prefix'] !== '') {
                                $ar_tmp[] = $options['options']['prefix'] . $name;
                            } else {
                                $ar_tmp[] = $name;
                            }
                        }
                    }
                }
            }
        }

        return $ar_tmp;
    }

    public function getViewElementsName(string $group = ''): array
    {
        return $this->getElementsName('view', $group);
    }

    public function getListElementsName(string $group = ''): array
    {
        return $this->getElementsName('list', $group);
    }

    public function getSearchElementsName(string $group = ''): array
    {
        return $this->getElementsName('search', $group);
    }

    public function getAddElementsName(string $group = ''): array
    {
        return $this->getElementsName('add', $group);
    }

    public function getEditElementsName(string $group = ''): array
    {
        return $this->getElementsName('edit', $group);
    }

    /**
     * @param string $group
     *
     * @return array
     */
    public function getDeleteElementsName($group = '')
    {
        return $this->getElementsName('delete', $group);
    }

    public function getSendElementsName(string $group = ''): array
    {
        return $this->getElementsName('send', $group);
    }

    public function getFinishElementsName(string $group = ''): array
    {
        return $this->getElementsName('finish', $group);
    }

    public function getAfterFinishElementsName(string $group = ''): array
    {
        return $this->getElementsName('afterfinish', $group);
    }

    public function getElementValue(string $type, string $element, string $option, string $group = ''): mixed
    {
        if ($group === '') {
            if (isset($this->ddm['elements'][$type][$element][$option])) {
                return $this->ddm['elements'][$type][$element][$option];
            }
            if ($type === 'view') {
                $type = 'preview';
                if (isset($this->ddm['elements'][$type][$element][$option])) {
                    return $this->ddm['elements'][$type][$element][$option];
                }
            }
        } else {
            if (isset($this->ddm['elements'][$type][$element][$group][$option])) {
                return $this->ddm['elements'][$type][$element][$group][$option];
            }
            if ($type === 'view') {
                $type = 'preview';
                if (isset($this->ddm['elements'][$type][$element][$group][$option])) {
                    return $this->ddm['elements'][$type][$element][$group][$option];
                }
            }
        }

        return '';
    }

    public function getViewElementValue(string $element, string $option): mixed
    {
        return $this->getElementValue('view', $element, $option, '');
    }

    public function getViewElementOption(string $element, string $option): mixed
    {
        return $this->getElementValue('view', $element, $option, 'options');
    }

    public function getListElementValue(string $element, string $option): mixed
    {
        return $this->getElementValue('list', $element, $option, '');
    }

    public function getListElementOption(string $element, string $option): mixed
    {
        return $this->getElementValue('list', $element, $option, 'options');
    }

    public function getSearchElementValue(string $element, string $option): mixed
    {
        return $this->getElementValue('search', $element, $option, '');
    }

    public function getSearchElementOption(string $element, string $option): mixed
    {
        return $this->getElementValue('search', $element, $option, 'options');
    }

    public function getSearchElementValidation(string $element, string $option): mixed
    {
        return $this->getElementValue('search', $element, $option, 'validation');
    }

    public function getAddElementValue(string $element, string $option): mixed
    {
        return $this->getElementValue('add', $element, $option, '');
    }

    public function getAddElementOption(string $element, string $option): mixed
    {
        return $this->getElementValue('add', $element, $option, 'options');
    }

    public function getAddElementValidation(string $element, string $option): mixed
    {
        return $this->getElementValue('add', $element, $option, 'validation');
    }

    public function getEditElementValue(string $element, string $option): mixed
    {
        return $this->getElementValue('edit', $element, $option, '');
    }

    public function getEditElementOption(string $element, string $option): mixed
    {
        return $this->getElementValue('edit', $element, $option, 'options');
    }

    public function getEditElementValidation(string $element, string $option): mixed
    {
        return $this->getElementValue('edit', $element, $option, 'validation');
    }

    public function getDeleteElementValue(string $element, string $option): mixed
    {
        return $this->getElementValue('delete', $element, $option, '');
    }

    public function getDeleteElementOption(string $element, string $option): mixed
    {
        return $this->getElementValue('delete', $element, $option, 'options');
    }

    public function getDeleteElementValidation(string $element, string $option): mixed
    {
        return $this->getElementValue('delete', $element, $option, 'validation');
    }

    /**
     */
    public function getSendElementValue(string $element, string $option): mixed
    {
        return $this->getElementValue('send', $element, $option, '');
    }

    /**
     */
    public function getSendElementOption(string $element, string $option): mixed
    {
        return $this->getElementValue('send', $element, $option, 'options');
    }

    /**
     */
    public function getSendElementValidation(string $element, string $option): mixed
    {
        return $this->getElementValue('send', $element, $option, 'validation');
    }

    /**
     */
    public function getFinishElementValue(string $element, string $option): mixed
    {
        return $this->getElementValue('finish', $element, $option, '');
    }

    /**
     */
    public function getFinishElementOption(string $element, string $option): mixed
    {
        return $this->getElementValue('finish', $element, $option, 'options');
    }

    /**
     */
    public function getFinishElementValidation(string $element, string $option): mixed
    {
        return $this->getElementValue('finish', $element, $option, 'validation');
    }

    /**
     */
    public function getAfterFinishElementValue(string $element, string $option): mixed
    {
        return $this->getElementValue('afterfinish', $element, $option, '');
    }

    /**
     */
    public function getAfterFinishElementOption(string $element, string $option): mixed
    {
        return $this->getElementValue('afterfinish', $element, $option, 'options');
    }

    /**
     */
    public function getAfterFinishElementValidation(string $element, string $option): mixed
    {
        return $this->getElementValue('afterfinish', $element, $option, 'validation');
    }

    /**
     */
    public function setElementValue(
        string $type,
        string $element,
        string $option,
        mixed $value,
        string $group = ''
    ): bool {
        if ($group === '') {
            $this->ddm['elements'][$type][$element][$option] = $value;

            return true;
        }
        $this->ddm['elements'][$type][$element][$group][$option] = $value;

        return true;


        return false;
    }

    /**
     */
    public function setViewElementValue(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('view', $element, $option, $value, '');
    }

    /**
     */
    public function setViewElementOption(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('view', $element, $option, $value, 'options');
    }

    /**
     */
    public function setListElementValue(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('list', $element, $option, $value, '');
    }

    /**
     */
    public function setListElementOption(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('list', $element, $option, $value, 'options');
    }

    /**
     */
    public function setSearchElementValue(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('search', $element, $option, $value, '');
    }

    /**
     */
    public function setSearchElementOption(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('search', $element, $option, $value, 'options');
    }

    /**
     */
    public function setSearchElementValidation(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('search', $element, $option, $value, 'validation');
    }

    /**
     */
    public function setAddElementValue(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('add', $element, $option, $value, '');
    }

    /**
     */
    public function setAddElementOption(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('add', $element, $option, $value, 'options');
    }

    /**
     */
    public function setAddElementValidation(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('add', $element, $option, $value, 'validation');
    }

    /**
     */
    public function setEditElementValue(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('edit', $element, $option, $value, '');
    }

    /**
     */
    public function setEditElementOption(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('edit', $element, $option, $value, 'options');
    }

    /**
     */
    public function setEditElementValidation(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('edit', $element, $option, $value, 'validation');
    }

    /**
     */
    public function setDeleteElementValue(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('delete', $element, $option, $value, '');
    }

    /**
     */
    public function setDeleteElementOption(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('delete', $element, $option, $value, 'options');
    }

    /**
     */
    public function setDeleteElementValidation(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('delete', $element, $option, $value, 'validation');
    }

    /**
     */
    public function setSendElementValue(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('send', $element, $option, $value, '');
    }

    /**
     */
    public function setSendElementOption(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('send', $element, $option, $value, 'options');
    }

    /**
     */
    public function setSendElementValidation(string $element, string $option, mixed $value): bool
    {
        return $this->setElementValue('send', $element, $option, $value, 'validation');
    }

    /**
     */
    public function removeElements(): bool
    {
        if (isset($this->ddm['elements'])) {
            unset($this->ddm['elements']);

            return true;
        }

        return false;
    }

    /**
     */
    public function removeElement(string $type, string $element): bool
    {
        if (isset($this->ddm['elements'][$type][$element])) {
            unset($this->ddm['elements'][$type][$element]);

            return true;
        }

        return false;
    }

    /**
     */
    public function removeElementValue(string $type, string $element, string $option, string $group = ''): bool
    {
        if ($group === '') {
            if (isset($this->ddm['elements'][$type][$element][$option])) {
                unset($this->ddm['elements'][$type][$element][$option]);

                return true;
            }
        } else {
            if (isset($this->ddm['elements'][$type][$element][$group][$option])) {
                unset($this->ddm['elements'][$type][$element][$group][$option]);

                return true;
            }
        }

        return false;
    }

    /**
     */
    public function removeViewElementValue(string $element, string $option): bool
    {
        return $this->removeElementValue('view', $element, $option, '');
    }

    /**
     */
    public function removeViewElementOption(string $element, string $option): bool
    {
        return $this->removeElementValue('view', $element, $option, 'options');
    }

    /**
     */
    public function removeListElementValue(string $element, string $option): bool
    {
        return $this->removeElementValue('list', $element, $option, '');
    }

    /**
     */
    public function removeListElementOption(string $element, string $option): bool
    {
        return $this->removeElementValue('list', $element, $option, 'options');
    }

    /**
     */
    public function removeSearchElementValue(string $element, string $option): bool
    {
        return $this->removeElementValue('search', $element, $option, '');
    }

    /**
     */
    public function removeSearchElementOption(string $element, string $option): bool
    {
        return $this->removeElementValue('search', $element, $option, 'options');
    }

    /**
     */
    public function removeSearchElementValidation(string $element, string $option): bool
    {
        return $this->removeElementValue('search', $element, $option, 'validation');
    }

    /**
     */
    public function removeAddElementValue(string $element, string $option): bool
    {
        return $this->removeElementValue('add', $element, $option, '');
    }

    /**
     */
    public function removeAddElementOption(string $element, string $option): bool
    {
        return $this->removeElementValue('add', $element, $option, 'options');
    }

    /**
     */
    public function removeAddElementValidation(string $element, string $option): bool
    {
        return $this->removeElementValue('add', $element, $option, 'validation');
    }

    /**
     */
    public function removeEditElementValue(string $element, string $option): bool
    {
        return $this->removeElementValue('edit', $element, $option, '');
    }

    /**
     */
    public function removeEditElementOption(string $element, string $option): bool
    {
        return $this->removeElementValue('edit', $element, $option, 'options');
    }

    /**
     */
    public function removeEditElementValidation(string $element, string $option): bool
    {
        return $this->removeElementValue('edit', $element, $option, 'validation');
    }

    /**
     */
    public function removeDeleteElementValue(string $element, string $option): bool
    {
        return $this->removeElementValue('delete', $element, $option, '');
    }

    /**
     */
    public function removeDeleteElementOption(string $element, string $option): bool
    {
        return $this->removeElementValue('delete', $element, $option, 'options');
    }

    /**
     */
    public function removeDeleteElementValidation(string $element, string $option): bool
    {
        return $this->removeElementValue('delete', $element, $option, 'validation');
    }

    /**
     */
    public function removeSendElementValue(string $element, string $option): bool
    {
        return $this->removeElementValue('send', $element, $option, '');
    }

    /**
     */
    public function removeSendElementOption(string $element, string $option): bool
    {
        return $this->removeElementValue('send', $element, $option, 'options');
    }

    /**
     */
    public function removeSendElementValidation(string $element, string $option): bool
    {
        return $this->removeElementValue('send', $element, $option, 'validation');
    }

    /**
     */
    public function setStorageValues(string $type, array $elements): bool
    {
        $this->ddm['storage'][$type] = $elements;

        return true;
    }

    /**
     */
    public function setListStorageValues(array $elements): bool
    {
        return $this->setStorageValues('list', $elements);
    }

    /**
     */
    public function getStorageValue(string $type, string $element): mixed
    {
        if (isset($this->ddm['storage'][$type][$element])) {
            return $this->ddm['storage'][$type][$element];
        }

        return '';
    }

    /**
     */
    public function getListStorageValue(string $element): mixed
    {
        return $this->getStorageValue('list', $element);
    }

    /**
     */
    public function setElementStorage(string $element, mixed $value, string $option = 'default'): bool
    {
        if (!isset($this->ddm['storage']['data'])) {
            $this->ddm['storage']['data'] = [];
        }
        if (!isset($this->ddm['storage']['data'][$option])) {
            $this->ddm['storage']['data'][$option] = [];
        }
        $this->ddm['storage']['data'][$option][$element] = $value;

        return true;
    }

    /**
     */
    public function setSearchElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'search');
    }

    /**
     */
    public function setDataBaseElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'database');
    }

    /**
     */
    public function setAddElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'add');
    }

    /**
     */
    public function setDoAddElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'doadd');
    }

    /**
     */
    public function setEditElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'edit');
    }

    /**
     */
    public function setDoEditElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'doedit');
    }

    /**
     */
    public function setDeleteElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'delete');
    }

    /**
     */
    public function setDoDeleteElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'dodelete');
    }

    /**
     */
    public function setSendElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'send');
    }

    /**
     */
    public function setDoSendElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'dosend');
    }

    /**
     */
    public function setFilterElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'filter');
    }

    /**
     */
    public function setFilterErrorElementStorage(string $element, mixed $value): bool
    {
        return $this->setElementStorage($element, $value, 'filtererror');
    }

    /**
     */
    public function setIndexElementStorage(mixed $value): bool
    {
        return $this->setElementStorage('index', $value, 'index');
    }

    /**
     */
    public function getElementStorage(string $element, string $option = 'default'): mixed
    {
        if (isset($this->ddm['storage']['data'][$option][$element])) {
            return $this->ddm['storage']['data'][$option][$element];
        }

        return '';
    }

    /**
     */
    public function getSearchElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'search');
    }

    /**
     */
    public function getAddElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'add');
    }

    /**
     */
    public function getDoAddElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'doadd');
    }

    /**
     */
    public function getEditElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'edit');
    }

    /**
     */
    public function getDoEditElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'doedit');
    }

    /**
     */
    public function getDeleteElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'delete');
    }

    /**
     */
    public function getDoDeleteElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'dodelete');
    }

    /**
     */
    public function getSendElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'send');
    }

    /**
     */
    public function getDoSendElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'dosend');
    }

    /**
     */
    public function getDataBaseElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'database');
    }

    /**
     */
    public function getFilterElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'filter');
    }

    /**
     */
    public function getFilterErrorElementStorage(string $element): mixed
    {
        return $this->getElementStorage($element, 'filtererror');
    }

    /**
     */
    public function getIndexElementStorage(): mixed
    {
        if ($this->getGroupOption('db_index_type', 'database') === 'string') {
            return (string)$this->getElementStorage('index', 'index');
        }

        return (int)$this->getElementStorage('index', 'index');
    }

    /**
     */
    public function getSearchElementsStorage(): array
    {
        return $this->getElementsStorage('search');
    }

    /**
     */
    public function getDataBaseElementsStorage(): array
    {
        return $this->getElementsStorage('database');
    }

    /**
     */
    public function getElementsStorage(string $option = 'default'): array
    {
        if (isset($this->ddm['storage']['data'][$option])) {
            return $this->ddm['storage']['data'][$option];
        }

        return [];
    }

    /**
     */
    public function clearSearchElementStorage(string $element): bool
    {
        return $this->clearElementStorage($element, 'search');
    }

    /**
     */
    public function clearElementStorage(string $element, string $option = 'default'): bool
    {
        if (isset($this->ddm['storage']['data'][$option][$element])) {
            unset($this->ddm['storage']['data'][$option][$element]);
        }

        return true;
    }

    /**
     */
    public function getDirectModule(): string
    {
        if ($this->getGroupOption('module', 'direct') !== '') {
            return $this->getGroupOption('module', 'direct');
        }

        return 'current';
    }

    /**
     */
    public function getDirectParameters(): string
    {
        $_paramters = [];
        if (($this->getGroupOption('parameters', 'direct') !== '') && (\is_array(
            $this->getGroupOption('parameters', 'direct')
        )) && (\count($this->getGroupOption('parameters', 'direct')) > 0)
        ) {
            foreach ($this->getGroupOption('parameters', 'direct') as $element => $value) {
                $_paramters[] = $element . '=' . $value;
            }
        }

        return implode('&', $_paramters);
    }

    /**
     */
    public function direct(string $module = 'current', string $parameter = ''): void
    {
        $this->storeParameters();
        Network::directHeader($this->getTemplate()->buildhrefLink($module, $parameter));
    }

    /**
     */
    public function addAjaxFunction(string $name, string $value): void
    {
        if (!isset($this->ddm['ajaxfunction'])) {
            $this->ddm['ajaxfunction'] = [];
        }
        $this->ddm['ajaxfunction'][$name] = $value;
    }

    /**
     * @return array|mixed
     */
    public function getAjaxFunction(string $name)
    {
        if (isset($this->ddm['ajaxfunction'][$name])) {
            return $this->ddm['ajaxfunction'][$name];
        }

        return [];
    }

    /**
     */
    public function getAjaxFunctions(): array
    {
        if (isset($this->ddm['ajaxfunction'])) {
            return $this->ddm['ajaxfunction'];
        }

        return [];
    }

    /**
     */
    public function removeAjaxFunction(string $name): bool
    {
        if (!isset($this->ddm['ajaxfunction'])) {
            $this->ddm['ajaxfunction'] = [];
        }
        if (isset($this->ddm['ajaxfunction'][$name])) {
            unset($this->ddm['ajaxfunction'][$name]);

            return true;
        }

        return false;
    }

    /**
     * @return $this
     */
    public function setParameter(string $name, string|int $value): self
    {
        return $this->addParameter($name, $value);
    }

    /**
     * @return $this
     */
    public function addParameter(string $name, string|int|array $value): self
    {
        if (!isset($this->ddm['parameters'])) {
            $this->ddm['parameters'] = [];
        }
        $this->ddm['parameters'][$name] = $value;

        return $this;
    }

    /**
     */
    public function getParameter(string $name): string|int|array
    {
        if (isset($this->ddm['parameters'][$name])) {
            return $this->ddm['parameters'][$name];
        }
        if ($name === 'ddm_search_data') {
            return [];
        }

        return '';
    }

    /**
     */
    public function removeParameter(string $name): bool
    {
        if (!isset($this->ddm['parameters'])) {
            $this->ddm['parameters'] = [];
        }
        if (isset($this->ddm['parameters'][$name])) {
            unset($this->ddm['parameters'][$name]);

            return true;
        }

        return false;
    }

    public function storeParameters(): bool
    {
        Session::setArrayVar('ddm4_' . $this->name . '_parameters', $this->ddm['parameters']);

        return true;
    }

    public function readParameters(): bool
    {
        $this->ddm['parameters'] = Session::getArrayVar('ddm4_' . $this->name . '_parameters');
        if ($this->ddm['parameters'] === null) {
            $this->ddm['parameters'] = [];
        }

        return true;
    }

    /**
     */
    public function parseElementPHP(string $type, string $element, array $values): bool
    {
        return $this->parseElement($element, $values, $type, 'content', 'php');
    }

    /**
     */
    public function parseViewElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('view', $element, $values);
    }

    /**
     */
    public function parseListElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('list', $element, $values);
    }

    /**
     */
    public function parseFormSearchElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('formsearch', $element, $values);
    }

    /**
     */
    public function parseParserSearchElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('parsersearch', $element, $values);
    }

    /**
     */
    public function parseFormAddElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('formadd', $element, $values);
    }

    /**
     */
    public function parseParserAddElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('parseradd', $element, $values);
    }

    /**
     */
    public function parseFilterAddElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('filteradd', $element, $values);
    }

    /**
     */
    public function parseFinishAddElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('finishadd', $element, $values);
    }

    /**
     */
    public function parseFormEditElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('formedit', $element, $values);
    }

    /**
     */
    public function parseParserEditElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('parseredit', $element, $values);
    }

    /**
     */
    public function parseFilterEditElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('filteredit', $element, $values);
    }

    /**
     */
    public function parseFinishEditElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('finishedit', $element, $values);
    }

    /**
     */
    public function parseFormDeleteElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('formdelete', $element, $values);
    }

    /**
     */
    public function parseParserDeleteElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('parserdelete', $element, $values);
    }

    /**
     */
    public function parseFinishDeleteElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('finishdelete', $element, $values);
    }

    /**
     */
    public function parseFinishSearchElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('finishsearch', $element, $values);
    }

    /**
     */
    public function parseFormSendElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('formsend', $element, $values);
    }

    /**
     */
    public function parseParserSendElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('parsersend', $element, $values);
    }

    /**
     */
    public function parseFilterSendElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('filtersend', $element, $values);
    }

    /**
     */
    public function parseFinishSendElementPHP(string $element, array $values): bool
    {
        return $this->parseElementPHP('finishsend', $element, $values);
    }

    /**
     */
    public function setLock(string $key, string $value, int $user_id, string $connection = ''): bool
    {
        $this->clearLock($connection);
        $Qgetlock = self::getConnection($connection);
        $Qgetlock->prepare(
            'SELECT * FROM :table_ddm4_lock: WHERE lock_group=:lock_group: AND lock_key=:lock_key: AND lock_value=:lock_value:'
        );
        $Qgetlock->bindTable(':table_ddm4_lock:', 'ddm4_lock');
        $Qgetlock->bindString(':lock_group:', $this->getName());
        $Qgetlock->bindString(':lock_key:', $key);
        $Qgetlock->bindString(':lock_value:', $value);
        if ($Qgetlock->exec() === 1) {
            $Qgetlock = self::getConnection($connection);
            $Qgetlock->prepare(
                'SELECT * FROM :table_ddm4_lock: WHERE lock_group=:lock_group: AND lock_key=:lock_key: AND lock_value=:lock_value: AND user_id=:user_id:'
            );
            $Qgetlock->bindTable(':table_ddm4_lock:', 'ddm4_lock');
            $Qgetlock->bindString(':lock_group:', $this->getName());
            $Qgetlock->bindString(':lock_key:', $key);
            $Qgetlock->bindString(':lock_value:', $value);
            $Qgetlock->bindInt(':user_id:', $user_id);
            if ($Qgetlock->exec() === 1) {
                $Qlock = self::getConnection($connection);
                $Qlock->prepare(
                    'UPDATE :table_ddm4_lock: SET lock_time=:lock_time: WHERE lock_group=:lock_group: AND lock_key=:lock_key: AND lock_value=:lock_value: AND user_id=:user_id:'
                );
                $Qlock->bindTable(':table_ddm4_lock:', 'ddm4_lock');
                $Qlock->bindString(':lock_group:', $this->getName());
                $Qlock->bindString(':lock_key:', $key);
                $Qlock->bindString(':lock_value:', $value);
                $Qlock->bindInt(':user_id:', $user_id);
                $Qlock->bindInt(':lock_time:', time());
                $Qlock->execute();
            } else {
                return false;
            }
        } else {
            $Qlock = self::getConnection($connection);
            $Qlock->prepare(
                'INSERT INTO :table_ddm4_lock: (lock_group, lock_key, lock_value, user_id, lock_time) VALUES (:lock_group:, :lock_key:, :lock_value:, :user_id:, :lock_time:)'
            );
            $Qlock->bindTable(':table_ddm4_lock:', 'ddm4_lock');
            $Qlock->bindString(':lock_group:', $this->getName());
            $Qlock->bindString(':lock_key:', $key);
            $Qlock->bindString(':lock_value:', $value);
            $Qlock->bindInt(':user_id:', $user_id);
            $Qlock->bindInt(':lock_time:', time());
            $Qlock->execute();
        }

        return true;
    }

    /**
     */
    public function getLockUserId(string $key, string $value, string $connection = ''): int
    {
        $Qgetlock = self::getConnection($connection);
        $Qgetlock->prepare(
            'SELECT user_id FROM :table_ddm4_lock: WHERE lock_group=:lock_group: AND lock_key=:lock_key: AND lock_value=:lock_value:'
        );
        $Qgetlock->bindTable(':table_ddm4_lock:', 'ddm4_lock');
        $Qgetlock->bindString(':lock_group:', $this->getName());
        $Qgetlock->bindString(':lock_key:', $key);
        $Qgetlock->bindString(':lock_value:', $value);
        if ($Qgetlock->exec() === 1) {
            $Qgetlock->fetch();

            return $Qgetlock->getInt('user_id');
        }

        return 0;
    }

    /**
     */
    public function clearLock(string $connection = ''): bool
    {
        $Qclearlock = self::getConnection($connection);
        $Qclearlock->prepare('DELETE FROM :table_ddm4_lock: WHERE lock_time<:lock_time:');
        $Qclearlock->bindTable(':table_ddm4_lock:', 'ddm4_lock');
        $Qclearlock->bindInt(':lock_time:', (time() - 10));
        $Qclearlock->execute();

        return true;
    }

    /**
     */
    public function parseElementTPL(string $type, string $position, string $element, array $values): string|bool
    {
        return $this->parseElement($element, $values, $type, $position, 'tpl');
    }

    /**
     */
    public function parseViewElementTPL(string $element, array $values): string|bool
    {
        return $this->parseElementTPL('view', 'content', $element, $values);
    }

    /**
     */
    public function parseListElementTPL(string $element, array $values): string|bool
    {
        return $this->parseElementTPL('list', 'content', $element, $values);
    }

    /**
     */
    public function parseListHeaderElementTPL(string $element, array $values): string|bool
    {
        return $this->parseElementTPL('list', 'header', $element, $values);
    }

    /**
     */
    public function parseFormSearchElementTPL(string $element, array $values): string|bool
    {
        return $this->parseElementTPL('formsearch', 'content', $element, $values);
    }

    /**
     */
    public function parseFormAddElementTPL(string $element, array $values): string|bool
    {
        return $this->parseElementTPL('formadd', 'content', $element, $values);
    }

    /**
     */
    public function parseFormEditElementTPL(string $element, array $values): string|bool
    {
        return $this->parseElementTPL('formedit', 'content', $element, $values);
    }

    /**
     */
    public function parseFormDeleteElementTPL(string $element, array $values): string|bool
    {
        return $this->parseElementTPL('formdelete', 'content', $element, $values);
    }

    /**
     */
    public function parseFormSendElementTPL(string $element, array $values): string|bool
    {
        return $this->parseElementTPL('formsend', 'content', $element, $values);
    }

    /**
     */
    public function parseElement(
        string $element,
        array $values,
        string $type = '',
        string $file = 'content',
        string $script = 'php'
    ): string|bool {
        if (!isset($values['module'])) {
            return false;
        }
        if ($script === 'tpl') {
            ob_start();
            if ($this->ddm['options']['theme'] !== 'default') {
                $file = Settings::getStringVar(
                    'settings_abspath'
                ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . $type . \DIRECTORY_SEPARATOR . $values['module'] . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . $this->ddm['options']['theme'] . \DIRECTORY_SEPARATOR . 'content.tpl.php';
                $file_core = Settings::getStringVar(
                    'settings_abspath'
                ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . $type . \DIRECTORY_SEPARATOR . $values['module'] . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . $this->ddm['options']['theme'] . \DIRECTORY_SEPARATOR . 'content.tpl.php';
                if (file_exists($file)) {
                    include $file;
                } elseif ($file_core) {
                    include $file_core;
                } else {
                    $file = Settings::getStringVar(
                        'settings_abspath'
                    ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . $type . \DIRECTORY_SEPARATOR . $values['module'] . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'content.tpl.php';
                    $file_core = Settings::getStringVar(
                        'settings_abspath'
                    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . $type . \DIRECTORY_SEPARATOR . $values['module'] . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'content.tpl.php';
                    if (file_exists($file)) {
                        include $file;
                    } elseif ($file_core) {
                        include $file_core;
                    }
                }
            } else {
                $file = Settings::getStringVar(
                    'settings_abspath'
                ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . $type . \DIRECTORY_SEPARATOR . $values['module'] . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'content.tpl.php';
                $file_core = Settings::getStringVar(
                    'settings_abspath'
                ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . $type . \DIRECTORY_SEPARATOR . $values['module'] . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'content.tpl.php';
                if (file_exists($file)) {
                    include $file;
                } elseif (file_exists($file_core)) {
                    include $file_core;
                }
            }
            $contents = ob_get_contents();
            ob_end_clean();

            return $contents;
        }
        $file = Settings::getStringVar(
            'settings_abspath'
        ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . $type . \DIRECTORY_SEPARATOR . $values['module'] . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
        $file_core = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . $type . \DIRECTORY_SEPARATOR . $values['module'] . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
        if (file_exists($file)) {
            include $file;
        } elseif (file_exists($file_core)) {
            include $file_core;
        }

        return true;
    }

    /**
     */
    public function runDDMPHP(): bool
    {
        $engine = $this->getGroupOption('engine');
        $file = Settings::getStringVar(
            'settings_abspath'
        ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'loader' . \DIRECTORY_SEPARATOR . 'run' . \DIRECTORY_SEPARATOR . $this->getName(
        ) . '.inc.php';
        $file_core = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'loader' . \DIRECTORY_SEPARATOR . 'run' . \DIRECTORY_SEPARATOR . $this->getName(
        ) . '.inc.php';
        if (file_exists($file)) {
            include $file;
        } elseif (file_exists($file_core)) {
            include $file_core;
        }
        $file = Settings::getStringVar(
            'settings_abspath'
        ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'engine' . \DIRECTORY_SEPARATOR . $engine . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
        $file_core = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'engine' . \DIRECTORY_SEPARATOR . $engine . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
        if (file_exists($file)) {
            include $file;
        } elseif (file_exists($file_core)) {
            include $file_core;
        }

        return true;
    }

    /**
     */
    public function runDDMTPL(): string
    {
        $engine = $this->getGroupOption('engine');

        ob_start();
        if ($this->ddm['options']['theme'] !== 'default') {
            $file = Settings::getStringVar(
                'settings_abspath'
            ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'engine' . \DIRECTORY_SEPARATOR . $engine . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . $this->ddm['options']['theme'] . \DIRECTORY_SEPARATOR . 'content.tpl.php';
            $file_core = Settings::getStringVar(
                'settings_abspath'
            ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'engine' . \DIRECTORY_SEPARATOR . $engine . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . $this->ddm['options']['theme'] . \DIRECTORY_SEPARATOR . 'content.tpl.php';
            if (file_exists($file)) {
                include $file;
            } elseif (file_exists($file_core)) {
                include $file_core;
            } else {
                $file = Settings::getStringVar(
                    'settings_abspath'
                ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'engine' . \DIRECTORY_SEPARATOR . $engine . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'content.tpl.php';
                $file_core = Settings::getStringVar(
                    'settings_abspath'
                ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'engine' . \DIRECTORY_SEPARATOR . $engine . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'content.tpl.php';
                if (file_exists($file)) {
                    include $file;
                } elseif (file_exists($file_core)) {
                    include $file_core;
                }
            }
        } else {
            $file = Settings::getStringVar(
                'settings_abspath'
            ) . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'engine' . \DIRECTORY_SEPARATOR . $engine . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'content.tpl.php';
            $file_core = Settings::getStringVar(
                'settings_abspath'
            ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'engine' . \DIRECTORY_SEPARATOR . $engine . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'content.tpl.php';
            if (file_exists($file)) {
                include $file;
            } elseif (file_exists($file_core)) {
                include $file_core;
            }
        }
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }
}
