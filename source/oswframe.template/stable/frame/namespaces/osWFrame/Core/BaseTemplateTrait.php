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

trait BaseTemplateTrait {

	/**
	 * Speichert alle benötigten Dateien.
	 *
	 * @var array
	 */
	protected array $template_files=[];

	/**
	 * Speichert alle benötigten Dateien.
	 *
	 * @var array
	 */
	protected array $template_codes=[];

	/**
	 *
	 * @param string $js
	 * @return bool
	 */
	public function addJSCodeHead(string $js):bool {
		return $this->addTemplateCode('head', 'js', $js);
	}

	/**
	 *
	 * @param string $js
	 * @return bool
	 */
	public function addJSCodeBody(string $js):bool {
		return $this->addTemplateCode('body', 'js', $js);
	}

	/**
	 *
	 * @param string $css
	 * @return bool
	 */
	public function addCSSCodeHead(string $css):bool {
		return $this->addTemplateCode('head', 'css', $css);
	}

	/**
	 *
	 * @param string $css
	 * @return bool
	 */
	public function addCSSCodeBody(string $css):bool {
		return $this->addTemplateCode('body', 'css', $css);
	}

	/**
	 *
	 * @param string $jsfile
	 * @return bool
	 */
	public function addJSFileHead(string $jsfile):bool {
		return $this->addTemplateFile('head', 'js', $jsfile);
	}

	/**
	 *
	 * @param string $jsfile
	 * @return bool
	 */
	public function addJSFileBody(string $jsfile):bool {
		return $this->addTemplateFile('body', 'js', $jsfile);
	}

	/**
	 *
	 * @param string $cssfile
	 * @return bool
	 */
	public function addCSSFileHead(string $cssfile):bool {
		return $this->addTemplateFile('head', 'css', $cssfile);
	}

	/**
	 *
	 * @param string $cssfile
	 * @return bool
	 */
	public function addCSSFileBody(string $cssfile):bool {
		return $this->addTemplateFile('body', 'css', $cssfile);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $file
	 * @return bool
	 */
	public function addTemplateJSFile(string $pos, string $file):bool {
		return $this->addTemplateFile($pos, 'js', $file);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $files
	 * @return bool
	 */
	public function addTemplateJSFiles(string $pos, array $files):bool {
		return $this->addTemplateFiles($pos, 'js', $files);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $file
	 * @return bool
	 */
	public function addTemplateCSSFile(string $pos, string $file):bool {
		return $this->addTemplateFile($pos, 'css', $file);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $files
	 * @return bool
	 */
	public function addTemplateCSSFiles(string $pos, array $files):bool {
		return $this->addTemplateFiles($pos, 'css', $files);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $type
	 * @param string $file
	 * @return bool
	 */
	public function addTemplateFile(string $pos, string $type, string $file):bool {
		if (!isset($this->template_files[$pos])) {
			$this->template_files[$pos]=[];
		}
		if (!isset($this->template_files[$pos][$type])) {
			$this->template_files[$pos][$type]=[];
		}
		$this->template_files[$pos][$type][md5($file)]=$file;

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
			$this->addTemplateFile($pos, $type, $file);
		}

		return true;
	}

	/**
	 *
	 * @return void
	 */
	public function clearTemplateFiles():void {
		$this->template_files=[];
	}

	/**
	 *
	 * @param string $pos
	 * @return array
	 */
	public function getTemplateFiles(string $pos='', string $type=''):array {
		if ($pos!='') {
			if (!isset($this->template_files[$pos])) {
				return [];
			}
			if ($type!='') {
				if (!isset($this->template_files[$pos][$type])) {
					return [];
				}

				return $this->template_files[$pos][$type];
			}

			return $this->template_files[$pos];
		}

		return $this->template_files;
	}

	/**
	 *
	 * @param string $pos
	 * @param string $code
	 * @return bool
	 */
	public function addTemplateJSCode(string $pos, string $code):bool {
		return $this->addTemplateCode($pos, 'js', $code);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $codes
	 * @return bool
	 */
	public function addTemplateJSCodes(string $pos, array $codes):bool {
		return $this->addTemplateCodes($pos, 'js', $codes);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $code
	 * @return bool
	 */
	public function addTemplateCSSCode(string $pos, string $code):bool {
		return $this->addTemplateCode($pos, 'css', $code);
	}

	/**
	 *
	 * @param string $pos
	 * @param array $codes
	 * @return bool
	 */
	public function addTemplateCSSCodes(string $pos, array $codes):bool {
		return $this->addTemplateCodes($pos, 'css', $codes);
	}

	/**
	 *
	 * @param string $pos
	 * @param string $type
	 * @param string $code
	 * @return bool
	 */
	public function addTemplateCode(string $pos, string $type, string $code):bool {
		if (!isset($this->template_codes[$pos])) {
			$this->template_codes[$pos]=[];
		}
		if (!isset($this->template_codes[$pos][$type])) {
			$this->template_codes[$pos][$type]=[];
		}
		$this->template_codes[$pos][$type][md5($code)]=$code;

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
			$this->addTemplateCode($pos, $type, $code);
		}

		return true;
	}

	/**
	 *
	 * @return void
	 */
	public function clearTemplateCodes():void {
		$this->template_codes=[];
	}

	/**
	 *
	 * @param string $pos
	 * @return array
	 */
	public function getTemplateCodes(string $pos='', string $type=''):array {
		if ($pos!='') {
			if (!isset($this->template_codes[$pos])) {
				return [];
			}
			if ($type!='') {
				if (!isset($this->template_codes[$pos][$type])) {
					return [];
				}

				return $this->template_codes[$pos][$type];
			}

			return $this->template_codes[$pos];
		}

		return $this->template_codes;
	}

}

?>