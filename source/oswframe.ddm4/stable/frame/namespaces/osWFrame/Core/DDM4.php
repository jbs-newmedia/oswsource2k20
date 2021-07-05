<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
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
	private const CLASS_RELEASE_VERSION=1;

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

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function addElement($type, $element, $options) {
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
				$file=Settings::getStringVar('settings_abspath').'frame/ddm4/defaultdata/'.$options['module'].'/php/content.inc.php';
				if (file_exists($file)) {
					include $file;
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
				$file=Settings::getStringVar('settings_abspath').'frame/ddm4/defaultdata/'.$options['module'].'/php/content.inc.php';
				if (file_exists($file)) {
					include $file;
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

	public function addPreViewElement($element, $options) {
		return $this->addElement('preview', $element, $options);
	}

	public function addViewElement($element, $options) {
		return $this->addElement('view', $element, $options);
	}

	public function addDataElement($element, $options) {
		return $this->addElement('data', $element, $options);
	}

	public function addSendElement($element, $options) {
		return $this->addElement('send', $element, $options);
	}

	public function addFinishElement($element, $options) {
		return $this->addElement('finish', $element, $options);
	}

	public function addAfterFinishElement($element, $options) {
		return $this->addElement('afterfinish', $element, $options);
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function setReadOnly($element, $status=true) {
		if ((isset($this->ddm))&&(isset($this->ddm['elements']['add'][$element]))) {
			$this->ddm['elements']['add'][$element]['options']['read_only']=$status;
		}
		if ((isset($this->ddm))&&(isset($this->ddm['elements']['edit'][$element]))) {
			$this->ddm['elements']['edit'][$element]['options']['read_only']=$status;
		}
		if ((isset($this->ddm))&&(isset($this->ddm['elements']['delete'][$element]))) {
			$this->ddm['elements']['delete'][$element]['options']['read_only']=$status;
		}
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function setCounter($counter, $value) {
		$this->ddm['counts'][$counter]=$value;
	}

	public function incCounter($counter) {
		if (!isset($this->ddm['counts'][$counter])) {
			$this->setCounter($counter, 0);
		}
		$this->ddm['counts'][$counter]=$this->ddm['counts'][$counter]+1;

		return $this->getCounter($counter);
	}

	public function decCounter($counter) {
		if (!isset($this->ddm['counts'][$counter])) {
			$this->setCounter($counter, 0);
		}
		$this->ddm['counts'][$counter]=$this->ddm['counts'][$counter]-1;

		return $this->getCounter($counter);
	}

	public function getCounter($counter) {
		if (isset($this->ddm['counts'][$counter])) {
			return $this->ddm['counts'][$counter];
		}

		return false;
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function getOrderElementName($element) {
		if (isset($this->ddm['orderelementnames'][$element])) {
			return $this->ddm['orderelementnames'][$element];
		} else {
			return '';
		}
	}

	public function setOrderElementName($element, $value) {
		$this->ddm['orderelementnames'][$element]=$value;

		return true;
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function getElements($type) {
		if (isset($this->ddm['elements'][$type])) {
			return $this->ddm['elements'][$type];
		} else {
			return [];
		}
	}

	public function getPreViewElements() {
		return $this->getElements('preview');
	}

	public function getViewElements() {
		return $this->getElements('view');
	}

	public function getListElements() {
		return $this->getElements('list');
	}

	public function getSearchElements() {
		return $this->getElements('search');
	}

	public function getAddElements() {
		return $this->getElements('add');
	}

	public function getEditElements() {
		return $this->getElements('edit');
	}

	public function getDeleteElements() {
		return $this->getElements('delete');
	}

	public function getSendElements() {
		return $this->getElements('send');
	}

	public function getFinishElements() {
		return $this->getElements('finish');
	}

	public function getAfterFinishElements() {
		return $this->getElements('afterfinish');
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function getElement($type, $element) {
		if ((isset($this->ddm['elements'][$type]))&&(isset($this->ddm['elements'][$type][$element]))) {
			return $this->ddm['elements'][$type][$element];
		} else {
			return [];
		}
	}

	public function getPreViewElement($element) {
		return $this->getElement('preview', $element);
	}

	public function getViewElement($element) {
		return $this->getElement('view', $element);
	}

	public function getListElement($element) {
		return $this->getElement('list', $element);
	}

	public function getSearchElement($element) {
		return $this->getElement('search', $element);
	}

	public function getAddElement($element) {
		return $this->getElement('add', $element);
	}

	public function getEditElement($element) {
		return $this->getElement('edit', $element);
	}

	public function getDeleteElement($element) {
		return $this->getElement('delete', $element);
	}

	public function getSendElement($element) {
		return $this->getElement('send', $element);
	}

	public function getFinishElement($element) {
		return $this->getElement('finish', $element);
	}

	public function getAfterFinishElement($element) {
		return $this->getElement('afterfinish', $element);
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function getElementsValue($type, $key, $group='') {
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

	public function getViewElementsValue($key, $group='') {
		return $this->getElementsValue('view', $key, $group);
	}

	public function getListElementsValue($key, $group='') {
		return $this->getElementsValue('list', $key, $group);
	}

	public function getSearchElementsValue($key, $group='') {
		return $this->getElementsValue('search', $key, $group);
	}

	public function getAddElementsValue($key, $group='') {
		return $this->getElementsValue('add', $key, $group);
	}

	public function getEditElementsValue($key, $group='') {
		return $this->getElementsValue('edit', $key, $group);
	}

	public function getDeleteElementsValue($key, $group='') {
		return $this->getElementsValue('delete', $key, $group);
	}

	public function getSendElementsValue($key, $group='') {
		return $this->getElementsValue('send', $key, $group);
	}

	public function getFinishElementsValue($key, $group='') {
		return $this->getElementsValue('finish', $key, $group);
	}

	public function getAfterFinishElementsValue($key, $group='') {
		return $this->getElementsValue('afterfinish', $key, $group);
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function getElementsName($type, $group='') {
		$ar_tmp=[];
		$key='name';
		foreach ($this->getElements($type) as $id=>$options) {
			if ($group!='') {
			} else {
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

		return $ar_tmp;
	}

	public function getViewElementsName($group='') {
		return $this->getElementsName('view', $group);
	}

	public function getListElementsName($group='') {
		return $this->getElementsName('list', $group);
	}

	public function getSearchElementsName($group='') {
		return $this->getElementsName('search', $group);
	}

	public function getAddElementsName($group='') {
		return $this->getElementsName('add', $group);
	}

	public function getEditElementsName($group='') {
		return $this->getElementsName('edit', $group);
	}

	public function getDeleteElementsName($group='') {
		return $this->getElementsName('delete', $group);
	}

	public function getSendElementsName($group='') {
		return $this->getElementsName('send', $group);
	}

	public function getFinishElementsName($group='') {
		return $this->getElementsName('finish', $group);
	}

	public function getAfterFinishElementsName($group='') {
		return $this->getElementsName('afterfinish', $group);
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function getElementValue(string $type, string $element, string $option, string $group='') {
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

	public function getViewElementValue($element, $option) {
		return $this->getElementValue('view', $element, $option, '');
	}

	public function getViewElementOption($element, $option) {
		return $this->getElementValue('view', $element, $option, 'options');
	}

	public function getListElementValue($element, $option) {
		return $this->getElementValue('list', $element, $option, '');
	}

	public function getListElementOption($element, $option) {
		return $this->getElementValue('list', $element, $option, 'options');
	}

	public function getSearchElementValue($element, $option) {
		return $this->getElementValue('search', $element, $option, '');
	}

	public function getSearchElementOption($element, $option) {
		return $this->getElementValue('search', $element, $option, 'options');
	}

	public function getSearchElementValidation($element, $option) {
		return $this->getElementValue('search', $element, $option, 'validation');
	}

	public function getAddElementValue($element, $option) {
		return $this->getElementValue('add', $element, $option, '');
	}

	public function getAddElementOption($element, $option) {
		return $this->getElementValue('add', $element, $option, 'options');
	}

	public function getAddElementValidation($element, $option) {
		return $this->getElementValue('add', $element, $option, 'validation');
	}

	public function getEditElementValue($element, $option) {
		return $this->getElementValue('edit', $element, $option, '');
	}

	public function getEditElementOption(string $element, string $option) {
		return $this->getElementValue('edit', $element, $option, 'options');
	}

	public function getEditElementValidation($element, $option) {
		return $this->getElementValue('edit', $element, $option, 'validation');
	}

	public function getDeleteElementValue($element, $option) {
		return $this->getElementValue('delete', $element, $option, '');
	}

	public function getDeleteElementOption($element, $option) {
		return $this->getElementValue('delete', $element, $option, 'options');
	}

	public function getDeleteElementValidation($element, $option) {
		return $this->getElementValue('delete', $element, $option, 'validation');
	}

	public function getSendElementValue($element, $option) {
		return $this->getElementValue('send', $element, $option, '');
	}

	public function getSendElementOption($element, $option) {
		return $this->getElementValue('send', $element, $option, 'options');
	}

	public function getSendElementValidation($element, $option) {
		return $this->getElementValue('send', $element, $option, 'validation');
	}

	public function getFinishElementValue($element, $option) {
		return $this->getElementValue('finish', $element, $option, '');
	}

	public function getFinishElementOption($element, $option) {
		return $this->getElementValue('finish', $element, $option, 'options');
	}

	public function getFinishElementValidation($element, $option) {
		return $this->getElementValue('finish', $element, $option, 'validation');
	}

	public function getAfterFinishElementValue($element, $option) {
		return $this->getElementValue('afterfinish', $element, $option, '');
	}

	public function getAfterFinishElementOption($element, $option) {
		return $this->getElementValue('afterfinish', $element, $option, 'options');
	}

	public function getAfterFinishElementValidation($element, $option) {
		return $this->getElementValue('afterfinish', $element, $option, 'validation');
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function setElementValue($type, $element, $option, $value, $group='') {
		if ($group=='') {
			$this->ddm['elements'][$type][$element][$option]=$value;

			return true;
		} else {
			$this->ddm['elements'][$type][$element][$group][$option]=$value;

			return true;
		}

		return false;
	}

	public function setViewElementValue($element, $option, $value) {
		return $this->setElementValue('view', $element, $option, $value, '');
	}

	public function setViewElementOption($element, $option, $value) {
		return $this->setElementValue('view', $element, $option, $value, 'options');
	}

	public function setListElementValue($element, $option, $value) {
		return $this->setElementValue('list', $element, $option, $value, '');
	}

	public function setListElementOption($element, $option, $value) {
		return $this->setElementValue('list', $element, $option, $value, 'options');
	}

	public function setSearchElementValue($element, $option, $value) {
		return $this->setElementValue('search', $element, $option, $value, '');
	}

	public function setSearchElementOption($element, $option, $value) {
		return $this->setElementValue('search', $element, $option, $value, 'options');
	}

	public function setSearchElementValidation($element, $option, $value) {
		return $this->setElementValue('search', $element, $option, $value, 'validation');
	}

	public function setAddElementValue($element, $option, $value) {
		return $this->setElementValue('add', $element, $option, $value, '');
	}

	public function setAddElementOption($element, $option, $value) {
		return $this->setElementValue('add', $element, $option, $value, 'options');
	}

	public function setAddElementValidation($element, $option, $value) {
		return $this->setElementValue('add', $element, $option, $value, 'validation');
	}

	public function setEditElementValue($element, $option, $value) {
		return $this->setElementValue('edit', $element, $option, $value, '');
	}

	public function setEditElementOption($element, $option, $value) {
		return $this->setElementValue('edit', $element, $option, $value, 'options');
	}

	public function setEditElementValidation($element, $option, $value) {
		return $this->setElementValue('edit', $element, $option, $value, 'validation');
	}

	public function setDeleteElementValue($element, $option, $value) {
		return $this->setElementValue('delete', $element, $option, $value, '');
	}

	public function setDeleteElementOption($element, $option, $value) {
		return $this->setElementValue('delete', $element, $option, $value, 'options');
	}

	public function setDeleteElementValidation($element, $option, $value) {
		return $this->setElementValue('delete', $element, $option, $value, 'validation');
	}

	public function setSendElementValue($element, $option, $value) {
		return $this->setElementValue('send', $element, $option, $value, '');
	}

	public function setSendElementOption($element, $option, $value) {
		return $this->setElementValue('send', $element, $option, $value, 'options');
	}

	public function setSendElementValidation($element, $option, $value) {
		return $this->setElementValue('send', $element, $option, $value, 'validation');
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function removeElements() {
		if (isset($this->ddm['elements'])) {
			unset($this->ddm['elements']);

			return true;
		}

		return false;
	}

	public function removeElement($type, $element) {
		if (isset($this->ddm['elements'][$type][$element])) {
			unset($this->ddm['elements'][$type][$element]);

			return true;
		}

		return false;
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function removeElementValue($type, $element, $option, $group='') {
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

	public function removeViewElementValue($element, $option) {
		return $this->removeElementValue('view', $element, $option, '');
	}

	public function removeViewElementOption($element, $option) {
		return $this->removeElementValue('view', $element, $option, 'options');
	}

	public function removeListElementValue($element, $option) {
		return $this->removeElementValue('list', $element, $option, '');
	}

	public function removeListElementOption($element, $option) {
		return $this->removeElementValue('list', $element, $option, 'options');
	}

	public function removeSearchElementValue($element, $option) {
		return $this->removeElementValue('search', $element, $option, '');
	}

	public function removeSearchElementOption($element, $option) {
		return $this->removeElementValue('search', $element, $option, 'options');
	}

	public function removeSearchElementValidation($element, $option) {
		return $this->removeElementValue('search', $element, $option, 'validation');
	}

	public function removeAddElementValue($element, $option) {
		return $this->removeElementValue('add', $element, $option, '');
	}

	public function removeAddElementOption($element, $option) {
		return $this->removeElementValue('add', $element, $option, 'options');
	}

	public function removeAddElementValidation($element, $option) {
		return $this->removeElementValue('add', $element, $option, 'validation');
	}

	public function removeEditElementValue($element, $option) {
		return $this->removeElementValue('edit', $element, $option, '');
	}

	public function removeEditElementOption($element, $option) {
		return $this->removeElementValue('edit', $element, $option, 'options');
	}

	public function removeEditElementValidation($element, $option) {
		return $this->removeElementValue('edit', $element, $option, 'validation');
	}

	public function removeDeleteElementValue($element, $option) {
		return $this->removeElementValue('delete', $element, $option, '');
	}

	public function removeDeleteElementOption($element, $option) {
		return $this->removeElementValue('delete', $element, $option, 'options');
	}

	public function removeDeleteElementValidation($element, $option) {
		return $this->removeElementValue('delete', $element, $option, 'validation');
	}

	public function removeSendElementValue($element, $option) {
		return $this->removeElementValue('send', $element, $option, '');
	}

	public function removeSendElementOption($element, $option) {
		return $this->removeElementValue('send', $element, $option, 'options');
	}

	public function removeSendElementValidation($element, $option) {
		return $this->removeElementValue('send', $element, $option, 'validation');
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function setStorageValues($type, $elements) {
		$this->ddm['storage'][$type]=$elements;

		return true;
	}

	public function setListStorageValues($elements) {
		return $this->setStorageValues('list', $elements);
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function getStorageValue($type, $element) {
		if (isset($this->ddm['storage'][$type][$element])) {
			return $this->ddm['storage'][$type][$element];
		}

		return '';
	}

	public function getListStorageValue($element) {
		return $this->getStorageValue('list', $element);
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function setElementStorage($element, $value, $option='default') {
		if (!isset($this->ddm['storage']['data'])) {
			$this->ddm['storage']['data']=[];
		}
		if (!isset($this->ddm['storage']['data'][$option])) {
			$this->ddm['storage']['data'][$option]=[];
		}
		$this->ddm['storage']['data'][$option][$element]=$value;
	}

	public function setSearchElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'search');
	}

	public function setDataBaseElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'database');
	}

	public function setAddElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'add');
	}

	public function setDoAddElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'doadd');
	}

	public function setEditElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'edit');
	}

	public function setDoEditElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'doedit');
	}

	public function setDeleteElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'delete');
	}

	public function setDoDeleteElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'dodelete');
	}

	public function setSendElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'send');
	}

	public function setDoSendElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'dosend');
	}

	public function setFilterElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'filter');
	}

	public function setFilterErrorElementStorage($element, $value) {
		return $this->setElementStorage($element, $value, 'filtererror');
	}

	public function setIndexElementStorage($value) {
		return $this->setElementStorage('index', $value, 'index');
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function getElementStorage($element, $option='default') {
		if (isset($this->ddm['storage']['data'][$option][$element])) {
			return $this->ddm['storage']['data'][$option][$element];
		}

		return '';
	}

	public function getSearchElementStorage($element) {
		return $this->getElementStorage($element, 'search');
	}

	public function getAddElementStorage($element) {
		return $this->getElementStorage($element, 'add');
	}

	public function getDoAddElementStorage($element) {
		return $this->getElementStorage($element, 'doadd');
	}

	public function getEditElementStorage($element) {
		return $this->getElementStorage($element, 'edit');
	}

	public function getDoEditElementStorage($element) {
		return $this->getElementStorage($element, 'doedit');
	}

	public function getDeleteElementStorage($element) {
		return $this->getElementStorage($element, 'delete');
	}

	public function getDoDeleteElementStorage($element) {
		return $this->getElementStorage($element, 'dodelete');
	}

	public function getSendElementStorage($element) {
		return $this->getElementStorage($element, 'send');
	}

	public function getDoSendElementStorage($element) {
		return $this->getElementStorage($element, 'dosend');
	}

	public function getDataBaseElementStorage($element) {
		return $this->getElementStorage($element, 'database');
	}

	public function getFilterElementStorage($element) {
		return $this->getElementStorage($element, 'filter');
	}

	public function getFilterErrorElementStorage($element) {
		return $this->getElementStorage($element, 'filtererror');
	}

	public function getIndexElementStorage() {
		return $this->getElementStorage('index', 'index');
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function getSearchElementsStorage() {
		return $this->getElementsStorage('search');
	}

	public function getDataBaseElementsStorage() {
		return $this->getElementsStorage('database');
	}

	public function getElementsStorage($option='default') {
		if (isset($this->ddm['storage']['data'][$option])) {
			return $this->ddm['storage']['data'][$option];
		}

		return [];
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function clearSearchElementStorage($element) {
		return $this->clearElementStorage($element, 'search');
	}

	public function clearElementStorage($element, $option='default') {
		if (isset($this->ddm['storage']['data'][$option][$element])) {
			unset($this->ddm['storage']['data'][$option][$element]);
		}

		return true;
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function getDirectModule() {
		if (''!=$this->getGroupOption('module', 'direct')) {
			return $this->getGroupOption('module', 'direct');
		}

		return 'current';
	}

	public function getDirectParameters() {
		$_paramters=[];
		if ((''!=$this->getGroupOption('parameters', 'direct'))&&(is_array($this->getGroupOption('parameters', 'direct')))&&(count($this->getGroupOption('parameters', 'direct'))>0)) {
			foreach ($this->getGroupOption('parameters', 'direct') as $element=>$value) {
				$_paramters[]=$element.'='.$value;
			}
		}

		return implode('&', $_paramters);
	}

	public function direct($module='current', $parameter='') {
		$this->storeParameters();
		Network::directHeader($this->getTemplate()->buildhrefLink($module, $parameter));
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////

	public function addAjaxFunction($name, $value) {
		if (!isset($this->ddm['ajaxfunction'])) {
			$this->ddm['ajaxfunction']=[];
		}
		$this->ddm['ajaxfunction'][$name]=$value;
	}

	public function getAjaxFunction($name) {
		if (isset($this->ddm['ajaxfunction'][$name])) {
			return $this->ddm['ajaxfunction'][$name];
		}

		return [];
	}

	public function getAjaxFunctions() {
		if (isset($this->ddm['ajaxfunction'])) {
			return $this->ddm['ajaxfunction'];
		}

		return [];
	}

	public function removeAjaxFunction($name) {
		if (!isset($this->ddm['ajaxfunction'])) {
			$this->ddm['ajaxfunction']=[];
		}
		if (isset($this->ddm['ajaxfunction'][$name])) {
			unset($this->ddm['ajaxfunction'][$name]);

			return true;
		}

		return false;
	}

	public function setParameter($name, $value) {
		return $this->addParameter($name, $value);
	}

	public function addParameter($name, $value) {
		if (!isset($this->ddm['parameters'])) {
			$this->ddm['parameters']=[];
		}
		$this->ddm['parameters'][$name]=$value;
	}

	public function getParameter($name) {
		if (isset($this->ddm['parameters'][$name])) {
			return $this->ddm['parameters'][$name];
		}
		if ($name=='ddm_search_data') {
			return [];
		}

		return '';
	}

	public function removeParameter($name) {
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

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function parseElementPHP($type, $element, $values) {
		return $this->parseElement($element, $values, $type, 'content', 'php');
	}

	public function parseViewElementPHP($element, $values) {
		return $this->parseElementPHP('view', $element, $values);
	}

	public function parseListElementPHP($element, $values) {
		return $this->parseElementPHP('list', $element, $values);
	}

	public function parseFormSearchElementPHP($element, $values) {
		return $this->parseElementPHP('formsearch', $element, $values);
	}

	public function parseParserSearchElementPHP($element, $values) {
		return $this->parseElementPHP('parsersearch', $element, $values);
	}

	public function parseFormAddElementPHP($element, $values) {
		return $this->parseElementPHP('formadd', $element, $values);
	}

	public function parseParserAddElementPHP($element, $values) {
		return $this->parseElementPHP('parseradd', $element, $values);
	}

	public function parseFilterAddElementPHP($element, $values) {
		return $this->parseElementPHP('filteradd', $element, $values);
	}

	public function parseFinishAddElementPHP($element, $values) {
		return $this->parseElementPHP('finishadd', $element, $values);
	}

	public function parseFormEditElementPHP($element, $values) {
		return $this->parseElementPHP('formedit', $element, $values);
	}

	public function parseParserEditElementPHP($element, $values) {
		return $this->parseElementPHP('parseredit', $element, $values);
	}

	public function parseFilterEditElementPHP($element, $values) {
		return $this->parseElementPHP('filteredit', $element, $values);
	}

	public function parseFinishEditElementPHP($element, $values) {
		return $this->parseElementPHP('finishedit', $element, $values);
	}

	public function parseFormDeleteElementPHP($element, $values) {
		return $this->parseElementPHP('formdelete', $element, $values);
	}

	public function parseParserDeleteElementPHP($element, $values) {
		return $this->parseElementPHP('parserdelete', $element, $values);
	}

	public function parseFinishDeleteElementPHP($element, $values) {
		return $this->parseElementPHP('finishdelete', $element, $values);
	}

	public function parseFinishSearchElementPHP($element, $values) {
		return $this->parseElementPHP('finishsearch', $element, $values);
	}

	public function parseFormSendElementPHP($element, $values) {
		return $this->parseElementPHP('formsend', $element, $values);
	}

	public function parseParserSendElementPHP($element, $values) {
		return $this->parseElementPHP('parsersend', $element, $values);
	}

	public function parseFilterSendElementPHP($element, $values) {
		return $this->parseElementPHP('filtersend', $element, $values);
	}

	public function parseFinishSendElementPHP($element, $values) {
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

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function parseElementTPL($type, $position, $element, $values) {
		return $this->parseElement($element, $values, $type, $position, 'tpl');
	}

	public function parseViewElementTPL($element, $values) {
		return $this->parseElementTPL('view', 'content', $element, $values);
	}

	public function parseListElementTPL($element, $values) {
		return $this->parseElementTPL('list', 'content', $element, $values);
	}

	public function parseListHeaderElementTPL($element, $values) {
		return $this->parseElementTPL('list', 'header', $element, $values);
	}

	public function parseFormSearchElementTPL($element, $values) {
		return $this->parseElementTPL('formsearch', 'content', $element, $values);
	}

	public function parseFormAddElementTPL($element, $values) {
		return $this->parseElementTPL('formadd', 'content', $element, $values);
	}

	public function parseFormEditElementTPL($element, $values) {
		return $this->parseElementTPL('formedit', 'content', $element, $values);
	}

	public function parseFormDeleteElementTPL($element, $values) {
		return $this->parseElementTPL('formdelete', 'content', $element, $values);
	}

	public function parseFormSendElementTPL($element, $values) {
		return $this->parseElementTPL('formsend', 'content', $element, $values);
	}

	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	// ////////////////////////////////////////////
	public function parseElement($element, $values, $type='', $file='content', $script='php') {
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
	 *
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
	 *
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