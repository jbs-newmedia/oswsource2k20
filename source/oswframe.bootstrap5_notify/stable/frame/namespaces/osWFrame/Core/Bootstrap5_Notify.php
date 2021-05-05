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

class Bootstrap5_Notify {

	use BaseStaticTrait;
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
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * Bootstrap5 Version.
	 *
	 * @var string
	 */
	private const CURRENT_RESOURCE_VERSION='4.3.1';

	/**
	 * Bootstrap5_Notify constructor.
	 *
	 * @param object $Template
	 * @param string $version
	 * @param bool $min
	 */
	public function __construct(object $Template, string $version='current', bool $min=true) {
		$this->setTemplate($Template);
	}

	/**
	 *
	 * @param string $msg
	 * @param string $type
	 * @param array $_options
	 * @param bool $addfunction
	 * @return bool
	 */
	public function sendNotify(string $msg, string $type='success', array $_options=[], bool $addfunction=true):bool {
		switch ($type) {
			case 'info':
				$type='info';
				break;
			case 'warning':
				$type='warning';
				break;
			case 'error':
			case 'danger':
				$type='danger';
				break;
			case 'success':
			default:
				$type='success';
				break;
		}
		$options=[];
		$options['offset']['x']=20;
		$options['offset']['y']=60;
		$options['placement']['from']='top';
		$options['placement']['align']='center';
		$options['delay']=2500;
		$options['mouse_over']='pause';
		$options['type']=$type;
		$options=array_merge_recursive($options, $_options);
		$c='';
		if ($addfunction===true) {
			$c.='
$(function() {';
		}
		$c.='
	$.notify({
		message: \''.addslashes($msg).'\'
	},
		'.json_encode($options).'
	);';
		if ($addfunction===true) {
			$c.='
});';
		}
		$this->addTemplateJSCode('head', $c);

		return true;
	}

	/**
	 *
	 * @param string $msg
	 * @param string $type
	 * @param array $_options
	 * @param bool $addfunction
	 * @return string
	 */
	public function getNotifyCode(string $msg, string $type='success', array $_options=[], bool $addfunction=true):string {
		switch ($type) {
			case 'info':
				$type='info';
				break;
			case 'warning':
				$type='warning';
				break;
			case 'error':
			case 'danger':
				$type='danger';
				break;
			case 'success':
			default:
				$type='success';
				break;
		}
		$options=[];
		$options['offset']['x']=20;
		$options['offset']['y']=60;
		$options['placement']['from']='top';
		$options['placement']['align']='center';
		$options['delay']=2500;
		$options['mouse_over']='pause';
		$options['type']=$type;
		$options=array_merge_recursive($options, $_options);
		$c='';
		if ($addfunction===true) {
			$c.='
$(function() {';
		}
		$c.='
	$.notify({
		message: \''.addslashes($msg).'\'
	},
		'.json_encode($options).'
	);';
		if ($addfunction===true) {
			$c.='
});';
		}

		return $c;
	}

}

?>