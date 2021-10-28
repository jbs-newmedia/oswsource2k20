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

namespace osWFrame\Core;

class SmartOptimizer {

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
	 * SmartOptimizer constructor.
	 */
	private function __construct() {

	}

	/**
	 * Schreibt den Cache.
	 *
	 * @param string $file
	 * @param string $data
	 * @return bool
	 */
	public static function writeCacheFile(string $file, string $data):bool {
		if (Cache::existsCache(self::getClassName(), $file, '')!==true) {
			Cache::writeCache(self::getClassName(), $file, $data, '');

			return true;
		}

		return false;
	}

	/**
	 * * Gibt den letzen Aktualisierungszeitpunkt alle Dateien der Liste zurück.
	 *
	 * @param array $files
	 * @return int
	 */
	private static function getFilesModTime(array $files):int {
		foreach ($files as $key=>$value) {
			$files[$key]=Settings::getStringVar('settings_abspath').$value;
		}

		return Filesystem::getFilesModTime($files);
	}

	/**
	 * Erzeugt die Ausgabe mehrerer Dateien kombiniert.
	 *
	 * @param string $query_string
	 * @param string $filetype
	 * @return bool
	 */
	public static function getOutput(string $query_string, string $filetype):bool {
		// Ueberpruefen ob die Cachedatei des Querystrings existiert
		if (Cache::existsCache(self::getClassName(), $query_string, '')!==true) {
			$msg='File not found ('.$query_string.')';
			MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>$msg]);
			Settings::dieScript($msg);
		}
		// Dateiliste aus Cachedatei erzeugen
		$files=explode(',', Cache::readCacheAsString(self::getClassName(), $query_string, 0, ''));
		// Fehlende Dateien ermitteln
		$missed_files=false;
		$missing_files=[];
		foreach ($files as $file) {
			$cfile=Settings::getStringVar('settings_abspath').$file;
			if (!file_exists($cfile)) {
				$missed_files=true;
				$missing_files[]=$file;
			}
		}
		if ($missed_files===true) {
			if (count($missing_files)>1) {
				$msg='Files not found ('.implode(',', $missing_files).')';
			} else {
				$msg='File not found ('.implode(',', $missing_files).')';
			}
			MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>$msg]);
			Settings::dieScript($msg);
		}
		$allowed_dirs=[];
		if ((Settings::getArrayVar('smartoptimizer_allowed_dirs')!=null)&&(Settings::getArrayVar('smartoptimizer_allowed_dirs')!=[])) {
			$allowed_dirs=array_merge($allowed_dirs, Settings::getArrayVar('smartoptimizer_allowed_dirs'));
		}
		if ((Settings::getArrayVar('smartoptimizer_allowed_dirs_custom')!=null)&&(Settings::getArrayVar('smartoptimizer_allowed_dirs_custom')!=[])) {
			$allowed_dirs=array_merge($allowed_dirs, Settings::getArrayVar('smartoptimizer_allowed_dirs_custom'));
		}
		$allowed_dirs[]=substr(Settings::getStringVar('cache_path'), 0, -1);
		$allowed_dirs[]=substr(Settings::getStringVar('resource_path'), 0, -1);
		$disallowed_files=[];
		foreach ($files as $file) {
			$allowed_check=false;
			foreach ($allowed_dirs as $a_dir) {
				if (strpos(realpath(Settings::getStringVar('settings_abspath').$file), realpath(Settings::getStringVar('settings_abspath').$a_dir))===0) {
					$allowed_check=true;
				}
			}
			if ($allowed_check!==true) {
				$disallowed_files[]=$file;
			}
		}
		if ($allowed_check!==true) {
			if (count($disallowed_files)>1) {
				$msg='Files out of allowed dir ('.implode(',', $disallowed_files).')';
			} else {
				$msg='File out of allowed dir ('.implode(',', $disallowed_files).')';
			}
			MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>$msg]);
			Settings::dieScript($msg);
		}
		switch ($filetype) {
			case 'css':
				Network::sendHeader('Content-Type: text/css; charset=utf-8');
				break;
			case 'js':
				Network::sendHeader('Content-Type: text/javascript; charset=utf-8');
				break;
			default:
				$msg='Unsupported file type ('.$filetype.')';
				MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>$msg]);
				Settings::dieScript($msg);
				break;
		}
		if ((Settings::getBoolVar('smartoptimizer_gzipcompression')===true)&&(!headers_sent())&&(!connection_aborted())) {
			Network::sendHeader("Content-Encoding: gzip");
		} else {
			Settings::setBoolVar('smartoptimizer_gzipcompression', false);
		}
		$filename=Settings::getStringVar('smartoptimizer_cacheprefix').md5($query_string.(Settings::getBoolVar('smartoptimizer_embed')?'embed1':'embed0').(Settings::getBoolVar('smartoptimizer_stripoutput')?'stripoutput1':'stripoutput0')).'.'.$filetype.(Settings::getBoolVar('smartoptimizer_gzipcompression')?'.gz':'');
		if ((Cache::existsCache(self::getClassName(), $filename, '')!==true)||((self::getFilesModTime($files)>Cache::getCacheModTime(self::getClassName(), $filename, '')))&&(Settings::getBoolVar('smartoptimizer_servercachecheck')===true)) {
			$generateContent=true;
		} else {
			$generateContent=false;
		}
		if (Settings::getBoolVar('smartoptimizer_clientcache')===true) {
			if ($generateContent!==true) {
				$mtime=Cache::getCacheModTime(self::getClassName(), $filename, '');
			} else {
				$mtime=self::getFilesModTime($files);
			}
			$mtimestr=DateTime::convertTimeStamp2GM($mtime);
		}
		if ((Settings::getBoolVar('smartoptimizer_clientcache')!==true)||(Settings::catchValue('HTTP_IF_MODIFIED_SINCE', '', 'r')!=$mtimestr)) {
			if (Settings::getBoolVar('smartoptimizer_clientcache')===true) {
				Network::sendHeader("Last-Modified: ".$mtimestr);
				Network::sendHeader("Cache-Control: must-revalidate");
			} else {
				Network::sendNoCacheHeader();
			}
			$session_parameter='';
			if ((defined('SID')===true)&&(strlen(SID)>0)) {
				$session_parameter.='?'.SID;
			}
			$generateContent=true;
			if ($generateContent===true) {
				$content=[];
				foreach ($files as $file) {
					$__DIR__='../../';
					$cfile=Settings::getStringVar('settings_abspath').$file;
					if (file_exists($cfile)) {
						switch ($filetype) {
							case 'css':
								if (substr($cfile, -8)=='.min.css') {
									$content[]=file_get_contents($cfile);
								} else {
									if (Settings::getBoolVar('smartoptimizer_stripoutput')==true) {
										$content[]=self::stripCSS(file_get_contents($cfile));
									} else {
										$content[]=file_get_contents($cfile);
									}
								}
								break;
							case 'js':
								if (substr($cfile, -7)=='.min.js') {
									$content[]=file_get_contents($cfile);
								} else {
									if (Settings::getBoolVar('smartoptimizer_stripoutput')==true) {
										$content[]=self::stripJS(file_get_contents($cfile));
									} else {
										$content[]=file_get_contents($cfile);
									}
								}
								break;
						}
					} else {
						MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>'File not found ('.$file.')']);
					}
				}
				$content=implode("\n\n", $content);
				if (Settings::getBoolVar('smartoptimizer_gzipcompression')===true) {
					$content=gzencode($content, Settings::getIntVar('smartoptimizer_gzipcompression_level'));
				}
				if (Cache::writeCache(self::getClassName(), $filename, $content, '')!==true) {
					MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>'Could not create cache file('.$filename.')']);
				}
				if (strlen($session_parameter)>0) {
					str_ireplace('__PARAMETER__', $session_parameter, $content);
				}
				Network::sendHeader('Content-Length: '.strlen($content));
				echo $content;
			} else {
				$content=Cache::readCacheAsString(self::getClassName(), $filename, 0, '');
				if (strlen($session_parameter)>0) {
					str_ireplace('__PARAMETER__', $session_parameter, $content);
				}
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

	/**
	 * * Erzeugt die Ausgabe einer Datei.
	 *
	 * @param string $file
	 * @param string $filetype
	 * @return bool
	 */
	public static function getOutputSingle(string $file, string $filetype):bool {
		$cfile=Settings::getStringVar('settings_abspath').$file;
		if (!file_exists($cfile)) {
			$msg='File not found ('.$cfile.')';
			MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>$msg]);
			Settings::dieScript($msg);
		}
		$allowed_dirs=[];
		if ((Settings::getArrayVar('smartoptimizer_allowed_dirs')!=null)&&(Settings::getArrayVar('smartoptimizer_allowed_dirs')!=[])) {
			$allowed_dirs=array_merge($allowed_dirs, Settings::getArrayVar('smartoptimizer_allowed_dirs'));
		}
		if ((Settings::getArrayVar('smartoptimizer_allowed_dirs_custom')!=null)&&(Settings::getArrayVar('smartoptimizer_allowed_dirs_custom')!=[])) {
			$allowed_dirs=array_merge($allowed_dirs, Settings::getArrayVar('smartoptimizer_allowed_dirs_custom'));
		}
		$allowed_dirs[]=substr(Settings::getStringVar('cache_path'), 0, -1);
		$allowed_dirs[]=substr(Settings::getStringVar('resource_path'), 0, -1);
		$allowed_check=false;
		foreach ($allowed_dirs as $a_dir) {
			if (strpos(realpath(Settings::getStringVar('settings_abspath').$file), realpath(Settings::getStringVar('settings_abspath').$a_dir))===0) {
				$allowed_check=true;
			}
		}
		if ($allowed_check!==true) {
			$msg='File out of allowed dir ('.$file.')';
			MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>$msg]);
			Settings::dieScript($msg);
		}
		switch ($filetype) {
			case 'css':
				Network::sendHeader('Content-Type: text/css; charset=utf-8');
				break;
			case 'js':
				Network::sendHeader('Content-Type: text/javascript; charset=utf-8');
				break;
			default:
				$msg='Unsupported file type ('.$filetype.')';
				MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>$msg]);
				Settings::dieScript($msg);
				break;
		}
		if ((Settings::getBoolVar('smartoptimizer_gzipcompression')===true)&&(!headers_sent())&&(!connection_aborted())) {
			Network::sendHeader("Content-Encoding: gzip");
		} else {
			Settings::setBoolVar('smartoptimizer_gzipcompression', false);
		}
		$filename=Settings::getStringVar('smartoptimizer_cacheprefix').md5($file.(Settings::getBoolVar('smartoptimizer_embed')?'embed1':'embed0').(Settings::getBoolVar('smartoptimizer_stripoutput')?'stripoutput1':'stripoutput0')).'.'.$filetype.(Settings::getBoolVar('smartoptimizer_gzipcompression')?'.gz':'');
		if ((Cache::existsCache(self::getClassName(), $filename, '')!==true)||((self::getFilesModTime([$file])>Cache::getCacheModTime(self::getClassName(), $filename, '')))&&(Settings::getBoolVar('smartoptimizer_servercachecheck')===true)) {
			$generateContent=true;
		} else {
			$generateContent=false;
		}
		if (Settings::getBoolVar('smartoptimizer_clientcache')===true) {
			if ($generateContent!==true) {
				$mtime=Cache::getCacheModTime(self::getClassName(), $filename, '');
			} else {
				$mtime=self::getFilesModTime([$file]);
			}
			$mtimestr=DateTime::convertTimeStamp2GM($mtime);
		}
		if ((Settings::getBoolVar('smartoptimizer_clientcache')!==true)||(Settings::catchValue('HTTP_IF_MODIFIED_SINCE', '', 'r')!=$mtimestr)) {
			if (Settings::getBoolVar('smartoptimizer_clientcache')===true) {
				Network::sendHeader("Last-Modified: ".$mtimestr);
				Network::sendHeader("Cache-Control: must-revalidate");
			} else {
				Network::sendNoCacheHeader();
			}
			$session_parameter='';
			if ((defined('SID')===true)&&(strlen(SID)>0)) {
				$session_parameter.='?'.SID;
			}
			$generateContent=true;
			if ($generateContent===true) {
				$content=[];
				$__DIR__='../../';
				$cfile=Settings::getStringVar('settings_abspath').$file;
				if (file_exists($cfile)) {
					$content=file_get_contents($cfile);
				} else {
					MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>$msg]);
				}
				if (Settings::getBoolVar('smartoptimizer_stripoutput')==true) {
					switch ($filetype) {
						case 'css':
							$content=self::stripCSS($content);
							break;
						case 'js':
							$content=self::stripJS($content);
							break;
					}
				}
				if (Settings::getBoolVar('smartoptimizer_gzipcompression')===true) {
					$content=gzencode($content, Settings::getIntVar('smartoptimizer_gzipcompression_level'));
				}
				if (Cache::writeCache(self::getClassName(), $filename, $content, '')!==true) {
					MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>'Could not create cache file('.$filename.')']);
				}
				if (strlen($session_parameter)>0) {
					str_ireplace('__PARAMETER__', $session_parameter, $content);
				}
				Network::sendHeader('Content-Length: '.strlen($content));
				echo $content;
			} else {
				$content=Cache::readCacheAsString(self::getClassName(), $filename, 0, '');
				if (strlen($session_parameter)>0) {
					str_ireplace('__PARAMETER__', $session_parameter, $content);
				}
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

	/**
	 * Entfernt alle unnötigen Zeichen aus dem CSS-Code.
	 *
	 * @param string $str
	 * @return string
	 */
	public static function stripCSS(string $str):string {
		$res='';
		$i=0;
		$inside_block=false;
		$current_char='';
		while ($i+1<strlen($str)) {
			if ($str[$i]=='"'||$str[$i]=="'") { // quoted string detected
				$res.=$quote=$str[$i++];
				$url='';
				while ($i<strlen($str)&&$str[$i]!=$quote) {
					if ($str[$i]=='\\') {
						$url.=$str[$i++];
					}
					$url.=$str[$i++];
				}
				// if (strtolower(substr($res, -5, 4))=='url('||strtolower(substr($res, -9, 8))=='@import ') {
				// $url=self::convertUrl($url, substr_count($str, $url));
				// }
				$res.=$url;
				$res.=$str[$i++];
				continue;
			} elseif (strtolower(substr($res, -4))=='url(') { // url detected
				$url='';
				do {
					if ($str[$i]=='\\') {
						$url.=$str[$i++];
					}
					$url.=$str[$i++];
				} while ($i<strlen($str)&&$str[$i]!=')');
				// $url=self::convertUrl($url, substr_count($str, $url));
				$res.=$url;
				$res.=$str[$i++];
				continue;
			} elseif ($str[$i].$str[$i+1]=='/*') { // css comment detected
				$i+=3;
				while ($i<strlen($str)&&$str[$i-1].$str[$i]!='*/')
					$i++;
				if ($current_char=="\n")
					$str[$i]="\n"; else
					$str[$i]=' ';
			}
			if (strlen($str)<=$i+1)
				break;
			$current_char=$str[$i];
			if ($inside_block&&$current_char=='}') {
				$inside_block=false;
			}
			if ($current_char=='{') {
				$inside_block=true;
			}
			if (preg_match('/[\n\r\t ]/', $current_char))
				$current_char=" ";
			if ($current_char==" ") {
				$pattern=$inside_block?'/^[^{};,:\n\r\t ]{2}$/':'/^[^{};,>+\n\r\t ]{2}$/';
				if (strlen($res)&&preg_match($pattern, $res[strlen($res)-1].$str[$i+1]))
					$res.=$current_char;
			} else
				$res.=$current_char;
			$i++;
		}
		if ($i<strlen($str)&&preg_match('/[^\n\r\t ]/', $str[$i]))
			$res.=$str[$i];

		return $res;
	}

	/**
	 * Entfernt alle unnötigen Zeichen aus dem JS-Code.
	 *
	 * @param string $str
	 * @return string
	 */
	public static function stripJS(string $str):string {
		$str = str_replace("\r\n", "\n", $str);
		$str = str_replace('/**/', '', $str);
		$str .= PHP_EOL;
		$res='';
		$maybe_regex=true;
		$i=0;
		$current_char='';
		while ($i+1<strlen($str)) {
			if ($maybe_regex&&$str[$i]=='/'&&$str[$i+1]!='/'&&$str[$i+1]!='*'&&@$str[$i-1]!='*') { // regex detected
				if (strlen($res)&&$res[strlen($res)-1]==='/')
					$res.=' ';
				do {
					if ($str[$i]=='\\') {
						$res.=$str[$i++];
					} elseif ($str[$i]=='[') {
						do {
							if ($str[$i]=='\\') {
								$res.=$str[$i++];
							}
							$res.=$str[$i++];
						} while ($i<strlen($str)&&$str[$i]!=']');
					}
					$res.=$str[$i++];
				} while ($i<strlen($str)&&$str[$i]!='/');
				$res.=$str[$i++];
				$maybe_regex=false;
				continue;
			} elseif ($str[$i]=='"'||$str[$i]=="'") { // quoted string detected
				$quote=$str[$i];
				do {
					if ($str[$i]=='\\') {
						$res.=$str[$i++];
					}
					$res.=$str[$i++];
				} while ($i<strlen($str)&&$str[$i]!=$quote);
				$res.=$str[$i++];
				continue;
			} elseif ($str[$i].$str[$i+1]=='/*'&&@$str[$i+2]!='@') { // multi-line comment detected
				$i+=3;
				while ($i<strlen($str)&&$str[$i-1].$str[$i]!='*/')
					$i++;
				if ($current_char=="\n")
					$str[$i]="\n"; else
					$str[$i]=' ';
			} elseif ($str[$i].$str[$i+1]=='//') { // single-line comment detected
				$i+=2;
				while ($i<strlen($str)&&$str[$i]!="\n"&&$str[$i]!="\r")
					$i++;
			}
			$LF_needed=false;
			if (isset($str[$i])) {
				if (preg_match('/[\n\r\t ]/', $str[$i])) {
					if (strlen($res)&&preg_match('/[\n ]/', @$res[strlen($res)-1])) {
						if ($res[strlen($res)-1]=="\n")
							$LF_needed=true;
						$res=substr($res, 0, -1);
					}
					while ($i+1<strlen($str)&&preg_match('/[\n\r\t ]/', $str[$i+1])) {
						if (!$LF_needed&&preg_match('/[\n\r]/', $str[$i]))
							$LF_needed=true;
						$i++;
					}
				}
			}
			if (strlen($str)<=$i+1)
				break;
			$current_char=$str[$i];
			if ($LF_needed)
				$current_char="\n"; 
			elseif ($current_char=="\t")
				$current_char=" ";
			elseif ($current_char=="\r")
				$current_char="\n";
			// detect unnecessary white spaces
			if ($current_char==" ") {
				if (strlen($res)&&(preg_match('/^[^(){}[\]=+\-*\/%&|!><?:~^,;"\']{2}$/', $res[strlen($res)-1].$str[$i+1])||preg_match('/^(\+\+)|(--)$/', $res[strlen($res)-1].$str[$i+1]))) // for example i+ ++j;
					$res.=$current_char;
			} elseif ($current_char=="\n") {
				if (strlen($res)&&(preg_match('/^[^({[=+\-*%&|!><?:~^,;\/][^)}\]=+\-*%&|><?:,;\/]$/', $res[strlen($res)-1].$str[$i+1])||(strlen($res)>1&&preg_match('/^(\+\+)|(--)$/', $res[strlen($res)-2].$res[strlen($res)-1]))||(strlen($str)>$i+2&&preg_match('/^(\+\+)|(--)$/', $str[$i+1].$str[$i+2]))||preg_match('/^(\+\+)|(--)$/', $res[strlen($res)-1].$str[$i+1]))) // || // for example i+ ++j;
					$res.=$current_char;
			} elseif($current_char=="}") {
				$j=1;
				while(($i+$j)<strlen($str)&&preg_match('/[\n\r\t ]/', $str[$i+$j])){
					$j++;
					if(($i+$j+1)<strlen($str)&&$str[$i+$j].$str[$i+$j+1]=='//') { // single-line comment detected
						$j+=2;
						while ($i+$j<strlen($str)&&$str[$i+$j]!="\n"&&$str[$i+$j]!="\r")
							$j++;
					} elseif (($i+$j+2)<strlen($str)&&$str[$i+$j].$str[$i+$j+1]=='/*'&&$str[$i+$j+2]!='@') { // multi-line comment detected
						$j+=3;
						while ($i+$j<strlen($str)&&$str[$i+$j-1].$str[$i+$j]!='*/')
							$j++;
					}
				}
				if((($i+$j)<strlen($str)&&!preg_match('/^[(){}\[\]=*%&|><?:,;.]$/', $str[$i+$j])) &&
					(($i+$j+4)<strlen($str)&&!preg_match('/^(else[\S\s])|(while)|(catch)$/', substr($str,$i+$j,5))) &&
					(($i+$j+6)<strlen($str)&&!preg_match('/^finally$/', substr($str,$i+$j,7))))
					$res.=$current_char.';';
				else
					$res.=$current_char;
			} else
				$res.=$current_char;
			// if the next character be a slash, detects if it is a divide operator or start of a regex
			if (preg_match('/[({[=+\-*\/%&|!><?:~^,;]/', $current_char))
				$maybe_regex=true; elseif (!preg_match('/[\n ]/', $current_char))
				$maybe_regex=false;
			$i++;
		}
		if ($i<strlen($str)&&preg_match('/[^\n\r\t ]/', $str[$i]))
			$res.=$str[$i];

		return $res;
	}

}

?>
