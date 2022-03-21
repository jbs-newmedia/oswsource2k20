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

namespace osWFrame\Tools\Tool;

use osWFrame\Core as Frame;

class PHPInfo extends CoreTool {

	use Frame\BaseStaticTrait;

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
	protected string $phpinfo='';

	/**
	 * PHPInfo constructor.
	 *
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 */
	public function __construct(string $serverlist, string $package, string $release) {
		parent::__construct($serverlist, $package, $release);
	}

	/**
	 * @return string
	 */
	public function getContent():string {
		if ($this->phpinfo=='') {
			ob_start();
			phpinfo();
			$this->phpinfo=ob_get_clean();

			# Body-Content rausholen
			$this->phpinfo=preg_replace('#^.*<body>(.*)</body>.*$#s', '$1', $this->phpinfo);
			# HTML5-Fehler korrigieren
			$this->phpinfo=str_replace('module_Zend Optimizer', 'module_Zend_Optimizer', $this->phpinfo);
			$this->phpinfo=str_replace('module_Zend OPcache', 'module_Zend_OPcache', $this->phpinfo);
			$this->phpinfo=str_replace('<a name="', '<a id="', $this->phpinfo);
			$this->phpinfo=str_replace('<img border="0"', '<img', $this->phpinfo);
			# <font> durch <span> ersetzen
			$this->phpinfo=str_replace('<font', '<span', $this->phpinfo);
			$this->phpinfo=str_replace('</font>', '</span>', $this->phpinfo);
			#Table
			$this->phpinfo=str_replace('<table>', '<table class="table table-bordered table-striped" style="table-layout: fixed;word-wrap: break-word;">', $this->phpinfo);
			# Schlüsselwörter grün oder rot einfärben
			$this->phpinfo=preg_replace('#>(on|enabled|active)#i', '><span class="text-success">$1</span>', $this->phpinfo);
			$this->phpinfo=preg_replace('#>(off|disabled)#i', '><span class="text-danger">$1</span>', $this->phpinfo);
			$this->phpinfo=str_replace('<a href="', '<a target="_blank" href="', $this->phpinfo);

		}

		return $this->phpinfo;
	}

}

?>