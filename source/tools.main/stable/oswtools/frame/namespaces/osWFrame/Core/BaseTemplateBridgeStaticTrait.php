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

trait BaseTemplateBridgeStaticTrait {

	/**
	 * @var object|null
	 */
	public static ?object $obj_Template=null;

	/**
	 * Fügt das Objekt dem Template hinzu und arbeitet über Referenzen.
	 *
	 * @param object $Template
	 * @return bool
	 */
	public static function setTemplate(object $Template):bool {
		self::$obj_Template=$Template;

		return true;
	}

	/**
	 * @return object|null
	 */
	public function getTemplate():?object {
		return self::$obj_Template;
	}

	/**
	 *
	 * @param string $js
	 * @return bool
	 */
	public static function addJSCodeHead(string $jscode):bool {
		return self::$obj_Template->addJSCodeHead($jscode);
	}

	/**
	 *
	 * @param string $js
	 * @return bool
	 */
	public static function addJSCodeBody(string $jscode):bool {
		return self::$obj_Template->addJSCodeBody($jscode);
	}

	/**
	 *
	 * @param string $css
	 * @return bool
	 */
	public static function addCSSCodeHead(string $csscode):bool {
		return self::$obj_Template->addCSSCodeHead($csscode);
	}

	/**
	 *
	 * @param string $css
	 * @return bool
	 */
	public static function addCSSCodeBody(string $csscode):bool {
		return self::$obj_Template->addCSSCodeBody($csscode);
	}

	/**
	 *
	 * @param string $jsfile
	 * @return bool
	 */
	public static function addJSFileHead(string $jsfile):bool {
		return self::$obj_Template->addJSFileHead($jsfile);
	}

	/**
	 *
	 * @param string $jsfile
	 * @return bool
	 */
	public static function addJSFileBody(string $jsfile):bool {
		return self::$obj_Template->addJSFileBody($jsfile);
	}

	/**
	 *
	 * @param string $cssfile
	 * @return bool
	 */
	public static function addCSSFileHead(string $cssfile):bool {
		return self::$obj_Template->addCSSFileHead($cssfile);
	}

	/**
	 *
	 * @param string $cssfile
	 * @return bool
	 */
	public static function addCSSFileBody(string $cssfile):bool {
		return self::$obj_Template->addCSSFileBody($cssfile);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $file
	 * @return bool
	 */
	public static function addTemplateJSFile(string $pos, string $file):bool {
		return self::$obj_Template->addTemplateFile($pos, 'js', $file);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $files
	 * @return bool
	 */
	public static function addTemplateJSFiles(string $pos, array $files):bool {
		return self::$obj_Template->addTemplateFiles($pos, 'js', $files);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $file
	 * @return bool
	 */
	public static function addTemplateCSSFile(string $pos, string $file):bool {
		return self::$obj_Template->addTemplateFile($pos, 'css', $file);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $files
	 * @return bool
	 */
	public static function addTemplateCSSFiles(string $pos, array $files):bool {
		return self::$obj_Template->addTemplateFiles($pos, 'css', $files);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $type
	 * @param string $file
	 * @return bool
	 */
	public static function addTemplateFile(string $pos, string $type, string $file):bool {
		if (!isset(self::$obj_Template->template_files[$pos])) {
			self::$obj_Template->template_files[$pos]=[];
		}
		if (!isset(self::$obj_Template->template_files[$pos][$type])) {
			self::$obj_Template->template_files[$pos][$type]=[];
		}
		self::$obj_Template->template_files[$pos][$type][md5($file)]=$file;

		return true;
	}

	/**
	 *
	 * @param string $pos
	 * @param string $type
	 * @param array $files
	 * @return bool
	 */
	public static function addTemplateFiles(string $pos, string $type, array $files):bool {
		foreach ($files as $file) {
			self::$obj_Template->addTemplateFile($pos, $type, $file);
		}

		return true;
	}

	/**
	 *
	 * @return array
	 */
	public static function clearTemplateFiles():array {
		self::$obj_Template->template_files=[];
	}

	/**
	 *
	 * @param string $pos
	 * @return array
	 */
	public static function getTemplateFiles(string $pos='', string $type=''):array {
		if ($pos!='') {
			if (!isset(self::$obj_Template->template_files[$pos])) {
				return [];
			}
			if ($type!='') {
				if (!isset(self::$obj_Template->template_files[$pos][$type])) {
					return [];
				}

				return self::$obj_Template->template_files[$pos][$type];
			}

			return self::$obj_Template->template_files[$pos];
		}

		return self::$obj_Template->template_files;
	}

	/**
	 *
	 * @param string $pos
	 * @param string $code
	 * @return bool
	 */
	public static function addTemplateJSCode(string $pos, string $code):bool {
		return self::$obj_Template->addTemplateCode($pos, 'js', $code);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $codes
	 * @return bool
	 */
	public static function addTemplateJSCodes(string $pos, array $codes):bool {
		return self::$obj_Template->addTemplateCodes($pos, 'js', $codes);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $code
	 * @return bool
	 */
	public static function addTemplateCSSCode(string $pos, string $code):bool {
		return self::$obj_Template->addTemplateCode($pos, 'css', $code);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $codes
	 * @return bool
	 */
	public static function addTemplateCSSCodes(string $pos, array $codes):bool {
		return self::$obj_Template->addTemplateCodes($pos, 'css', $codes);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $type
	 * @param string $code
	 * @return bool
	 */
	public static function addTemplateCode(string $pos, string $type, string $code):bool {
		if (!isset(self::$obj_Template->template_codes[$pos])) {
			self::$obj_Template->template_codes[$pos]=[];
		}
		if (!isset(self::$obj_Template->template_codes[$pos][$type])) {
			self::$obj_Template->template_codes[$pos][$type]=[];
		}
		self::$obj_Template->template_codes[$pos][$type][md5($code)]=$code;

		return true;
	}

	/**
	 *
	 * @param string $pos
	 * @param string $type
	 * @param array $codes
	 * @return bool
	 */
	public static function addTemplateCodes(string $pos, string $type, array $codes):bool {
		foreach ($codes as $code) {
			self::$obj_Template->addTemplateCode($pos, $type, $code);
		}

		return true;
	}

	/**
	 *
	 * @return array
	 */
	public static function clearTemplateCodes():array {
		self::$obj_Template->template_codes=[];
	}

	/**
	 *
	 * @param string $pos
	 * @return array
	 */
	public static function getTemplateCodes(string $pos='', string $type=''):array {
		if ($pos!='') {
			if (!isset(self::$obj_Template->template_codes[$pos])) {
				return [];
			}
			if ($type!='') {
				if (!isset(self::$obj_Template->template_codes[$pos][$type])) {
					return [];
				}

				return self::$obj_Template->template_codes[$pos][$type];
			}

			return self::$obj_Template->template_codes[$pos];
		}

		return self::$obj_Template->template_codes;
	}

}

?>