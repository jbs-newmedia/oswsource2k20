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

class ImageLib {

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
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var resource
	 */
	private $image=null;

	/**
	 * @var array
	 */
	private array $options=[];

	/**
	 * ImageLib constructor.
	 *
	 * @param string $filename
	 */
	public function __construct(string $filename='') {
		if ($filename!='') {
			$this->load($filename);
		}
	}

	/**
	 * @param string $filename
	 * @return bool
	 */
	public function load(string $filename):bool {
		$_options=getimagesize($filename);
		$this->options['image']=[];
		$this->options['image']['width']=$_options[0];
		$this->options['image']['height']=$_options[1];
		$this->options['image']['type']=$_options[2];
		$this->options['image']['bits']=$_options['bits'];
		$this->options['image']['mime']=$_options['mime'];

		$this->options['options']=[];
		$this->options['options']['width']=$this->options['image']['width'];
		$this->options['options']['height']=$this->options['image']['height'];
		$this->setQuality(Settings::getIntVar('imagelib_quality'));
		$this->options['options']['type']=$this->options['image']['type'];
		$this->options['options']['alphablending']=false;
		$this->options['options']['savealpha']=true;

		if ($this->options['image']['type']==IMAGETYPE_JPEG) {
			$this->image=imagecreatefromjpeg($filename);

			return true;
		} elseif ($this->options['image']['type']==IMAGETYPE_GIF) {
			$this->image=imagecreatefromgif($filename);

			return true;
		} elseif ($this->options['image']['type']==IMAGETYPE_PNG) {
			$this->image=imagecreatefrompng($filename);

			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function unload():bool {
		imagedestroy($this->image);
		$this->image=null;

		return true;
	}

	/**
	 * @param string $filename
	 * @return bool
	 */
	public function save(string $filename):bool {
		imagealphablending($this->image, $this->options['options']['alphablending']);
		imagesavealpha($this->image, $this->options['options']['savealpha']);

		if ($this->options['options']['type']==IMAGETYPE_JPEG) {
			imagejpeg($this->image, $filename, $this->options['options']['quality']);
		} elseif ($this->options['options']['type']==IMAGETYPE_GIF) {
			imagegif($this->image, $filename);
		} elseif ($this->options['options']['type']==IMAGETYPE_PNG) {
			imagepng($this->image, $filename, 9);
		}
		Filesystem::changeFilemode($filename);

		return true;
	}

	/**
	 * @param bool $header
	 * @param bool $die
	 * @return bool
	 */
	public function output(bool $header=true, bool $die=false):bool {
		imagealphablending($this->image, $this->options['options']['alphablending']);
		imagesavealpha($this->image, $this->options['options']['savealpha']);

		if ($this->options['options']['type']==IMAGETYPE_JPEG) {
			if ($header===true) {
				header('Content-Type: image/jpg');
			}
			/*
			 * 0 (worst quality, smaller file) to 100 (best quality, biggest file)
			 */
			imagejpeg($this->image, null, $this->options['options']['quality']);
		} elseif ($this->options['options']['type']==IMAGETYPE_GIF) {
			if ($header===true) {
				header('Content-Type: image/gif');
			}
			imagegif($this->image, null);
		} elseif ($this->options['options']['type']==IMAGETYPE_PNG) {
			if ($header===true) {
				header('Content-Type: image/png');
			}
			/*
			 * quality 0-9 0 is NO COMPRESSION at all, 1 is FASTEST but produces larger files, 9 provides the best compression (smallest files) but takes a long time to compress, and -1 selects the default compiled into the zlib library.
			 */
			imagepng($this->image, null, 9);
		}
		if ($die===true) {
			Settings::dieScript();
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function outputStream():string {
		ob_start();
		$this->output(false);
		$image=ob_get_contents();
		ob_end_clean();

		return $image;
	}

	/**
	 * @param int $quality
	 * @return bool
	 */
	public function setQuality(int $quality):bool {
		$quality=intval($quality);
		if (($quality<0)||($quality>100)) {
			$this->options['options']['quality']=Settings::getIntVar('imagelib_quality');
		} else {
			$this->options['options']['quality']=$quality;
		}

		return true;
	}

	/**
	 * @return int
	 */
	public function getWidth():int {
		return $this->getImageWidth();
	}

	/**
	 * @return int
	 */
	public function getHeight():int {
		return $this->getImageHeight();
	}

	/**
	 * @return int
	 */
	public function getImageWidth():int {
		return imagesx($this->image);
	}

	/**
	 * @return int
	 */
	public function getImageHeight():int {
		return imagesy($this->image);
	}

	/**
	 * @param int $width
	 * @param int $height
	 * @return bool
	 */
	public function resize(int $width, int $height):bool {
		$this->options['options']['width']=intval($width);
		$this->options['options']['height']=intval($height);
		if ($this->checkSizeLimits()===true) {
			;
			$new_image=imagecreatetruecolor($width, $height);

			imagealphablending($new_image, $this->options['options']['alphablending']);
			imagesavealpha($new_image, $this->options['options']['savealpha']);

			imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getImageWidth(), $this->getImageHeight());
			$this->image=$new_image;

			return true;
		}

		return false;
	}

	/**
	 * @param int $height
	 * @return bool
	 */
	public function resizeToHeight(int $height):bool {
		$height=intval($height);
		$ratio=$height/$this->getImageHeight();
		$width=intval(round($this->getImageWidth()*$ratio));

		return $this->resize($width, $height);
	}

	/**
	 * @param int $width
	 * @return bool
	 */
	public function resizeToWidth(int $width):bool {
		$width=intval($width);
		$ratio=$width/$this->getImageWidth();
		$height=intval(round($this->getImageHeight()*$ratio));

		return $this->resize($width, $height);
	}

	/**
	 * @param int $size
	 * @return bool
	 */
	public function resizeToLongest(int $size):bool {
		$ratio_w=$size/$this->getImageWidth();
		$ratio_h=$size/$this->getImageHeight();
		if ($ratio_h>$ratio_w) {
			$width=$this->getImageWidth()*$ratio_w;
			$height=$this->getImageHeight()*$ratio_w;
		} else {
			$width=$this->getWidth()*$ratio_h;
			$height=$this->getImageHeight()*$ratio_h;
		}

		return $this->resize($width, $height);
	}

	/**
	 * @return bool
	 */
	private function checkSizeLimits():bool {
		/* ToDo */
		return true;
	}

	/**
	 * @param int $scale
	 * @return bool
	 */
	public function scale(int $scale):bool {
		$width=$this->getWidth()*$scale/100;
		$height=$this->getheight()*$scale/100;

		return $this->resize($width, $height);
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $width
	 * @param int $height
	 * @return bool
	 */
	public function cut(int $x, int $y, int $width, int $height):bool {
		$new_image=imagecreatetruecolor($width, $height);

		imagealphablending($new_image, $this->options['options']['alphablending']);
		imagesavealpha($new_image, $this->options['options']['savealpha']);

		imagecopyresampled($new_image, $this->image, 0, 0, $x, $y, $width, $height, $width, $height);
		$this->image=$new_image;

		return true;
	}

	/**
	 * @return bool
	 */
	public function cropSquare():bool {
		$img_width=$this->getWidth();
		$img_height=$this->getheight();
		if ($img_width>$img_height) {
			$div=bcdiv(($img_width-$img_height), 2);

			return $this->cut($div, 0, $img_height, $img_height);
		} elseif ($img_height>$img_width) {
			$div=bcdiv(($img_height-$img_width), 2);

			return $this->cut(0, $div, $img_width, $img_width);
		} else {
			return true;
		}
	}

	/**
	 * @param int $size
	 * @return bool
	 */
	public function cropSquareResized(int $size):bool {
		$this->cropSquare();

		return $this->resize($size, $size);
	}

	/**
	 * @param int $ratio
	 * @return bool
	 */
	public function cropRectangle(int $ratio):bool {
		if ($ratio==1) {
			return $this->cropSquare();
		}
		$img_width=$this->getWidth();
		$img_height=$this->getheight();
		$img_ratio=floatval($img_width/$img_height);
		if ($ratio>$img_ratio) {
			$width_new=$img_width;
			$height_new=round($width_new/$ratio);

			return $this->cut(0, bcdiv(($img_height-$height_new), 2), $width_new, $height_new);
		} else {
			$height_new=$img_height;
			$width_new=round($height_new*$ratio);

			return $this->cut(bcdiv(($img_width-$width_new), 2), 0, $width_new, $height_new);
		}
	}

	/**
	 * @param int $width
	 * @param int $height
	 * @return bool
	 */
	public function cropRectangleResized(int $width, int $height):bool {
		if ($width==$height) {
			return $this->cropSquareResized($height);
		}
		$ratio=floatval($width/$height);
		$this->cropRectangle($ratio);
		if ($width>$height) {
			return $this->resizeToLongest($width);
		} else {
			return $this->resizeToLongest($height);
		}
	}

}

?>