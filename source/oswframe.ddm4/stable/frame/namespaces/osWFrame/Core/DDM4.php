<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

namespace osWFrame\Core;

class DDM4 {

	use BaseStaticTrait;
	use BaseConnectionTrait;
	use BaseTemplateBridgeTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=0;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=3;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var string
	 */
	private string $name='';

	/**
	 * @var array
	 */
	public array $ddm=[];

	/**
	 * DDM4 constructor.
	 *
	 * @param object $Template
	 * @param string $name
	 * @param array $options
	 */
	public function __construct(object $Template, string $name='ddm4_group', array $options=[]) {
		$this->name=$name;
		$this->setTemplate($Template);
		$this->addGroup($options);
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function reset(string $name='ddm4_group'):bool {
		$this->name=$name;
		$this->ddm=[];

		return true;
	}

	/**
	 * @return string
	 */
	public function getName():string {
		return str_replace('_log_log', '_log', $this->name);
	}

	/**
	 * @param array $options
	 * @return bool
	 */
	public function addGroup(array $options):bool {
		if (isset($this->ddm['options'])) {
			return false;
		}
		if (!isset($options['messages'])) {
			$options['messages']=[];
		}
		$options['messages']=$this->loadDefaultMessages($options['messages']);
		$this->ddm['options']=$options;
		if ((!isset($this->ddm['options']['layout_loaded']))||($this->ddm['options']['layout_loaded']!==true)) {
			if (!isset($this->ddm['options']['layout'])) {
				$this->ddm['options']['layout']='default';
			}
			$this->ddm['options']['layout_loaded']=true;
			$version=self::getVersion();
			$dir=strtolower(self::getClassName());
			$name=$version.'-'.$this->ddm['options']['layout'].'.resource';
			if (Resource::existsResource($dir, $name)!==true) {
				$files=[];
				foreach (glob(Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'ddm4'.DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.$this->ddm['options']['layout'].DIRECTORY_SEPARATOR.'*.*') as $filename) {
					$files[]=str_replace(Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'ddm4'.DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.$this->ddm['options']['layout'].DIRECTORY_SEPARATOR, '', $filename);
				}
				Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'ddm4'.DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.$this->ddm['options']['layout'].DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.'-'.$this->ddm['options']['layout'].DIRECTORY_SEPARATOR, $files);
				$path=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.'-'.$this->ddm['options']['layout'].DIRECTORY_SEPARATOR;
				$content=file_get_contents($path.DIRECTORY_SEPARATOR.'default.css');
				$content=str_replace('url("/modules/vis2/img/loader.gif")', 'url("/'.$path.'loader.gif")', $content);
				file_put_contents($path.DIRECTORY_SEPARATOR.'default.css', $content);
				Resource::writeResource($dir, $name, time());
			}

			foreach (glob(Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.'-'.$this->ddm['options']['layout'].DIRECTORY_SEPARATOR.'*.css') as $filename) {
				$this->getTemplate()->addCSSFileHead($filename);
			}
			foreach (glob(Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.'-'.$this->ddm['options']['layout'].DIRECTORY_SEPARATOR.'*.js') as $filename) {
				$this->getTemplate()->addJSFileHead($filename);
			}
		}
		$file=Settings::getStringVar('settings_abspath').'frame/ddm4/loader/group/'.$this->getName().'.inc.php';
		if (file_exists($file)) {
			include $file;
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getElementsArrayInit():array {
		return ['preview'=>[], 'view'=>[], 'data'=>[], 'send'=>[], 'finish'=>[], 'afterfinish'=>[]];
	}

	/**
	 * @param array $messages
	 * @return string[]
	 */
	public function loadDefaultMessages(array $messages):array {
		$default_messages=[];
		$default_messages['data_options']='Optionen';
		$default_messages['form_submit']='Absenden';
		$default_messages['form_search']='Suchen';
		$default_messages['form_add']='Erstellen';
		$default_messages['form_edit']='Bearbeiten';
		$default_messages['form_delete']='Löschen';
		$default_messages['form_reset']='Zurücksetzen';
		$default_messages['form_close']='Schließen';
		$default_messages['form_cancel']='Abbrechen';
		$default_messages['form_delete']='Löschen';
		$default_messages['form_send']='Absenden';
		$default_messages['data_search']='Suchen';
		$default_messages['data_add']='Erstellen';
		$default_messages['data_edit']='Bearbeiten';
		$default_messages['data_delete']='Löschen';
		$default_messages['data_log']='Log';
		$default_messages['form_title_required_icon']='*';
		$default_messages['form_title_pages']='Seiten';
		$default_messages['form_title_pages_single']='Seite';
		$default_messages['form_title_pages_multi']='Seiten';
		$default_messages['form_title_counter']='Datensätze $elements_from$ - $elements_to$ ($elements_all$ insgesamt)';
		$default_messages['form_title_counter_single']='Datensatz $elements_from$ - $elements_to$ ($elements_all$ insgesamt)';
		$default_messages['form_title_counter_multi']='Datensätze $elements_from$ - $elements_to$ ($elements_all$ insgesamt)';
		$default_messages['form_title_asc']='Aufsteigend sortieren';
		$default_messages['form_title_desc']='Absteigend sortieren';
		$default_messages['form_title_sortorder_delete']='Sortierung entfernen';
		$default_messages['form_title_closer']=':';
		$default_messages['form_required_notice']='* Pflichtfeld';
		$default_messages['form_required_notice_multi']='* Pflichtfelder';
		$default_messages['form_error']='Eingabefehler';
		$default_messages['data_noresults']='Keine Elemente vorhanden';
		$default_messages['text_hidden']='Wird nicht angezeigt';
		$default_messages['text_char']='Zeichen';
		$default_messages['text_chars']='Zeichen';
		$default_messages['text_all']='Alle';
		$default_messages['text_yes']='Ja';
		$default_messages['text_no']='Nein';
		$default_messages['text_blank']='---';
		$default_messages['text_action']='Aktion';
		$default_messages['text_file_select']='Datei auswählen';
		$default_messages['text_file_view']='Datei anzeigen';
		$default_messages['text_file_delete']='Datei löschen';
		$default_messages['text_image_select']='Bild auswählen';
		$default_messages['text_image_view']='Bild anzeigen';
		$default_messages['text_image_delete']='Bild löschen';
		$default_messages['text_image_show']='Bild einblenden';
		$default_messages['text_image_edit']='Bild bearbeiten';
		$default_messages['log_char_true']='<i class="fas fa-plus-square"></i>';
		$default_messages['log_char_false']='<i class="fas fa-minus-square"></i>';
		$default_messages['text_clock']='Uhr';
		$default_messages['text_search']='Suche';
		$default_messages['text_filter']='Filter';
		$default_messages['text_selected']='Ausgewählte';
		$default_messages['text_notselected']='Nicht Ausgewählte';
		$default_messages['text_selectall']='Alle auswählen';
		$default_messages['text_deselectall']='Keins auswählen';
		$default_messages['text_invertselection']='Auswahl umkehren';
		$default_messages['text_all']='Alle';
		$default_messages['create_time']='Erstellt am';
		$default_messages['create_user']='Erstellt von';
		$default_messages['update_time']='Geändert am';
		$default_messages['update_user']='Geändert von';
		$default_messages['search_title']='Erweiterte Suche';
		$default_messages['edit_search_title']='Erweiterte Suche bearbeiten';
		$default_messages['back_title']='Zurück zur Übersicht';
		$default_messages['sortorder_title']='Sortierung';
		$default_messages['createupdate_title']='Datensatzinformationen';
		$default_messages['log_title']='Datensatzhistorie';
		$default_messages['send_title']='Datensatz übermitteln';
		$default_messages['send_success_title']='Datensatz wurde erfolgreich übermittelt';
		$default_messages['add_title']='Neuen Datensatz erstellen';
		$default_messages['add_success_title']='Datensatz wurde erfolgreich erstellt';
		$default_messages['add_error_title']='Datensatz konnte nicht erstellt werden';
		$default_messages['edit_title']='Datensatz bearbeiten';
		$default_messages['edit_load_error_title']='Datensatz wurde nicht gefunden';
		$default_messages['edit_success_title']='Datensatz wurde erfolgreich bearbeitet';
		$default_messages['edit_error_title']='Datensatz konnte nicht bearbeitet werden';
		$default_messages['delete_title']='Datensatz löschen';
		$default_messages['delete_load_error_title']='Datensatz wurde nicht gefunden';
		$default_messages['delete_success_title']='Datensatz wurde erfolgreich gelöscht';
		$default_messages['delete_error_title']='Datensatz konnte nicht gelöscht werden';
		$default_messages['lock_error']='Datensatz durch "$user$" gesperrt. Keine Änderungen möglich.';
		$default_messages['validation_error']='Es sind Fehler vorhanden.';
		$default_messages['validation_element_error']='Fehler bei $element_title$.';
		$default_messages['validation_element_filtererror']='Filter "$filter$" bei "$element_title$" wurde nicht gefunden.';
		$default_messages['validation_element_incorrect']='Ihre Eingabe bei "$element_title$" ist nicht korrekt.';
		$default_messages['validation_element_toshort']='Bitte korrekt angeben (Mindestens $length_min$ Zeichen)';
		$default_messages['validation_element_tolong']='Bitte korrekt angeben (Maximal $length_max$ Zeichen)';
		$default_messages['validation_element_empty']='Bitte angeben';
		$default_messages['validation_element_tosmall']='Ihre Eingabe bei "$element_title$" ist zu klein.';
		$default_messages['validation_element_tobig']='Ihre Eingabe bei "$element_title$" ist zu groß.';
		$default_messages['validation_element_regerror']='Ihre Eingabe bei "$element_title$" ist nicht korrekt.';
		$default_messages['validation_element_double']='Ihre Eingaben bei "$element_title$" stimmen nicht überein.';
		$default_messages['validation_element_unique']='Ihre Eingabe bei "$element_title$" ist bereits vorhanden.';
		$default_messages['validation_element_miss']='Ihre Eingabe bei "$element_title$" fehlt.';
		$default_messages['validation_file_uploaderror']='Die Datei bei "$element_title$" konnte nicht hochgeladen werden.';
		$default_messages['validation_file_typeerror']='Die Datei bei "$element_title$" ist vom falschen Typ.';
		$default_messages['validation_file_extensionerror']='Die Datei bei "$element_title$" hat die falsche Endung.';
		$default_messages['validation_file_tosmall']='Die Datei bei "$element_title$" ist zu klein.';
		$default_messages['validation_file_tobig']='Die Datei bei "$element_title$" ist zu groß.';
		$default_messages['validation_file_miss']='Keine Datei bei "$element_title$" hochgeladen.';
		$default_messages['validation_image_uploaderror']='Die Datei bei "$element_title$" konnte nicht hochgeladen werden.';
		$default_messages['validation_image_fileerror']='Die Datei bei "$element_title$" ist kein Bild.';
		$default_messages['validation_image_typeerror']='Die Datei bei "$element_title$" ist vom falschen Typ.';
		$default_messages['validation_image_extensionerror']='Die Datei bei "$element_title$" hat die falsche Endung.';
		$default_messages['validation_image_tosmall']='Die Datei bei "$element_title$" ist zu klein.';
		$default_messages['validation_image_tobig']='Die Datei bei "$element_title$" ist zu groß.';
		$default_messages['validation_imagewidth_tosmall']='Die Breite bei "$element_title$" ist zu klein.';
		$default_messages['validation_imagewidth_tobig']='Die Breite bei "$element_title$" ist zu groß.';
		$default_messages['validation_imageheight_tosmall']='Die Höhe bei "$element_title$" ist zu klein.';
		$default_messages['validation_imageheight_tobig']='Die Höhe bei "$element_title$" ist zu groß.';
		$default_messages['validation_image_miss']='Keine Datei bei "$element_title$" hochgeladen.';
		$default_messages['module_not_found']='Modul "$module$" in "$path$" nicht gefunden';
		if ($messages!=[]) {
			foreach ($messages as $key=>$value) {
				$default_messages[$key]=$value;
			}
		}

		return $default_messages;
	}

	/**
	 * Setzt den Wert einer Group-Option
	 *
	 * @param string $option
	 * @param  $value
	 * @param string $group
	 * @return bool
	 */
	public function setGroupOption(string $option, $value, string $group='general'):bool {
		$this->ddm['options'][$group][$option]=$value;

		return true;
	}

	/**
	 * @param array $values
	 * @return bool
	 */
	public function setGroupMessages(array $values):bool {
		$this->ddm['options']['messages']=$values;

		return true;
	}

	/**
	 * Gibt den Wert einer Group-Option zurück, wenn nicht vorhanden liefert es '' zurück
	 *
	 * @param string $option
	 * @param string $group
	 */
	public function getGroupOption(string $option, string $group='general') {
		if (isset($this->ddm['options'][$group][$option])) {
			return $this->ddm['options'][$group][$option];
		}

		return '';
	}

	/**
	 * @param string $option
	 * @return string
	 */
	public function getGroupMessage(string $option):string {
		return $this->getGroupOption($option, 'messages');
	}

	/**
	 * @param string $type
	 * @param string $element
	 * @param array $options
	 * @return bool
	 */
	public function addElement(string $type, string $element, array $options):bool {
		if ((!isset($options['enabled']))||($options['enabled']!==true)) {
			$options['id']=$element;
			if (isset($this->ddm[$type]['elements'][$element])) {
				return false;
			}
			if (!isset($this->ddm['counts'])) {
				$this->ddm['counts']=[];
			}
			if ($type=='data') {
				$_data=['list', 'search', 'add', 'edit', 'delete'];
				$default_options=[];
				if ((isset($options['module']))&&($options['module']!='')) {
					$file=Settings::getStringVar('settings_abspath').'frame/ddm4/defaultdata/'.$options['module'].'/php/content.inc.php';
					if (file_exists($file)) {
						include $file;
					}
				}
				$options=array_replace_recursive($default_options, $options);
				$_tmp=[];
				foreach ($_data as $_type) {
					$_tmp[$_type]=[];
					if (isset($options['_'.$_type])) {
						$_tmp[$_type]=$options['_'.$_type];
						unset($options['_'.$_type]);
					}
				}
				foreach ($_data as $_type) {
					$data=array_replace_recursive($options, $_tmp[$_type]);
					if (((!isset($data['enabled']))||($data['enabled']===true))&&($this->getGroupOption('disable_'.$_type)!==true)) {
						$this->ddm['elements'][$_type][$element]=$data;
						$this->ddm['counts'][$_type.'_elements']=count($this->ddm['elements'][$_type]);
						if ((isset($this->ddm['elements'][$_type][$element]['options']['check_required']))&&($this->ddm['elements'][$_type][$element]['options']['check_required']===true)) {
							if (isset($this->ddm['elements'][$_type][$element]['validation'])) {
								if ((isset($this->ddm['elements'][$_type][$element]['validation']['length_min']))&&($this->ddm['elements'][$_type][$element]['validation']['length_min']>0)) {
									$this->ddm['elements'][$_type][$element]['options']['required']=true;
								}
							}
						}
					}
				}
			} else {
				$default_options=[];
				if ((isset($options['module']))&&($options['module']!='')) {
					$file=Settings::getStringVar('settings_abspath').'frame/ddm4/defaultdata/'.$options['module'].'/php/content.inc.php';
					if (file_exists($file)) {
						include $file;
					}
				}
				$options=array_replace_recursive($default_options, $options);
				if ($this->getGroupOption('disable_'.$type)!==true) {
					$this->ddm['elements'][$type][$element]=$options;
					$this->ddm['counts'][$type.'_elements']=count($this->ddm['elements'][$type]);
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * @param string $element
	 * @param array $options
	 * @return bool
	 */
	public function addPreViewElement(string $element, array $options):bool {
		return $this->addElement('preview', $element, $options);
	}

	/**
	 * @param string $element
	 * @param array $options
	 * @return bool
	 */
	public function addViewElement(string $element, array $options):bool {
		return $this->addElement('view', $element, $options);
	}

	/**
	 * @param string $element
	 * @param array $options
	 * @return bool
	 */
	public function addDataElement(string $element, array $options):bool {
		return $this->addElement('data', $element, $options);
	}

	/**
	 * @param string $element
	 * @param array $options
	 * @return bool
	 */
	public function addSendElement(string $element, array $options):bool {
		return $this->addElement('send', $element, $options);
	}

	/**
	 * @param string $element
	 * @param array $options
	 * @return bool
	 */
	public function addFinishElement(string $element, array $options):bool {
		return $this->addElement('finish', $element, $options);
	}

	/**
	 * @param string $element
	 * @param array $options
	 * @return bool
	 */
	public function addAfterFinishElement(string $element, array $options):bool {
		return $this->addElement('afterfinish', $element, $options);
	}

	/**
	 * @param string $element
	 * @param bool $status
	 * @return $this
	 */
	public function setReadOnly(string $element, bool $status=true):self {
		if ((isset($this->ddm))&&(isset($this->ddm['elements']['add'][$element]))) {
			$this->ddm['elements']['add'][$element]['options']['read_only']=$status;
		}
		if ((isset($this->ddm))&&(isset($this->ddm['elements']['edit'][$element]))) {
			$this->ddm['elements']['edit'][$element]['options']['read_only']=$status;
		}
		if ((isset($this->ddm))&&(isset($this->ddm['elements']['delete'][$element]))) {
			$this->ddm['elements']['delete'][$element]['options']['read_only']=$status;
		}

		return $this;
	}

	/**
	 * @param $counter
	 * @param $value
	 * @return $this
	 */
	public function setCounter($counter, $value):self {
		$this->ddm['counts'][$counter]=$value;

		return $this;
	}

	/**
	 * @param string $counter
	 * @return bool|int
	 */
	public function incCounter(string $counter):bool|int {
		if (!isset($this->ddm['counts'][$counter])) {
			$this->setCounter($counter, 0);
		}
		$this->ddm['counts'][$counter]=$this->ddm['counts'][$counter]+1;

		return $this->getCounter($counter);
	}

	/**
	 * @param string $counter
	 * @return bool|int
	 */
	public function decCounter(string $counter):bool|int {
		if (!isset($this->ddm['counts'][$counter])) {
			$this->setCounter($counter, 0);
		}
		$this->ddm['counts'][$counter]=$this->ddm['counts'][$counter]-1;

		return $this->getCounter($counter);
	}

	/**
	 * @param string $counter
	 * @return bool|int
	 */
	public function getCounter(string $counter):bool|int {
		if (isset($this->ddm['counts'][$counter])) {
			return $this->ddm['counts'][$counter];
		}

		return false;
	}

	/**
	 * @param string $element
	 * @return string
	 */
	public function getOrderElementName(string $element):string {
		if (isset($this->ddm['orderelementnames'][$element])) {
			return $this->ddm['orderelementnames'][$element];
		} else {
			return '';
		}
	}

	/**
	 * @param string $element
	 * @param string $value
	 * @return bool
	 */
	public function setOrderElementName(string $element, string $value):bool {
		$this->ddm['orderelementnames'][$element]=$value;

		return true;
	}

	/**
	 * @param string $type
	 * @return array
	 */
	public function getElements(string $type):array {
		if (isset($this->ddm['elements'][$type])) {
			return $this->ddm['elements'][$type];
		} else {
			return [];
		}
	}

	/**
	 * @return array
	 */
	public function getPreViewElements():array {
		return $this->getElements('preview');
	}

	/**
	 * @return array
	 */
	public function getViewElements():array {
		return $this->getElements('view');
	}

	/**
	 * @return array
	 */
	public function getListElements():array {
		return $this->getElements('list');
	}

	/**
	 * @return array
	 */
	public function getSearchElements():array {
		return $this->getElements('search');
	}

	/**
	 * @return array
	 */
	public function getAddElements():array {
		return $this->getElements('add');
	}

	/**
	 * @return array
	 */
	public function getEditElements():array {
		return $this->getElements('edit');
	}

	/**
	 * @return array
	 */
	public function getDeleteElements():array {
		return $this->getElements('delete');
	}

	/**
	 * @return array
	 */
	public function getSendElements():array {
		return $this->getElements('send');
	}

	/**
	 * @return array
	 */
	public function getFinishElements():array {
		return $this->getElements('finish');
	}

	/**
	 * @return array
	 */
	public function getAfterFinishElements():array {
		return $this->getElements('afterfinish');
	}

	/**
	 * @param string $type
	 * @param string $element
	 * @return array
	 */
	public function getElement(string $type, string $element):array {
		if ((isset($this->ddm['elements'][$type]))&&(isset($this->ddm['elements'][$type][$element]))) {
			return $this->ddm['elements'][$type][$element];
		} else {
			return [];
		}
	}

	/**
	 * @param string $element
	 * @return array
	 */
	public function getPreViewElement(string $element):array {
		return $this->getElement('preview', $element);
	}

	/**
	 * @param string $element
	 * @return array
	 */
	public function getViewElement(string $element):array {
		return $this->getElement('view', $element);
	}

	/**
	 * @param string $element
	 * @return array
	 */
	public function getListElement(string $element):array {
		return $this->getElement('list', $element);
	}

	/**
	 * @param string $element
	 * @return array
	 */
	public function getSearchElement(string $element):array {
		return $this->getElement('search', $element);
	}

	/**
	 * @param string $element
	 * @return array
	 */
	public function getAddElement(string $element):array {
		return $this->getElement('add', $element);
	}

	/**
	 * @param string $element
	 * @return array
	 */
	public function getEditElement(string $element):array {
		return $this->getElement('edit', $element);
	}

	/**
	 * @param string $element
	 * @return array
	 */
	public function getDeleteElement(string $element):array {
		return $this->getElement('delete', $element);
	}

	/**
	 * @param string $element
	 * @return array
	 */
	public function getSendElement(string $element):array {
		return $this->getElement('send', $element);
	}

	/**
	 * @param string $element
	 * @return array
	 */
	public function getFinishElement(string $element):array {
		return $this->getElement('finish', $element);
	}

	/**
	 * @param string $element
	 * @return array
	 */
	public function getAfterFinishElement(string $element):array {
		return $this->getElement('afterfinish', $element);
	}

	/**
	 * @param string $type
	 * @param string $key
	 * @param string $group
	 * @return array
	 */
	public function getElementsValue(string $type, string $key, string $group=''):array {
		$ar_tmp=[];
		foreach ($this->getElements($type) as $id=>$options) {
			if ($group!='') {
			} else {
				if (isset($options[$key])) {
					$ar_tmp[$id]=$options[$key];
				}
			}
		}

		return $ar_tmp;
	}

	/**
	 * @param string $key
	 * @param string $group
	 * @return array
	 */
	public function getViewElementsValue(string $key, string $group=''):array {
		return $this->getElementsValue('view', $key, $group);
	}

	/**
	 * @param string $key
	 * @param string $group
	 * @return array
	 */
	public function getListElementsValue(string $key, string $group=''):array {
		return $this->getElementsValue('list', $key, $group);
	}

	/**
	 * @param string $key
	 * @param string $group
	 * @return array
	 */
	public function getSearchElementsValue(string $key, string $group=''):array {
		return $this->getElementsValue('search', $key, $group);
	}

	/**
	 * @param string $key
	 * @param string $group
	 * @return array
	 */
	public function getAddElementsValue(string $key, string $group=''):array {
		return $this->getElementsValue('add', $key, $group);
	}

	/**
	 * @param string $key
	 * @param string $group
	 * @return array
	 */
	public function getEditElementsValue(string $key, string $group=''):array {
		return $this->getElementsValue('edit', $key, $group);
	}

	/**
	 * @param string $key
	 * @param string $group
	 * @return array
	 */
	public function getDeleteElementsValue(string $key, string $group=''):array {
		return $this->getElementsValue('delete', $key, $group);
	}

	/**
	 * @param string $key
	 * @param string $group
	 * @return array
	 */
	public function getSendElementsValue(string $key, string $group=''):array {
		return $this->getElementsValue('send', $key, $group);
	}

	/**
	 * @param string $key
	 * @param string $group
	 * @return array
	 */
	public function getFinishElementsValue(string $key, string $group=''):array {
		return $this->getElementsValue('finish', $key, $group);
	}

	/**
	 * @param string $key
	 * @param string $group
	 * @return array
	 */
	public function getAfterFinishElementsValue(string $key, string $group=''):array {
		return $this->getElementsValue('afterfinish', $key, $group);
	}

	/**
	 * @param string $type
	 * @param string $group
	 * @return array
	 */
	public function getElementsName(string $type, string $group=''):array {
		$ar_tmp=[];
		foreach ($this->getElements($type) as $id=>$options) {
			if ($group=='') {
				if ((isset($options['enabled']))&&($options['enabled']===true)) {
					if (isset($options['name'])) {
						$ar_tmp[]=$options['name'];
					}
					if (isset($options['name_array'])) {
						foreach ($options['name_array'] as $name) {
							if ($options['options']['prefix']!='') {
								$ar_tmp[]=$options['options']['prefix'].$name;
							} else {
								$ar_tmp[]=$name;
							}
						}
					}
				}
			}
		}

		return $ar_tmp;
	}

	/**
	 * @param string $group
	 * @return array
	 */
	public function getViewElementsName(string $group=''):array {
		return $this->getElementsName('view', $group);
	}

	/**
	 * @param string $group
	 * @return array
	 */
	public function getListElementsName(string $group=''):array {
		return $this->getElementsName('list', $group);
	}

	/**
	 * @param string $group
	 * @return array
	 */
	public function getSearchElementsName(string $group=''):array {
		return $this->getElementsName('search', $group);
	}

	/**
	 * @param string $group
	 * @return array
	 */
	public function getAddElementsName(string $group=''):array {
		return $this->getElementsName('add', $group);
	}

	/**
	 * @param string $group
	 * @return array
	 */
	public function getEditElementsName(string $group=''):array {
		return $this->getElementsName('edit', $group);
	}

	/**
	 * @param string $group
	 * @return array
	 */
	public function getDeleteElementsName($group='') {
		return $this->getElementsName('delete', $group);
	}

	/**
	 * @param string $group
	 * @return array
	 */
	public function getSendElementsName(string $group=''):array {
		return $this->getElementsName('send', $group);
	}

	/**
	 * @param string $group
	 * @return array
	 */
	public function getFinishElementsName(string $group=''):array {
		return $this->getElementsName('finish', $group);
	}

	/**
	 * @param string $group
	 * @return array
	 */
	public function getAfterFinishElementsName(string $group=''):array {
		return $this->getElementsName('afterfinish', $group);
	}

	/**
	 * @param string $type
	 * @param string $element
	 * @param string $option
	 * @param string $group
	 * @return mixed
	 */
	public function getElementValue(string $type, string $element, string $option, string $group=''):mixed {
		if ($group=='') {
			if (isset($this->ddm['elements'][$type][$element][$option])) {
				return $this->ddm['elements'][$type][$element][$option];
			}
			if ($type=='view') {
				$type='preview';
				if (isset($this->ddm['elements'][$type][$element][$option])) {
					return $this->ddm['elements'][$type][$element][$option];
				}
			}
		} else {
			if (isset($this->ddm['elements'][$type][$element][$group][$option])) {
				return $this->ddm['elements'][$type][$element][$group][$option];
			}
			if ($type=='view') {
				$type='preview';
				if (isset($this->ddm['elements'][$type][$element][$group][$option])) {
					return $this->ddm['elements'][$type][$element][$group][$option];
				}
			}
		}

		return '';
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getViewElementValue(string $element, string $option):mixed {
		return $this->getElementValue('view', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getViewElementOption(string $element, string $option):mixed {
		return $this->getElementValue('view', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getListElementValue(string $element, string $option):mixed {
		return $this->getElementValue('list', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getListElementOption(string $element, string $option):mixed {
		return $this->getElementValue('list', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getSearchElementValue(string $element, string $option):mixed {
		return $this->getElementValue('search', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getSearchElementOption(string $element, string $option):mixed {
		return $this->getElementValue('search', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getSearchElementValidation(string $element, string $option):mixed {
		return $this->getElementValue('search', $element, $option, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getAddElementValue(string $element, string $option):mixed {
		return $this->getElementValue('add', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getAddElementOption(string $element, string $option):mixed {
		return $this->getElementValue('add', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getAddElementValidation(string $element, string $option):mixed {
		return $this->getElementValue('add', $element, $option, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getEditElementValue(string $element, string $option):mixed {
		return $this->getElementValue('edit', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getEditElementOption(string $element, string $option):mixed {
		return $this->getElementValue('edit', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getEditElementValidation(string $element, string $option):mixed {
		return $this->getElementValue('edit', $element, $option, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getDeleteElementValue(string $element, string $option):mixed {
		return $this->getElementValue('delete', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getDeleteElementOption(string $element, string $option):mixed {
		return $this->getElementValue('delete', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getDeleteElementValidation(string $element, string $option):mixed {
		return $this->getElementValue('delete', $element, $option, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getSendElementValue(string $element, string $option):mixed {
		return $this->getElementValue('send', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getSendElementOption(string $element, string $option):mixed {
		return $this->getElementValue('send', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getSendElementValidation(string $element, string $option):mixed {
		return $this->getElementValue('send', $element, $option, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getFinishElementValue(string $element, string $option):mixed {
		return $this->getElementValue('finish', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getFinishElementOption(string $element, string $option):mixed {
		return $this->getElementValue('finish', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getFinishElementValidation(string $element, string $option):mixed {
		return $this->getElementValue('finish', $element, $option, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getAfterFinishElementValue(string $element, string $option):mixed {
		return $this->getElementValue('afterfinish', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getAfterFinishElementOption(string $element, string $option):mixed {
		return $this->getElementValue('afterfinish', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getAfterFinishElementValidation(string $element, string $option):mixed {
		return $this->getElementValue('afterfinish', $element, $option, 'validation');
	}

	/**
	 * @param string $type
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @param string $group
	 * @return bool
	 */
	public function setElementValue(string $type, string $element, string $option, mixed $value, string $group=''):bool {
		if ($group=='') {
			$this->ddm['elements'][$type][$element][$option]=$value;

			return true;
		} else {
			$this->ddm['elements'][$type][$element][$group][$option]=$value;

			return true;
		}

		return false;
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setViewElementValue(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('view', $element, $option, $value, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setViewElementOption(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('view', $element, $option, $value, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setListElementValue(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('list', $element, $option, $value, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setListElementOption(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('list', $element, $option, $value, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setSearchElementValue(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('search', $element, $option, $value, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setSearchElementOption(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('search', $element, $option, $value, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setSearchElementValidation(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('search', $element, $option, $value, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setAddElementValue(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('add', $element, $option, $value, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setAddElementOption(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('add', $element, $option, $value, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setAddElementValidation(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('add', $element, $option, $value, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setEditElementValue(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('edit', $element, $option, $value, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setEditElementOption(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('edit', $element, $option, $value, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setEditElementValidation(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('edit', $element, $option, $value, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setDeleteElementValue(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('delete', $element, $option, $value, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setDeleteElementOption(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('delete', $element, $option, $value, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setDeleteElementValidation(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('delete', $element, $option, $value, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setSendElementValue(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('send', $element, $option, $value, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setSendElementOption(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('send', $element, $option, $value, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	public function setSendElementValidation(string $element, string $option, mixed $value):bool {
		return $this->setElementValue('send', $element, $option, $value, 'validation');
	}

	/**
	 * @return bool
	 */
	public function removeElements():bool {
		if (isset($this->ddm['elements'])) {
			unset($this->ddm['elements']);

			return true;
		}

		return false;
	}

	/**
	 * @param string $type
	 * @param string $element
	 * @return bool
	 */
	public function removeElement(string $type, string $element):bool {
		if (isset($this->ddm['elements'][$type][$element])) {
			unset($this->ddm['elements'][$type][$element]);

			return true;
		}

		return false;
	}

	/**
	 * @param string $type
	 * @param string $element
	 * @param string $option
	 * @param string $group
	 * @return bool
	 */
	public function removeElementValue(string $type, string $element, string $option, string $group=''):bool {
		if ($group=='') {
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
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeViewElementValue(string $element, string $option):bool {
		return $this->removeElementValue('view', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeViewElementOption(string $element, string $option):bool {
		return $this->removeElementValue('view', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeListElementValue(string $element, string $option):bool {
		return $this->removeElementValue('list', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeListElementOption(string $element, string $option):bool {
		return $this->removeElementValue('list', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeSearchElementValue(string $element, string $option):bool {
		return $this->removeElementValue('search', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeSearchElementOption(string $element, string $option):bool {
		return $this->removeElementValue('search', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeSearchElementValidation(string $element, string $option):bool {
		return $this->removeElementValue('search', $element, $option, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeAddElementValue(string $element, string $option):bool {
		return $this->removeElementValue('add', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeAddElementOption(string $element, string $option):bool {
		return $this->removeElementValue('add', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeAddElementValidation(string $element, string $option):bool {
		return $this->removeElementValue('add', $element, $option, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeEditElementValue(string $element, string $option):bool {
		return $this->removeElementValue('edit', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeEditElementOption(string $element, string $option):bool {
		return $this->removeElementValue('edit', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeEditElementValidation(string $element, string $option):bool {
		return $this->removeElementValue('edit', $element, $option, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeDeleteElementValue(string $element, string $option):bool {
		return $this->removeElementValue('delete', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeDeleteElementOption(string $element, string $option):bool {
		return $this->removeElementValue('delete', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeDeleteElementValidation(string $element, string $option):bool {
		return $this->removeElementValue('delete', $element, $option, 'validation');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeSendElementValue(string $element, string $option):bool {
		return $this->removeElementValue('send', $element, $option, '');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeSendElementOption(string $element, string $option):bool {
		return $this->removeElementValue('send', $element, $option, 'options');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function removeSendElementValidation(string $element, string $option):bool {
		return $this->removeElementValue('send', $element, $option, 'validation');
	}

	/**
	 * @param string $type
	 * @param array $elements
	 * @return bool
	 */
	public function setStorageValues(string $type, array $elements):bool {
		$this->ddm['storage'][$type]=$elements;

		return true;
	}

	/**
	 * @param array $elements
	 * @return bool
	 */
	public function setListStorageValues(array $elements):bool {
		return $this->setStorageValues('list', $elements);
	}

	/**
	 * @param string $type
	 * @param string $element
	 * @return mixed
	 */
	public function getStorageValue(string $type, string $element):mixed {
		if (isset($this->ddm['storage'][$type][$element])) {
			return $this->ddm['storage'][$type][$element];
		}

		return '';
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getListStorageValue(string $element):mixed {
		return $this->getStorageValue('list', $element);
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @param string $option
	 * @return bool
	 */
	public function setElementStorage(string $element, mixed $value, string $option='default'):bool {
		if (!isset($this->ddm['storage']['data'])) {
			$this->ddm['storage']['data']=[];
		}
		if (!isset($this->ddm['storage']['data'][$option])) {
			$this->ddm['storage']['data'][$option]=[];
		}
		$this->ddm['storage']['data'][$option][$element]=$value;

		return true;
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setSearchElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'search');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setDataBaseElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'database');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setAddElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'add');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setDoAddElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'doadd');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setEditElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'edit');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setDoEditElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'doedit');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setDeleteElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'delete');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setDoDeleteElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'dodelete');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setSendElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'send');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setDoSendElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'dosend');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setFilterElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'filter');
	}

	/**
	 * @param string $element
	 * @param mixed $value
	 * @return bool
	 */
	public function setFilterErrorElementStorage(string $element, mixed $value):bool {
		return $this->setElementStorage($element, $value, 'filtererror');
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	public function setIndexElementStorage(mixed $value):bool {
		return $this->setElementStorage('index', $value, 'index');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return mixed
	 */
	public function getElementStorage(string $element, string $option='default'):mixed {
		if (isset($this->ddm['storage']['data'][$option][$element])) {
			return $this->ddm['storage']['data'][$option][$element];
		}

		return '';
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getSearchElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'search');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getAddElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'add');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getDoAddElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'doadd');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getEditElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'edit');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getDoEditElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'doedit');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getDeleteElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'delete');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getDoDeleteElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'dodelete');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getSendElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'send');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getDoSendElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'dosend');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getDataBaseElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'database');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getFilterElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'filter');
	}

	/**
	 * @param string $element
	 * @return mixed
	 */
	public function getFilterErrorElementStorage(string $element):mixed {
		return $this->getElementStorage($element, 'filtererror');
	}

	/**
	 * @return mixed
	 */
	public function getIndexElementStorage():mixed {
		return $this->getElementStorage('index', 'index');
	}

	/**
	 * @return array
	 */
	public function getSearchElementsStorage():array {
		return $this->getElementsStorage('search');
	}

	/**
	 * @return array
	 */
	public function getDataBaseElementsStorage():array {
		return $this->getElementsStorage('database');
	}

	/**
	 * @param string $option
	 * @return array
	 */
	public function getElementsStorage(string $option='default'):array {
		if (isset($this->ddm['storage']['data'][$option])) {
			return $this->ddm['storage']['data'][$option];
		}

		return [];
	}

	/**
	 * @param string $element
	 * @return bool
	 */
	public function clearSearchElementStorage(string $element):bool {
		return $this->clearElementStorage($element, 'search');
	}

	/**
	 * @param string $element
	 * @param string $option
	 * @return bool
	 */
	public function clearElementStorage(string $element, string $option='default'):bool {
		if (isset($this->ddm['storage']['data'][$option][$element])) {
			unset($this->ddm['storage']['data'][$option][$element]);
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function getDirectModule():string {
		if (''!=$this->getGroupOption('module', 'direct')) {
			return $this->getGroupOption('module', 'direct');
		}

		return 'current';
	}

	/**
	 * @return string
	 */
	public function getDirectParameters():string {
		$_paramters=[];
		if ((''!=$this->getGroupOption('parameters', 'direct'))&&(is_array($this->getGroupOption('parameters', 'direct')))&&(count($this->getGroupOption('parameters', 'direct'))>0)) {
			foreach ($this->getGroupOption('parameters', 'direct') as $element=>$value) {
				$_paramters[]=$element.'='.$value;
			}
		}

		return implode('&', $_paramters);
	}

	/**
	 * @param string $module
	 * @param string $parameter
	 */
	public function direct(string $module='current', string $parameter=''):void {
		$this->storeParameters();
		Network::directHeader($this->getTemplate()->buildhrefLink($module, $parameter));
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function addAjaxFunction(string $name, string $value) {
		if (!isset($this->ddm['ajaxfunction'])) {
			$this->ddm['ajaxfunction']=[];
		}
		$this->ddm['ajaxfunction'][$name]=$value;
	}

	/**
	 * @param string $name
	 * @return array|mixed
	 */
	public function getAjaxFunction(string $name) {
		if (isset($this->ddm['ajaxfunction'][$name])) {
			return $this->ddm['ajaxfunction'][$name];
		}

		return [];
	}

	/**
	 * @return array
	 */
	public function getAjaxFunctions():array {
		if (isset($this->ddm['ajaxfunction'])) {
			return $this->ddm['ajaxfunction'];
		}

		return [];
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function removeAjaxFunction(string $name):bool {
		if (!isset($this->ddm['ajaxfunction'])) {
			$this->ddm['ajaxfunction']=[];
		}
		if (isset($this->ddm['ajaxfunction'][$name])) {
			unset($this->ddm['ajaxfunction'][$name]);

			return true;
		}

		return false;
	}

	/**
	 * @param string $name
	 * @param string|int $value
	 * @return $this
	 */
	public function setParameter(string $name, string|int $value):self {
		return $this->addParameter($name, $value);
	}

	/**
	 * @param string $name
	 * @param string|int|array $value
	 * @return $this
	 */
	public function addParameter(string $name, string|int|array $value):self {
		if (!isset($this->ddm['parameters'])) {
			$this->ddm['parameters']=[];
		}
		$this->ddm['parameters'][$name]=$value;

		return $this;
	}

	/**
	 * @param string $name
	 * @return string|int|array
	 */
	public function getParameter(string $name):string|int|array {
		if (isset($this->ddm['parameters'][$name])) {
			return $this->ddm['parameters'][$name];
		}
		if ($name=='ddm_search_data') {
			return [];
		}

		return '';
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function removeParameter(string $name):bool {
		if (!isset($this->ddm['parameters'])) {
			$this->ddm['parameters']=[];
		}
		if (isset($this->ddm['parameters'][$name])) {
			unset($this->ddm['parameters'][$name]);

			return true;
		}

		return false;
	}

	/**
	 *
	 * @return bool
	 */
	public function storeParameters():bool {
		Session::setArrayVar('ddm4_'.$this->name.'_parameters', $this->ddm['parameters']);

		return true;
	}

	/**
	 *
	 * @return bool
	 */
	public function readParameters():bool {
		$this->ddm['parameters']=Session::getArrayVar('ddm4_'.$this->name.'_parameters');
		if ($this->ddm['parameters']===null) {
			$this->ddm['parameters']=[];
		}

		return true;
	}

	/**
	 * @param string $type
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseElementPHP(string $type, string $element, array $values):bool {
		return $this->parseElement($element, $values, $type, 'content', 'php');
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseViewElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('view', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseListElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('list', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFormSearchElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('formsearch', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseParserSearchElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('parsersearch', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFormAddElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('formadd', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseParserAddElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('parseradd', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFilterAddElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('filteradd', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFinishAddElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('finishadd', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFormEditElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('formedit', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseParserEditElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('parseredit', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFilterEditElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('filteredit', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFinishEditElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('finishedit', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFormDeleteElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('formdelete', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseParserDeleteElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('parserdelete', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFinishDeleteElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('finishdelete', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFinishSearchElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('finishsearch', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFormSendElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('formsend', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseParserSendElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('parsersend', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFilterSendElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('filtersend', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return bool
	 */
	public function parseFinishSendElementPHP(string $element, array $values):bool {
		return $this->parseElementPHP('finishsend', $element, $values);
	}

	/**
	 *
	 * @param string $key
	 * @param string $value
	 * @param int $user_id
	 * @return bool
	 */
	public function setLock(string $key, string $value, int $user_id):bool {
		$this->clearLock();
		$Qgetlock=self::getConnection();
		$Qgetlock->prepare('SELECT * FROM :table_ddm4_lock: WHERE lock_group=:lock_group: AND lock_key=:lock_key: AND lock_value=:lock_value:');
		$Qgetlock->bindTable(':table_ddm4_lock:', 'ddm4_lock');
		$Qgetlock->bindString(':lock_group:', $this->getName());
		$Qgetlock->bindString(':lock_key:', $key);
		$Qgetlock->bindString(':lock_value:', $value);
		if ($Qgetlock->exec()==1) {
			$Qgetlock=self::getConnection();
			$Qgetlock->prepare('SELECT * FROM :table_ddm4_lock: WHERE lock_group=:lock_group: AND lock_key=:lock_key: AND lock_value=:lock_value: AND user_id=:user_id:');
			$Qgetlock->bindTable(':table_ddm4_lock:', 'ddm4_lock');
			$Qgetlock->bindString(':lock_group:', $this->getName());
			$Qgetlock->bindString(':lock_key:', $key);
			$Qgetlock->bindString(':lock_value:', $value);
			$Qgetlock->bindInt(':user_id:', $user_id);
			if ($Qgetlock->exec()==1) {
				$Qlock=self::getConnection();
				$Qlock->prepare('UPDATE :table_ddm4_lock: SET lock_time=:lock_time: WHERE lock_group=:lock_group: AND lock_key=:lock_key: AND lock_value=:lock_value: AND user_id=:user_id:');
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
			$Qlock=self::getConnection();
			$Qlock->prepare('INSERT INTO :table_ddm4_lock: (lock_group, lock_key, lock_value, user_id, lock_time) VALUES (:lock_group:, :lock_key:, :lock_value:, :user_id:, :lock_time:)');
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
	 *
	 * @param string $key
	 * @param string $value
	 * @return int
	 */
	public function getLockUserId(string $key, string $value):int {
		$Qgetlock=self::getConnection();
		$Qgetlock->prepare('SELECT user_id FROM :table_ddm4_lock: WHERE lock_group=:lock_group: AND lock_key=:lock_key: AND lock_value=:lock_value:');
		$Qgetlock->bindTable(':table_ddm4_lock:', 'ddm4_lock');
		$Qgetlock->bindString(':lock_group:', $this->getName());
		$Qgetlock->bindString(':lock_key:', $key);
		$Qgetlock->bindString(':lock_value:', $value);
		if ($Qgetlock->exec()==1) {
			$Qgetlock->fetch();

			return $Qgetlock->getInt('user_id');
		}

		return 0;
	}

	/**
	 *
	 * @return bool
	 */
	public function clearLock():bool {
		$Qclearlock=self::getConnection();
		$Qclearlock->prepare('DELETE FROM :table_ddm4_lock: WHERE lock_time<:lock_time:');
		$Qclearlock->bindTable(':table_ddm4_lock:', 'ddm4_lock');
		$Qclearlock->bindInt(':lock_time:', (time()-10));
		$Qclearlock->execute();

		return true;
	}

	/**
	 * @param string $type
	 * @param string $position
	 * @param string $element
	 * @param array $values
	 * @return string|bool
	 */
	public function parseElementTPL(string $type, string $position, string $element, array $values):string|bool {
		return $this->parseElement($element, $values, $type, $position, 'tpl');
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return string|bool
	 */
	public function parseViewElementTPL(string $element, array $values):string|bool {
		return $this->parseElementTPL('view', 'content', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return string|bool
	 */
	public function parseListElementTPL(string $element, array $values):string|bool {
		return $this->parseElementTPL('list', 'content', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return string|bool
	 */
	public function parseListHeaderElementTPL(string $element, array $values):string|bool {
		return $this->parseElementTPL('list', 'header', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return string|bool
	 */
	public function parseFormSearchElementTPL(string $element, array $values):string|bool {
		return $this->parseElementTPL('formsearch', 'content', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return string|bool
	 */
	public function parseFormAddElementTPL(string $element, array $values):string|bool {
		return $this->parseElementTPL('formadd', 'content', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return string|bool
	 */
	public function parseFormEditElementTPL(string $element, array $values):string|bool {
		return $this->parseElementTPL('formedit', 'content', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return string|bool
	 */
	public function parseFormDeleteElementTPL(string $element, array $values):string|bool {
		return $this->parseElementTPL('formdelete', 'content', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @return string|bool
	 */
	public function parseFormSendElementTPL(string $element, array $values):string|bool {
		return $this->parseElementTPL('formsend', 'content', $element, $values);
	}

	/**
	 * @param string $element
	 * @param array $values
	 * @param string $type
	 * @param string $file
	 * @param string $script
	 * @return string|bool
	 */
	public function parseElement(string $element, array $values, string $type='', string $file='content', string $script='php'):string|bool {
		if (!isset($values['module'])) {
			return false;
		}
		if ($script==='tpl') {
			ob_start();
			$file=Settings::getStringVar('settings_abspath').'frame/ddm4/'.$type.'/'.$values['module'].'/tpl/content.tpl.php';
			if (file_exists($file)) {
				include $file;
			}
			$contents=ob_get_contents();
			ob_end_clean();

			return $contents;
		} else {
			$file=Settings::getStringVar('settings_abspath').'frame/ddm4/'.$type.'/'.$values['module'].'/php/content.inc.php';
			if (file_exists($file)) {
				include $file;
			}

			return true;
		}
	}

	/**
	 * @return bool
	 */
	public function runDDMPHP():bool {
		$engine=$this->getGroupOption('engine');
		$file=Settings::getStringVar('settings_abspath').'frame/ddm4/loader/run/'.$this->getName().'.inc.php';
		if (file_exists($file)) {
			include $file;
		}
		$file=Settings::getStringVar('settings_abspath').'frame/ddm4/engine/'.$engine.'/php/content.inc.php';
		if (file_exists($file)) {
			include $file;
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function runDDMTPL():string {
		$engine=$this->getGroupOption('engine');
		$file=Settings::getStringVar('settings_abspath').'frame/ddm4/engine/'.$engine.'/tpl/content.tpl.php';
		ob_start();
		if (file_exists($file)) {
			include $file;
		}
		$contents=ob_get_contents();
		ob_end_clean();

		return $contents;
	}

}

?>