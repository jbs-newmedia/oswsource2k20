<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

namespace osWFrame\Core;

class CKEditor5 {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=1;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var string
	 */
	protected string $selector_type='#';

	/**
	 * @var string
	 */
	protected string $selector='';

	/**
	 * @var array
	 */
	protected array $conf=[];

	/**
	 * @var array
	 */
	protected array $then=[];

	/**
	 * @var array
	 */
	protected array $catch=[];

	/**
	 * @var array
	 */
	protected array $simpleUpload=[];

	/**
	 * @var array
	 */
	protected array $file=[];

	/**
	 * CKEditor5 constructor.
	 *
	 * @param string $selector_type
	 * @param string $selector
	 * @param array $conf
	 * @param array $then
	 * @param array $catch
	 */
	public function __construct(string $selector_type, string $selector, array $conf=[], array $then=[], array $catch=[]) {
		if ($conf==[]) {
			$conf=self::getDefaultConf();
		}

		$this->setSelector($selector);
		$this->setConf($conf);
		$this->setThen($then);
		$this->setCatch($catch);
	}

	/**
	 * @return array
	 */
	public static function getDefaultConf():array {
		$conf=[];
		$conf['toolbar']=[];
		$conf['toolbar']['items']=['undo', 'redo', '|', 'findAndReplace', 'selectAll', '|', 'heading', '|', 'removeFormat', 'bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', '|', 'fontColor', 'fontBackgroundColor', '|', 'specialCharacters', 'link', 'insertTable', 'insertImage', 'mediaEmbed', 'htmlEmbed', '|', 'bulletedList', 'numberedList', 'alignment', '|', 'sourceEditing'];
		$conf['toolbar']['shouldNotGroupWhenFull']=true;
		$conf['image']=[];
		$conf['image']['styles']=['alignCenter', 'alignLeft', 'alignRight'];
		$conf['image']['resizeOptions']=[['name'=>'resizeImage:original', 'label'=>'Original', 'value'=>null], ['name'=>'resizeImage:25', 'label'=>'25%', 'value'=>'25'], ['name'=>'resizeImage:50', 'label'=>'50%', 'value'=>'50'], ['name'=>'resizeImage:75', 'label'=>'75%', 'value'=>'75'], ['name'=>'resizeImage:100', 'label'=>'100%', 'value'=>'100']];
		$conf['image']['toolbar']=['linkImage', 'imageTextAlternative', '|', 'imageStyle:inline', 'imageStyle:wrapText', 'imageStyle:breakText', 'imageStyle:side', '|', 'resizeImage'];
		$conf['image']['insert']=['integrations'=>['insertImageViaUrl']];
		$conf['list']=[];
		$conf['list']['properties']=['styles'=>true, 'startIndex'=>true, 'reversed'=>true];
		$conf['table']=[];
		$conf['table']['contentToolbar']=['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties'];

		return $conf;
	}

	/**
	 * @param array $conf
	 */
	public function setConf(array $conf):void {
		$this->conf=$conf;
	}

	/**
	 * @return array
	 */
	public function getConf():array {
		return $this->conf;
	}

	/**
	 * @param array $then
	 */
	public function setThen(array $then):void {
		$this->then=$then;
	}

	/**
	 * @return array
	 */
	public function getThen():array {
		return $this->then;
	}

	/**
	 * @param array $catch
	 */
	public function setCatch(array $catch):void {
		$this->catch=$catch;
	}

	/**
	 * @return array
	 */
	public function getCatch():array {
		return $this->catch;
	}

	/**
	 * @param string $selector_type
	 */
	public function setSelectorType(string $selector_type):void {
		$this->selector_type=$selector_type;
	}

	/**
	 * @return string
	 */
	public function getSelectorType():string {
		return $this->selector_type;
	}

	/**
	 * @param string $selector
	 */
	public function setSelector(string $selector):void {
		$this->selector=$selector;
	}

	/**
	 * @return string
	 */
	public function getSelector():string {
		return $this->selector;
	}

	/**
	 * @param string $url
	 * @return void
	 */
	public function setSimpleUploadUrl(string $url):void {
		$this->simpleUpload['uploadUrl']=$url;
	}

	/**
	 * @param bool $withCredentials
	 * @return void
	 */
	public function setSimpleUploadWithCredentials(bool $withCredentials):void {
		$this->simpleUpload['withCredentials']=$withCredentials;
	}

	/**
	 * @param array $headers
	 * @return void
	 */
	public function setSimpleUploadHeaders(array $headers):void {
		$this->simpleUpload['headers']=$headers;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function setSimpleUploadHeader(string $key, string $value):void {
		$this->simpleUpload['headers'][$key]=$value;
	}

	/**
	 * @return array
	 */
	public function getSimpleUpload():array {
		return $this->simpleUpload;
	}

	/**
	 * @return string
	 */
	public function getJS():string {
		$conf=$this->getConf();
		if ($this->getSimpleUpload()!==[]) {
			Session::setArrayVar('ck5editor_'.md5($this->getSelector().Settings::getStringVar('settings_protection_salt')), $this->getFile());
			$conf['simpleUpload']=$this->getSimpleUpload();
		}

		$output=[];
		$output[]='ClassicEditor';
		$output[]='	.create(document.querySelector(\''.$this->getSelectorType().$this->getSelector().'\'), {';
		$output[]=substr(json_encode($conf), 1, -1);
		$output[]='	})';
		$output[]='	.then(editor => {';
		if ($this->getThen()!=[]) {
			$output[]=implode("\n", $this->getThen());
		}
		$output[]='	})';
		$output[]='	.catch(error => {';
		if ($this->getCatch()!=[]) {
			$output[]=implode("\n", $this->getCatch());
		}
		$output[]='	});';

		return implode("\n", $output);
	}

	/**
	 * @param array $file
	 */
	public function setFile(array $file):void {
		$this->file=$file;
	}

	/**
	 * @return array
	 */
	public function getFile():array {
		return $this->file;
	}

	/**
	 * @param string $dir
	 * @return void
	 */
	public function setFileDir(string $dir):void {
		$this->file['file_dir']=$dir;
	}

	/**
	 * @return string
	 */
	public function getFileDir():string {
		if (isset($this->file['file_dir'])) {
			return $this->file['file_dir'];
		}

		return '';
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setFileName(string $name):void {
		$this->file['file_name']=$name;
	}

	/**
	 * @return string
	 */
	public function getFileName():string {
		if (isset($this->file['file_name'])) {
			return $this->file['file_name'];
		}

		return '';
	}

	/**
	 * @param array $types
	 * @return void
	 */
	public function setFileTypes(array $types):void {
		$this->file['file_types']=$types;
	}

	/**
	 * @return array
	 */
	public function getFileTypes():array {
		if (isset($this->file['file_types'])) {
			return $this->file['file_types'];
		}

		return [];
	}

	/**
	 * @param array $extensions
	 * @return void
	 */
	public function setFileExtensions(array $extensions):void {
		$this->file['file_extensions']=$extensions;
	}

	/**
	 * @return array
	 */
	public function getFileExtensions():array {
		if (isset($this->file['file_extensions'])) {
			return $this->file['file_extensions'];
		}

		return [];
	}

	/**
	 * @param int $size
	 * @return void
	 */
	public function setFileSizeMin(int $size):void {
		$this->file['file_size_min']=$size;
	}

	/**
	 * @return int
	 */
	public function getFileSizeMin():int {
		if (isset($this->file['file_size_min'])) {
			return $this->file['file_size_min'];
		}

		return 0;
	}

	/**
	 * @param int $size
	 * @return void
	 */
	public function setFileSizeMax(int $size):void {
		$this->file['file_size_max']=$size;
	}

	/**
	 * @return int
	 */
	public function getFileSizeMax():int {
		if (isset($this->file['file_size_max'])) {
			return $this->file['file_size_max'];
		}

		return 0;
	}

	/**
	 * @param int $width
	 * @return void
	 */
	public function setFileWidthMin(int $width):void {
		$this->file['file_width_min']=$width;
	}

	/**
	 * @return int
	 */
	public function getFileWidthMin():int {
		if (isset($this->file['file_width_min'])) {
			return $this->file['file_width_min'];
		}

		return 0;
	}

	/**
	 * @param int $width
	 * @return void
	 */
	public function setFileWidthMax(int $width):void {
		$this->file['file_width_max']=$width;
	}

	/**
	 * @return int
	 */
	public function getFileWidthMax():int {
		if (isset($this->file['file_width_max'])) {
			return $this->file['file_width_max'];
		}

		return 0;
	}

	/**
	 * @param int $height
	 * @return void
	 */
	public function setFileHeightMin(int $height):void {
		$this->file['file_height_min']=$height;
	}

	/**
	 * @return int
	 */
	public function getFileHeightMin():int {
		if (isset($this->file['file_height_min'])) {
			return $this->file['file_height_min'];
		}

		return 0;
	}

	/**
	 * @param int $height
	 * @return void
	 */
	public function setFileHeightMax(int $height):void {
		$this->file['file_height_max']=$height;
	}

	/**
	 * @return int
	 */
	public function getFileHeightMax():int {
		if (isset($this->file['file_height_max'])) {
			return $this->file['file_height_max'];
		}

		return 0;
	}

	/**
	 * @param string $key
	 * @param int $value
	 * @return void
	 */
	public function setFileIntValue(string $key, int $value):void {
		$this->file[$key]=$value;
	}

	/**
	 * @param string $key
	 * @return int
	 */
	public function getFileIntValue(string $key):int {
		if (isset($this->file[$key])) {
			return $this->file[$key];
		}

		return 0;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function setFileStringValue(string $key, string $value):void {
		$this->file[$key]=$value;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getFileStringValue(string $key):string {
		if (isset($this->file[$key])) {
			return $this->file[$key];
		}

		return '';
	}

}

?>