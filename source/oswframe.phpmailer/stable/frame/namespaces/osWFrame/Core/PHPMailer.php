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

class PHPMailer extends \PHPMailer\PHPMailer\PHPMailer {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=2;

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
	 * PHPMailer constructor.
	 *
	 * @param null $exceptions
	 */
	public function __construct($exceptions=null) {
		parent::__construct($exceptions);

		$this->setCharSet('utf-8');
	}

	/**
	 * @param $langcode
	 * @param $lang_path
	 * @return bool
	 */
	public function setLanguage($langcode='en', $lang_path=''):bool {
		if ($lang_path==='') {
			$lang_path=Settings::getStringVar('settings_abspath').DIRECTORY_SEPARATOR.'oswvendor'.DIRECTORY_SEPARATOR.'namespaces'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.Settings::getStringVar('vendor_namespace_phpmailer_phpmailer').DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR;
		}

		return parent::setLanguage($langcode, $lang_path);
	}

	/**
	 * @param bool $value
	 * @return bool
	 */
	public function setSMTPDebug(bool $value):bool {
		$this->SMTPDebug=$value;

		return true;
	}

	/**
	 * @param bool $value
	 * @return bool
	 */
	public function setSMTPAutoTLS(bool $value):bool {
		$this->SMTPAutoTLS=$value;

		return true;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	public function setHost(string $value):bool {
		$this->Host=$value;

		return true;
	}

	/**
	 * @param int $value
	 * @return bool
	 */
	public function setPort(int $value):bool {
		$this->Port=$value;

		return true;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	public function setCharSet(string $value):bool {
		$this->CharSet=$value;

		return true;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	public function setEncoding(string $value):bool {
		$this->Encoding=$value;

		return true;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	public function setUsername(string $value):bool {
		$this->Username=$value;

		return true;
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function setPassword($value) {
		$this->Password=$value;

		return true;
	}

	/**
	 * @param bool $value
	 * @return bool
	 */
	public function setSMTPAuth(bool $value):bool {
		$this->SMTPAuth=$value;

		return true;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	public function setSMTPSecure(string $value):bool {
		switch ($value) {
			case 'ssl':
				$this->SMTPSecure='ssl';
				break;
			case 'tls':
				$this->SMTPSecure='tls';
				break;
			default:
				$this->SMTPSecure='';
				break;
		}

		return true;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	public function setSubject(string $value):bool {
		$this->Subject=$value;

		return true;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	public function setBody(string $value):bool {
		$this->Body=$value;

		return true;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	public function setAltBody(string $value):bool {
		$this->AltBody=StringFunctions::convertHTML2Plain($value);

		return true;
	}

	/**
	 * @return bool
	 */
	public function sendMail():bool {
		$return=$this->Send();
		if ($this->getErrorInfo()!='') {
			MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>$this->getErrorInfo(), 'to'=>$this->addrAppend('To', $this->getToAddresses()), 'cc'=>$this->addrAppend('Cc', $this->getCcAddresses()), 'bcc'=>$this->addrAppend('Bcc', $this->getBccAddresses()), 'reply-to'=>$this->addrAppend('Reply-To', $this->getReplyToAddresses()), 'subject'=>$this->encodeHeader($this->secureHeader($this->Subject))]);
		}

		return $return;
	}

	/**
	 * @return string
	 */
	public function getErrorInfo():string {
		return $this->ErrorInfo;
	}

	/**
	 * @return bool
	 */
	public function clearMailer():bool {
		$this->ErrorInfo='';
		$this->setSubject('');
		$this->setAltBody('');
		$this->setBody('');
		$this->clearAddresses();
		$this->clearQueuedAddresses();
		$this->clearCCs();
		$this->clearBCCs();
		$this->clearReplyTos();
		$this->clearAllRecipients();
		$this->clearAttachments();
		$this->clearCustomHeaders();

		return true;
	}

}

?>