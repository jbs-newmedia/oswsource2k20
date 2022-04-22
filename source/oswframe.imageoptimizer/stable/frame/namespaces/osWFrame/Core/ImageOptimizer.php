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

class ImageOptimizer {

	use BaseStaticTrait;

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
	 * @var array
	 */
	protected array $options=[];

	/**
	 * @var array
	 */
	protected array $valid_options=[];

	/**
	 * ImageOptimizer constructor.
	 */
	public function __construct() {
		$this->setValidOptions();
	}

	/**
	 * @return bool
	 */
	protected function setValidOptions():bool {
		$this->valid_options=['longest', 'width', 'height', 'quality', 'scale', 'cropr', 'croprr', 'crops', 'cropsr', 'ps', 'transparent', 'border', 'ts'];

		return true;
	}

	/**
	 * @return array
	 */
	public function getValideOptions():array {
		return $this->valid_options;
	}

	/**
	 * @param string $str
	 * @return bool
	 */
	public function getOptionsArrayByString(string $str):array {
		$options=[];
		$_options=explode('-', $str);
		foreach ($_options as $_option) {
			$_option=explode('_', $_option);
			if (count($_option)==2) {
				$options[$_option[0]]=$_option[1];
			}
		}

		return $options;
	}

	/**
	 * @param array $options
	 * @return bool
	 */
	public function setOptionsByArray(array $options):bool {
		if ($options!=[]) {
			foreach ($options as $key=>$value) {
				$this->setOptionByValue($key, $value);
			}
		}

		return true;
	}

	/**
	 * @param string $keym
	 * @param $value
	 * @return bool
	 */
	public function setOptionByValue(string $key, $value):bool {
		if ((in_array($key, $this->getValideOptions()))&&($value!='')) {
			$this->options[$key]=$value;

			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getOptions():array {
		return $this->options;
	}

	/**
	 * @param bool $ps
	 * @return string
	 */
	public function getOptionsAsString(bool $ps=true):string {
		if (($ps!==true)&&isset($this->options['ps'])) {
			unset($this->options['ps']);
		}

		return $this->createOptionsAsString($this->options);
	}

	/**
	 * @param array $options
	 * @return string
	 */
	protected function createOptionsAsString(array $options) {
		$str=[];
		foreach ($options as $key=>$value) {
			$str[]=$key.'_'.$value;
		}

		return implode('-', $str);
	}

	/**
	 * @param string $filename
	 * @param string $protection_salt
	 * @return bool
	 */
	public function setPS(string $filename, string $protection_salt=''):bool {
		if ($protection_salt=='') {
			$protection_salt=Settings::getStringVar('settings_protection_salt');
		}
		$this->setOptionByValue('ps', $this->createPS($filename, $this->getOptions(), $protection_salt));

		return true;
	}

	/**
	 * @param string $filename
	 * @param array $options
	 * @param string $ps
	 * @param string $protection_salt
	 * @return bool
	 */
	protected function validatePS(string $filename, array $options, string $ps, string $protection_salt=''):bool {
		if ($protection_salt=='') {
			$protection_salt=Settings::getStringVar('settings_protection_salt');
		}
		if (isset($options['ps'])) {
			unset($options['ps']);
		}
		if (($this->createPS($filename, $options, $protection_salt))===$ps) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $filename
	 * @param array $options
	 * @param string $protection_salt
	 * @return string
	 */
	protected function createPS(string $filename, array $options, string $protection_salt):string {
		return substr(md5($filename.'#'.$this->createOptionsAsString($options).'#'.$protection_salt), 3, 6);
	}

	/**
	 * @param string $file
	 * @param array $options
	 * @return string
	 */
	protected function getImageContent(string $file, array $options):string {
		$osW_ImageLib=new ImageLib($file);
		if (isset($options['quality'])) {
			$osW_ImageLib->setQuality($options['quality']);
		}
		if (isset($options['longest'])) {
			$osW_ImageLib->resizeToLongest($options['longest']);
		} elseif (isset($options['scale'])) {
			$osW_ImageLib->scale($options['scale']);
		} elseif (isset($options['cropr'])) {
			$osW_ImageLib->cropRectangle();
		} elseif (isset($options['croprr'])) {
			$osW_ImageLib->cropRectangleResized($options['croprr']);
		} elseif (isset($options['crops'])) {
			$size=explode('x', $options['crops']);
			if (count($size)==2) {
				$ratio=floatval(intval($size[0])/intval($size[1]));
				$osW_ImageLib->cropSquare($ratio);
			} else {
				$osW_ImageLib->cropSquare(1);
			}
		} elseif (isset($options['cropsr'])) {
			$size=explode('x', $options['cropsr']);
			if (count($size)==2) {
				$osW_ImageLib->cropSquareResized(intval($size[0]), intval($size[1]));
			} else {
				$osW_ImageLib->cropSquareResized(640, 480);
			}
		} elseif ((isset($options['width']))&&(isset($options['height']))) {
			$osW_ImageLib->resize($options['width'], $options['height']);
		} elseif (isset($options['width'])) {
			$osW_ImageLib->resizeToWidth($options['width']);
		} elseif (isset($options['height'])) {
			$osW_ImageLib->resizeToHeight($options['height']);
		}

		return $osW_ImageLib->outputStream();
	}

	/**
	 * @param string $image
	 * @return bool
	 */
	public function getOutput(string $image):bool {
		$parts=pathinfo($image);
		$fileparts=explode('.', $parts['basename']);
		$count=count($fileparts);
		if (($count!=2)&&($count!=3)) {
			MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>'Unsupported file structure ('.$image.')']);
			Settings::dieScript('Unsupported file structure');
		}
		$options=[];
		if ($count==2) {
			$options=$this->getOptionsArrayByString('');
			$filename=$parts['dirname'].'/'.$fileparts[0].'.'.$fileparts[1];
			$fileparts[2]='';
		}
		if ($count==3) {
			$options=$this->getOptionsArrayByString($fileparts[1]);
			$filename=$parts['dirname'].'/'.$fileparts[0].'.'.$fileparts[2];
		}
		$rel_file=$filename;
		$abs_file=\osWFrame\Core\Settings::getStringVar('settings_abspath').$rel_file;
		if (!file_exists($abs_file)) {
			MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>'File not found ('.$rel_file.')']);
			Settings::dieScript('File not found');
		}
		$allowed_dirs=[];
		if ((Settings::getArrayVar('imageoptimizer_allowed_dirs')!=null)&&(Settings::getArrayVar('imageoptimizer_allowed_dirs')!=[])) {
			$allowed_dirs=array_merge($allowed_dirs, Settings::getArrayVar('imageoptimizer_allowed_dirs'));
		}
		if ((Settings::getArrayVar('imageoptimizer_allowed_dirs_custom')!=null)&&(Settings::getArrayVar('imageoptimizer_allowed_dirs_custom')!=[])) {
			$allowed_dirs=array_merge($allowed_dirs, Settings::getArrayVar('imageoptimizer_allowed_dirs_custom'));
		}
		$allowed_dirs[]=substr(Settings::getStringVar('cache_path'), 0, -1);
		$allowed_dirs[]=substr(Settings::getStringVar('resource_path'), 0, -1);
		$allowed_check=false;

		foreach ($allowed_dirs as $a_dir) {
			if (strpos(realpath($abs_file), realpath(Settings::getStringVar('settings_abspath').$a_dir))===0) {
				$allowed_check=true;
				break;
			}
		}

		if ($allowed_check!==true) {
			MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>'File out of allowed dir ('.implode(',', $disallowed_files).')']);
			Settings::dieScript('File out of allowed dir');
		}

		if (Settings::getBoolVar('imageoptimizer_protect_files')===true) {
			if (!isset($options['ps'])) {
				MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>'Checksum not matched ('.$rel_file.')']);
				Settings::dieScript('Checksum not matched');
			}
			if ($this->validatePS($rel_file, $options, $options['ps'])!==true) {
				MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>'Checksum not matched ('.$rel_file.')']);
				Settings::dieScript('Checksum not matched');
			}
		}

		$image_info=getimagesize($abs_file);
		$image_type=$image_info[2];
		switch ($image_type) {
			case IMAGETYPE_JPEG :
				Network::sendHeader('Content-Type: image/jpg');
				$image_type='jpg';
				break;
			case IMAGETYPE_GIF :
				Network::sendHeader('Content-Type: image/gif');
				$image_type='gif';
				break;
			case IMAGETYPE_PNG :
				Network::sendHeader('Content-Type: image/png');
				$image_type='png';
				break;
			default :
				MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>'Unsupported file type ('.$image_type.')']);
				Settings::dieScript('Unsupported file type');
				break;
		}

		$filenamecache=md5($image).'.'.$image_type;
		if ((Cache::existsCache($this->getClassName(), $filenamecache, '')!==true)||((Filesystem::getFileModTime($abs_file)>Cache::getCacheModTime($this->getClassName(), $filenamecache, '')))&&(Settings::getBoolVar('imageoptimizer_servercachecheck')===true)) {
			$generateContent=true;
		} else {
			$generateContent=false;
		}
		if (Settings::getBoolVar('imageoptimizer_clientcache')===true) {
			if ($generateContent!==true) {
				$mtime=Cache::getCacheModTime(self::getClassName(), $filenamecache, '');
			} else {
				$mtime=Filesystem::getFilesModTime([$abs_file]);
			}
			$mtimestr=DateTime::convertTimeStamp2GM($mtime);
		}

		if ((isset($options['ts']))||(Settings::getBoolVar('imageoptimizer_clientcache')!==true)||(Settings::catchValue('HTTP_IF_MODIFIED_SINCE', '', 'r')!=$mtimestr)) {
			if (isset($options['ts'])) {
				$ct=60*60*24*365;
				Network::sendHeader('Pragma: public');
				Network::sendHeader('Cache-Control: max-age='.$ct);
				Network::sendHeader('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()+$ct));
			} elseif (Settings::getBoolVar('imageoptimizer_clientcache')===true) {
				Network::sendHeader("Last-Modified: ".$mtimestr);
				Network::sendHeader("Cache-Control: must-revalidate");
			} else {
				Network::sendNoCacheHeader();
			}

			if ($generateContent===true) {
				$content=$this->getImageContent($abs_file, $options);
				if (Cache::writeCache(self::getClassName(), $filenamecache, $content, '')!==true) {
					MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>'Could not create cache file('.$rel_file.')']);
				}
				Network::sendHeader('Content-Length: '.strlen($content));
				echo $content;
			} else {
				$content=Cache::readCacheAsString(self::getClassName(), $filenamecache, 0, '');
				Network::sendHeader('Content-Length: '.strlen($content));
				echo $content;
			}
		} else {
			Network::sendHeader("Last-Modified: ".$mtimestr);
			Network::sendHeader("Cache-Control: must-revalidate");
			Network::sendHeader('HTTP/1.0 304 Not Modified');
		}

		return true;
	}

}

?>