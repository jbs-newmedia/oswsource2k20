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

class Network {

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
	 * Network constructor.
	 */
	private function __construct() {

	}

	/**
	 * Setzt einen HTTP-Header und sendet diesen.
	 *
	 * @param string $header
	 * @return bool
	 */
	public static function sendHeader(string $header):bool {
		header($header);

		return true;
	}

	/**
	 *
	 * @param string $link
	 * @param int $status
	 */
	public static function directHeader(string $link='', int $status=302) {
		$link=str_replace('&amp;', '&', $link);
		switch ($status) {
			case 301:
				$_header='HTTP/1.1 301 Moved Permanently';
				break;
			case 302:
			default:
				$_header='HTTP/1.1 302 Found';
				break;
		}
		$filename='';
		$linenum=0;
		if (!headers_sent($filename, $linenum)) {
			self::sendHeader($_header);
			self::sendHeader('Location: '.$link);
			self::sendHeader('Connection: close');
		} else {
			echo 'Header already sent in file <strong>'.$filename.'</strong> in line <strong>'.$linenum.'</strong>.<br/>';
			echo 'Redirect is not possible!';
			echo '<br/><br/>'.$_header.'<br/>';
			echo 'Location: <a href="'.$link.'">'.HTML::outputString($link).'</a><br/>';
			echo 'Connection: close';
		}
		Settings::dieScript();
	}

	/**
	 * Senden einen NoCache Header.
	 * Daten werden auf jeden Fall runtergeladen.
	 *
	 * @return bool
	 */
	public static function sendNoCacheHeader():bool {
		self::sendHeader("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		// always modified
		self::sendHeader("Last-Modified: ".DateTime::convertTimeStamp2GM());
		// HTTP/1.1
		self::sendHeader("Cache-Control: no-store, no-cache, must-revalidate");
		self::sendHeader("Cache-Control: post-check=0, pre-check=0");
		self::sendHeader("Cache-Control: max-age=0");
		// generate a unique Etag each time
		self::sendHeader('Etag: '.microtime());

		return true;
	}

	/**
	 * Gibt die IP-Adresse zurück.
	 *
	 * @link https://stackoverflow.com/questions/1634782/what-is-the-most-accurate-way-to-retrieve-a-users-correct-ip-address-in-php
	 * @return string
	 */
	public static function getIPAddress():string {
		// Check for shared internet/ISP IP
		if ((!empty($_SERVER['HTTP_CLIENT_IP']))&&(self::validateIP($_SERVER['HTTP_CLIENT_IP']))) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		// Check for IPs passing through proxies
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			// Check if multiple IP addresses exist in var
			$iplist=explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			foreach ($iplist as $ip) {
				if (self::validateIP($ip)) {
					return $ip;
				}
			}
		}
		if ((!empty($_SERVER['HTTP_X_FORWARDED']))&&(self::validateIP($_SERVER['HTTP_X_FORWARDED']))) {
			return $_SERVER['HTTP_X_FORWARDED'];
		}
		if ((!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))&&(self::validateIP($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))) {
			return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		}
		if ((!empty($_SERVER['HTTP_FORWARDED_FOR']))&&(self::validateIP($_SERVER['HTTP_FORWARDED_FOR']))) {
			return $_SERVER['HTTP_FORWARDED_FOR'];
		}
		if ((!empty($_SERVER['HTTP_FORWARDED']))&&(self::validateIP($_SERVER['HTTP_FORWARDED']))) {
			return $_SERVER['HTTP_FORWARDED'];
		}

		// Return unreliable IP address since all else failed
		return $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * @return string
	 */
	public static function getCurrentUrl():string {
		$server_port=intval($_SERVER['SERVER_PORT']);
		if ($_SERVER['REQUEST_SCHEME']=='https') {
			if (($server_port!=Settings::getIntVar('project_ssl_port'))||(Settings::getIntVar('project_ssl_port')!=Settings::getIntVar('settings_ssl_port'))) {
				return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$server_port.$_SERVER['REQUEST_URI'];
			}
		} elseif ($_SERVER['REQUEST_SCHEME']=='http') {
			if (($server_port!=Settings::getIntVar('project_port'))||(Settings::getIntVar('project_port')!=Settings::getIntVar('settings_port'))) {
				return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$server_port.$_SERVER['REQUEST_URI'];
			}
		}

		return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	}

	/**
	 * Validiert eine IP-Adesse.
	 *
	 * @param string $ip
	 * @return bool
	 */
	public static function validateIP(string $ip):bool {
		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)===false) {
			return false;
		}

		return true;
	}

	/**
	 * Prüft zwei IPs gegen eine definiertes Muster.
	 *
	 * @param string $ip1
	 * @param string $ip2
	 * @param string $scheme
	 * @return bool
	 */
	public static function verifyIP(string $ip1, string $ip2, string $scheme='x.x.x.x'):bool {
		if (self::validateIP($ip1)!==true) {
			return false;
		}
		if (self::validateIP($ip2)!==true) {
			return false;
		}
		$ip1=explode('.', $ip1);
		$ip2=explode('.', $ip2);
		switch ($scheme) {
			case 'x.x.x':
				if ((count($ip1)==4)&&(count($ip2)==4)&&($ip1[0]==$ip2[0])&&($ip1[1]==$ip2[1])&&($ip1[2]==$ip2[2])) {
					return true;
				}
				break;
			case 'x.x':
				if ((count($ip1)==4)&&(count($ip2)==4)&&($ip1[0]==$ip2[0])&&($ip1[1]==$ip2[1])) {
					return true;
				}
				break;
			case 'x':
				if ((count($ip1)==4)&&(count($ip2)==4)&&($ip1[0]==$ip2[0])) {
					return true;
				}
				break;
			case 'x.x.x.x':
			default:
				if ((count($ip1)==4)&&(count($ip2)==4)&&($ip1[0]==$ip2[0])&&($ip1[1]==$ip2[1])&&($ip1[2]==$ip2[2])&&($ip1[3]==$ip2[3])) {
					return true;
				}
				break;
		}

		return false;
	}

	/**
	 * @param array $json
	 */
	public static function dieJSON(array $json=[]) {
		self::sendHeader('Content-Type: application/json');
		Settings::dieScript(json_encode($json));
	}

	/**
	 * @param string $filename
	 */
	public static function diePDF(string $filename) {
		header('Content-Type: application/pdf');
		header('Content-Length: '.filesize($filename));
		header('Content-disposition: inline; filename="'.basename($filename).'"');
		header('Cache-Control: public, must-revalidate, max-age=0');
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		Settings::dieScript(file_get_contents($filename));
	}

}

?>