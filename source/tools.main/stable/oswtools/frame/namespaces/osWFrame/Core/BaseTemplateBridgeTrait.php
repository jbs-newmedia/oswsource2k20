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

trait BaseTemplateBridgeTrait {

	public ?object $obj_Template=null;

	/**
	 * Fügt das Objekt dem Template hinzu und arbeitet über Referenzen.
	 *
	 * @param object $obj_Template
	 * @return bool
	 */
	public function setTemplate(object $Template):bool {
		$this->obj_Template=$Template;

		return true;
	}

	/**
	 *
	 * @return object|null
	 */
	public function getTemplate():?object {
		return $this->obj_Template;
	}

	/**
	 *
	 * @param string $js
	 * @return bool
	 */
	public function addJSCodeHead(string $jscode):bool {
		return $this->obj_Template->addJSCodeHead($jscode);
	}

	/**
	 *
	 * @param string $js
	 * @return bool
	 */
	public function addJSCodeBody(string $jscode):bool {
		return $this->obj_Template->addJSCodeBody($jscode);
	}

	/**
	 *
	 * @param string $css
	 * @return bool
	 */
	public function addCSSCodeHead(string $csscode):bool {
		return $this->obj_Template->addCSSCodeHead($csscode);
	}

	/**
	 *
	 * @param string $css
	 * @return bool
	 */
	public function addCSSCodeBody(string $csscode):bool {
		return $this->obj_Template->addCSSCodeBody($csscode);
	}

	/**
	 *
	 * @param string $jsfile
	 * @return bool
	 */
	public function addJSFileHead(string $jsfile):bool {
		return $this->obj_Template->addJSFileHead($jsfile);
	}

	/**
	 *
	 * @param string $jsfile
	 * @return bool
	 */
	public function addJSFileBody(string $jsfile):bool {
		return $this->obj_Template->addJSFileBody($jsfile);
	}

	/**
	 *
	 * @param string $cssfile
	 * @return bool
	 */
	public function addCSSFileHead(string $cssfile):bool {
		return $this->obj_Template->addCSSFileHead($cssfile);
	}

	/**
	 *
	 * @param string $cssfile
	 * @return bool
	 */
	public function addCSSFileBody(string $cssfile):bool {
		return $this->obj_Template->addCSSFileBody($cssfile);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $file
	 * @return bool
	 */
	public function addTemplateJSFile(string $pos, string $file):bool {
		return $this->obj_Template->addTemplateFile($pos, 'js', $file);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $files
	 * @return bool
	 */
	public function addTemplateJSFiles(string $pos, array $files):bool {
		return $this->obj_Template->addTemplateFiles($pos, 'js', $files);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $file
	 * @return bool
	 */
	public function addTemplateCSSFile(string $pos, string $file):bool {
		return $this->obj_Template->addTemplateFile($pos, 'css', $file);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $files
	 * @return bool
	 */
	public function addTemplateCSSFiles(string $pos, array $files):bool {
		return $this->obj_Template->addTemplateFiles($pos, 'css', $files);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $type
	 * @param string $file
	 * @return bool
	 */
	public function addTemplateFile(string $pos, string $type, string $file):bool {
		if (!isset($this->obj_Template->template_files[$pos])) {
			$this->obj_Template->template_files[$pos]=[];
		}
		if (!isset($this->obj_Template->template_files[$pos][$type])) {
			$this->obj_Template->template_files[$pos][$type]=[];
		}
		$this->obj_Template->template_files[$pos][$type][md5($file)]=$file;

		return true;
	}

	/**
	 *
	 * @param string $pos
	 * @param string $type
	 * @param array $files
	 * @return bool
	 */
	public function addTemplateFiles(string $pos, string $type, array $files):bool {
		foreach ($files as $file) {
			$this->obj_Template->addTemplateFile($pos, $type, $file);
		}

		return true;
	}

	/**
	 *
	 * @return array
	 */
	public function clearTemplateFiles():array {
		$this->obj_Template->template_files=[];
	}

	/**
	 *
	 * @param string $pos
	 * @return array
	 */
	public function getTemplateFiles(string $pos='', string $type=''):array {
		if ($pos!='') {
			if (!isset($this->obj_Template->template_files[$pos])) {
				return [];
			}
			if ($type!='') {
				if (!isset($this->obj_Template->template_files[$pos][$type])) {
					return [];
				}

				return $this->obj_Template->template_files[$pos][$type];
			}

			return $this->obj_Template->template_files[$pos];
		}

		return $this->obj_Template->template_files;
	}

	/**
	 *
	 * @param string $pos
	 * @param string $code
	 * @return bool
	 */
	public function addTemplateJSCode(string $pos, string $code):bool {
		return $this->obj_Template->addTemplateCode($pos, 'js', $code);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $codes
	 * @return bool
	 */
	public function addTemplateJSCodes(string $pos, array $codes):bool {
		return $this->obj_Template->addTemplateCodes($pos, 'js', $codes);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $code
	 * @return bool
	 */
	public function addTemplateCSSCode(string $pos, string $code):bool {
		return $this->obj_Template->addTemplateCode($pos, 'css', $code);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $codes
	 * @return bool
	 */
	public function addTemplateCSSCodes(string $pos, array $codes):bool {
		return $this->obj_Template->addTemplateCodes($pos, 'css', $codes);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $type
	 * @param string $code
	 * @return bool
	 */
	public function addTemplateCode(string $pos, string $type, string $code):bool {
		if (!isset($this->obj_Template->template_codes[$pos])) {
			$this->obj_Template->template_codes[$pos]=[];
		}
		if (!isset($this->obj_Template->template_codes[$pos][$type])) {
			$this->obj_Template->template_codes[$pos][$type]=[];
		}
		$this->obj_Template->template_codes[$pos][$type][md5($code)]=$code;

		return true;
	}

	/**
	 *
	 * @param string $pos
	 * @param string $type
	 * @param array $codes
	 * @return bool
	 */
	public function addTemplateCodes(string $pos, string $type, array $codes):bool {
		foreach ($codes as $code) {
			$this->obj_Template->addTemplateCode($pos, $type, $code);
		}

		return true;
	}

	/**
	 *
	 * @return array
	 */
	public function clearTemplateCodes():array {
		$this->obj_Template->template_codes=[];
	}

	/**
	 *
	 * @param string $pos
	 * @return array
	 */
	public function getTemplateCodes(string $pos='', string $type=''):array {
		if ($pos!='') {
			if (!isset($this->obj_Template->template_codes[$pos])) {
				return [];
			}
			if ($type!='') {
				if (!isset($this->obj_Template->template_codes[$pos][$type])) {
					return [];
				}

				return $this->obj_Template->template_codes[$pos][$type];
			}

			return $this->obj_Template->template_codes[$pos];
		}

		return $this->obj_Template->template_codes;
	}

}

?>