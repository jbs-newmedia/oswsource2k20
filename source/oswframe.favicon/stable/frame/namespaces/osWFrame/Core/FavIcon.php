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

class FavIcon {

	use BaseStaticTrait;
	use BaseTemplateBridgeTrait;

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
	private const CLASS_RELEASE_VERSION=2;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var string
	 */
	protected string $file='';

	/**
	 * @var bool
	 */
	protected bool $favicon=false;

	/**
	 * @var array
	 */
	protected array $icons=[];

	/**
	 * @var array
	 */
	protected array $apple_touch_icons=[];

	/**
	 * @var array
	 */
	protected array $msapplication=[];

	/**
	 * FavIcon constructor.
	 *
	 * @param string $file
	 * @param object $Template
	 */
	public function __construct(string $file, object $Template) {
		$this->setFile($file);
		$this->setFavIcon(true);
		$this->setTemplate($Template);
		$this->setIcons();
		$this->setAppleTouchIcons();
		$this->setMSApplication();
	}

	/**
	 * @return bool
	 */
	public function setIcons2Template():bool {
		$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getFile();
		if (Filesystem::existsFile($file)) {
			$path=dirname($this->getFile());
			$path_filename=pathinfo($file, PATHINFO_FILENAME);
			$path_extension=pathinfo($file, PATHINFO_EXTENSION);
			$options=getimagesize($file);
			if ($this->getFavIcon()===true) {
				$this->getTemplate()->addVoidTag('link', ['rel'=>'shortcut icon', 'type'=>'image/ico', 'href'=>'favicon.ico']);
			}
			foreach ($this->getIcons() as $icon) {
				$osW_ImageOptimizer=new ImageOptimizer();
				$osW_ImageOptimizer->setOptionsByArray(['width'=>$icon['x'], 'height'=>$icon['y']]);
				if (Settings::getBoolVar('imageoptimizer_protect_files')===true) {
					$osW_ImageOptimizer->setPS($this->getFile());
				}
				$new_filename=$path_filename.'.'.$osW_ImageOptimizer->getOptionsAsString().'.'.$path_extension;
				$this->getTemplate()->addVoidTag('link', ['rel'=>'icon', 'type'=>$options['mime'], 'href'=>'static/'.Settings::getStringVar('imageoptimizer_module').'/'.$path.'/'.$new_filename, 'sizes'=>$icon['x'].'x'.$icon['y']]);
			}
			foreach ($this->getAppleTouchIcons() as $icon) {
				$osW_ImageOptimizer=new ImageOptimizer();
				$osW_ImageOptimizer->setOptionsByArray(['width'=>$icon['x'], 'height'=>$icon['y']]);
				if (Settings::getBoolVar('imageoptimizer_protect_files')===true) {
					$osW_ImageOptimizer->setPS($this->getFile());
				}
				$new_filename=$path_filename.'.'.$osW_ImageOptimizer->getOptionsAsString().'.'.$path_extension;
				$this->getTemplate()->addVoidTag('link', ['rel'=>'apple-touch-icon', 'href'=>'static/'.Settings::getStringVar('imageoptimizer_module').'/'.$path.'/'.$new_filename, 'sizes'=>$icon['x'].'x'.$icon['y']]);
			}
			foreach ($this->getMSApplication() as $icon) {
				$osW_ImageOptimizer=new ImageOptimizer();
				$osW_ImageOptimizer->setOptionsByArray(['width'=>$icon['x'], 'height'=>$icon['y']]);
				if (Settings::getBoolVar('imageoptimizer_protect_files')===true) {
					$osW_ImageOptimizer->setPS($this->getFile());
				}
				$new_filename=$path_filename.'.'.$osW_ImageOptimizer->getOptionsAsString().'.'.$path_extension;
				$this->getTemplate()->addVoidTag('meta', ['name'=>'msapplication-square'.$icon['x'].'x'.$icon['y'].'logo', 'content'=>'static/'.Settings::getStringVar('imageoptimizer_module').'/'.$path.'/'.$new_filename]);
			}

			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param string $file
	 * @return $this
	 */
	public function setFile(string $file):self {
		$this->file=$file;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFile():string {
		return $this->file;
	}

	/**
	 * @param bool $status
	 * @return $this
	 */
	public function setFavIcon(bool $status):self {
		$this->favicon=$status;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getFavIcon():bool {
		return $this->favicon;
	}

	/**
	 * @param array|null $icons
	 * @return $this
	 */
	public function setIcons(?array $icons=null):self {
		if ($icons!==null) {
			$this->icons=[];
		} elseif ($icons!=[]) {
			$this->icons=$icons;
		} else {
			$this->icons=[];
			$this->icons[]=['x'=>16, 'y'=>16];
			$this->icons[]=['x'=>32, 'y'=>32];
			$this->icons[]=['x'=>96, 'y'=>96];
			$this->icons[]=['x'=>128, 'y'=>128];
			$this->icons[]=['x'=>196, 'y'=>196];
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getIcons():array {
		return $this->icons;
	}

	/**
	 * @param array|null $icons
	 * @return $this
	 */
	public function setAppleTouchIcons(?array $icons=null):self {
		if ($icons!==null) {
			$this->apple_touch_icons=[];
		} elseif ($icons!=[]) {
			$this->apple_touch_icons=$icons;
		} else {
			$this->apple_touch_icons=[];
			$this->apple_touch_icons[]=['x'=>57, 'y'=>57];
			$this->apple_touch_icons[]=['x'=>60, 'y'=>60];
			$this->apple_touch_icons[]=['x'=>72, 'y'=>72];
			$this->apple_touch_icons[]=['x'=>76, 'y'=>76];
			$this->apple_touch_icons[]=['x'=>57, 'y'=>57];
			$this->apple_touch_icons[]=['x'=>57, 'y'=>57];
			$this->apple_touch_icons[]=['x'=>114, 'y'=>114];
			$this->apple_touch_icons[]=['x'=>120, 'y'=>120];
			$this->apple_touch_icons[]=['x'=>144, 'y'=>144];
			$this->apple_touch_icons[]=['x'=>152, 'y'=>152];
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getAppleTouchIcons() {
		return $this->apple_touch_icons;
	}

	/**
	 * @param array|null $icons
	 * @return $this
	 */
	public function setMSApplication(?array $icons=null):self {
		if ($icons!==null) {
			$this->msapplication=[];
		} elseif ($icons!=[]) {
			$this->msapplication=$icons;
		} else {
			$this->msapplication=[];
			$this->msapplication[]=['x'=>70, 'y'=>70];
			$this->msapplication[]=['x'=>144, 'y'=>144];
			$this->msapplication[]=['x'=>150, 'y'=>150];
			$this->msapplication[]=['x'=>310, 'y'=>310];
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getMSApplication():array {
		return $this->msapplication;
	}

}

?>