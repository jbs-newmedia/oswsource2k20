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

######################################################################################################################################################
# Config
######################################################################################################################################################
error_reporting(0);

$server_data=['server_name'=>'$SERVER_NAME$', 'server_version'=>'6.10', 'server_url'=>'$SERVER_URL$', 'server_file'=>'$SERVER_FILE$', 'server_list_name'=>'$SERVER_LIST_NAME$', 'server_list'=>'$SERVER_LIST$', 'server_secure'=>'$SERVER_SECURE$', 'server_token'=>'$SERVER_TOKEN$', 'server_status'=>1,];

######################################################################################################################################################
# Funktionen
######################################################################################################################################################
function logUpdateServer($server, $package, $release, $version, $version_requested, $option) {
	$url='https://oswframe.com/updatelogger?server='.urlencode($server).'&package='.$package.'&release='.$release.'&version='.$version.'&version_requested='.$version_requested.'&option='.$option;
	@file_get_contents($url);
}

function _mc_encrypt($var_1, $var_2) {
	$l=strlen($var_2);
	if ($l<16) {
		$var_2=str_repeat($var_2, ceil(16/$l));
	}
	if ($m=strlen($var_1)%8) {
		$var_1.=str_repeat("\x00", 8-$m);
	}

	return openssl_encrypt($var_1, 'BF-ECB', $var_2, OPENSSL_RAW_DATA|OPENSSL_NO_PADDING);
}

function _mc_decrypt($var_1, $var_2) {
	$l=strlen($var_2);
	if ($l<16) {
		$var_2=str_repeat($var_2, ceil(16/$l));
	}

	return openssl_decrypt($var_1, 'BF-ECB', $var_2, OPENSSL_RAW_DATA|OPENSSL_NO_PADDING);
}

function _mkDir($dir, $chmod=0775) {
	clearstatcache();
	if (is_dir($dir)) {
		return true;
	}

	$_dir=explode('/', $dir);
	unset($_dir[count($_dir)]);
	unset($_dir[0]);
	$dir='/'.implode('/', $_dir);

	if (!is_dir($dir)) {
		$path='/';
		foreach ($_dir as $dir) {
			if (!is_dir($path)) {
				mkdir($path);
				chmod($path, $chmod);
			}
			clearstatcache();
			$path.=$dir.'/';
		}
	}
	if (!is_dir($path)) {
		mkdir($path);
		chmod($path, $chmod);
	}
	clearstatcache();

	return true;
}

function _delDir($dir) {
	$entry=opendir($dir);
	while ($file=readdir($entry)) {
		$path=$dir.'/'.$file;
		if ($file!="."&&$file!="..") {
			if (is_dir($path)) {
				deldir($path);
			} else {
				unlink($path);
			}
		}
	}
	closedir($entry);
	rmdir($dir);
}

######################################################################################################################################################
# Server
######################################################################################################################################################

if ((!isset($_POST['action']))&&(!isset($_GET['action']))) {
	$action='hello';
} else {
	if (isset($_POST['action'])) {
		$action=$_POST['action'];
	} elseif (isset($_GET['action'])) {
		$action=$_GET['action'];
	}
}

if (!isset($_GET['server_name'])) {
	$server_name='';
} else {
	$server_name=$_GET['server_name'];
}

if (!isset($_SERVER['REMOTE_ADDR'])) {
	$remote_addr='';
} else {
	$remote_addr=$_SERVER['REMOTE_ADDR'];
}

if (!isset($_GET['frame_key'])) {
	$frame_key='';
} else {
	$frame_key=$_GET['frame_key'];
}

if (!isset($_GET['account_email'])) {
	$account_email='';
} else {
	$account_email=$_GET['account_email'];
}

$license=sha1($server_data['server_token'].'#'.$account_email.'#'.$frame_key.'#'.$server_data['server_secure']);

$abs_path=dirname(__FILE__).'/';

switch ($action) {
	# Gibt Infos zum Lizenzkey
	case 'license_server_name' :
	case 'license_server_addr' :
	case 'license_server_key' :
		if ($action=='license_server_name') {
			echo $server_name;
		}
		if ($action=='license_server_addr') {
			echo $remote_addr;
		}
		if ($action=='license_server_key') {
			echo sha1($server_data['server_token'].'#'.$account_email.'#'.$frame_key.'#'.$server_data['server_secure']);
		}
		die();
		break;
	# Gibt das Package der Serverlist zurueck
	case 'get_serverlist' :
		$_GET['package']=$server_data['server_list'];
		$_GET['release']='stable';
		$_GET['version']='0.0';
		$action='get_content';
	# Gibt die Version, den Inhalt und die Checksumme eines Packages zurueck
	case 'get_version' :
	case 'get_content' :
	case 'get_checksum' :
	case 'get_info' :
		if (!isset($_GET['package'])) {
			die('');
		}
		$package=$_GET['package'];
		if (!isset($_GET['release'])) {
			die('');
		}
		$release=$_GET['release'];
		if (!isset($_GET['version'])) {
			die('');
		}
		$version=$_GET['version'];

		$file=$abs_path.'data/'.$package.'/'.$release.'/package.license';
		if (file_exists($file)) {
			$handle=fopen($file, 'r');
			$valid=false;
			while (($buffer=fgets($handle))!==false) {
				if (strpos($buffer, $license)!==false) {
					$valid=true;
					break;
				}
			}
			fclose($handle);
		} else {
			$valid=true;
		}

		if ($valid!==true) {
			die('');
		}

		$file=$abs_path.'data/'.$package.'/'.$release.'/package.version';
		if (file_exists($file)) {
			$_version=file_get_contents($file);
		} else {
			$_version='0.0';
		}

		if ($action=='get_version') {
			logUpdateServer($server_data['server_name'], $package, $release, $_version, $version, 'version');
			die($_version);
		}
		if ($action=='get_content') {
			logUpdateServer($server_data['server_name'], $package, $release, $_version, $version, 'content');
			$file=$abs_path.'data/'.$package.'/'.$release.'/package.content';
			if (file_exists($file)) {
				die(file_get_contents($file));
			}
			die('');
		}
		if ($action=='get_checksum') {
			logUpdateServer($server_data['server_name'], $package, $release, $_version, $version, 'checksum');
			$file=$abs_path.'data/'.$package.'/'.$release.'/package.checksum';
			if (file_exists($file)) {
				die(file_get_contents($file));
			}
			die('');
		}
		if ($action=='get_info') {
			logUpdateServer($server_data['server_name'], $package, $release, $_version, $version, 'info');
			$file=$abs_path.'data/'.$package.'/'.$release.'/package.info';
			if (file_exists($file)) {
				die(file_get_contents($file));
			}
			die('');
		}
		break;
	# Gibt die Checksumme von Package.Release zurueck
	case 'server_check' :
		if (!isset($_POST['package'])) {
			die('');
		}
		$package=$_POST['package'];
		if (!isset($_POST['release'])) {
			die('');
		}
		$release=$_POST['release'];

		$file=$abs_path.'data/'.$package.'/'.$release.'/package.checksum';
		if (file_exists($file)) {
			die(file_get_contents($file));
		}
		die('');
		break;
	# Aktualisiert das Package.Release
	case 'server_update' :
		$error=false;
		if (!isset($_POST['token'])) {
			$token='';
		} else {
			$token=$_POST['token'];
		}
		if (!isset($_POST['part'])) {
			die('');
		}
		if (!isset($_POST['last_part'])) {
			die('');
		}

		$send_package=serialize(unserialize(base64_decode(substr(_mc_decrypt(base64_decode(substr(trim(file_get_contents($_FILES['data']['tmp_name'])), 4, -4)), $server_data['server_secure']), 4, -4))));
		$_token=sha1($send_package.'#'.$server_data['server_token']);

		if ($token!=$_token) {
			die('error (token)');
		}

		$send_package=unserialize($send_package);

		if ($send_package===false) {
			die('error (package)');
		}

		if ($send_package['packer_checksum']!=sha1($send_package['packer_content'])) {
			die('error (checksum)');
		}

		$_dir=$abs_path.'data/'.$send_package['packer_package'].'/'.$send_package['packer_release'].'/';

		$file_packer_checksum=$_dir.'package.checksum.part.'.$_POST['part'];
		$file_packer_version=$_dir.'package.version';
		$file_packer_package=$_dir.'package.content.part.'.$_POST['part'];
		$file_packer_info=$_dir.'package.info';
		$file_packer_parts=$_dir.'package.parts';

		_mkDir($_dir);

		file_put_contents($file_packer_checksum, $send_package['packer_checksum']);
		chmod($file_packer_checksum, 0664);
		file_put_contents($file_packer_version, $send_package['packer_version']);
		chmod($file_packer_version, 0664);
		file_put_contents($file_packer_package, $send_package['packer_content']);
		chmod($file_packer_package, 0664);
		file_put_contents($file_packer_info, $send_package['packer_info']);
		chmod($file_packer_info, 0664);
		file_put_contents($file_packer_parts, $send_package['packer_parts']);
		chmod($file_packer_parts, 0664);

		if ($_POST['last_part']==1) {
			$fp=fopen($_dir.'package.content', 'w+');
			$file='';
			for ($part=1; $part<=$send_package['packer_parts']; $part++) {
				$enc_old=file_get_contents($_dir.'package.content.part.'.$part);
				$enc=substr(base64_decode(substr($enc_old, 4, -4)), 4, -4);
				fwrite($fp, $enc);
			}
			fclose($fp);
			chmod($_dir.'package.content', 0664);
			file_put_contents($_dir.'package.checksum', sha1_file($_dir.'package.content'));
			chmod($_dir.'package.checksum', 0664);
		}
		die('ok');
		break;
	# Gibt die Checksumme vom Server zurueck
	case 'server_checksum' :
		$server_checksum='';
		if (isset($_POST['packages'])) {
			$_packages=[];
			$_dir=$abs_path.'data/';
			if ($handle_package=opendir($_dir)) {
				while (false!==($package=readdir($handle_package))) {
					if (((is_dir($_dir.$package))==true)&&($package!='.')&&($package!='..')) {
						if ($handle_release=opendir($_dir.$package)) {
							while (false!==($release=readdir($handle_release))) {
								if (((is_dir($_dir.$package.'/'.$release))==true)&&($release!='.')&&($release!='..')) {
									$_packages[$package.'-'.$release]=['package'=>$package, 'release'=>$release];
								}
							}
							closedir($handle_release);
						}
					}
				}
				closedir($handle_package);
			}

			$_server_packages=($_POST['packages']);
			$_server_packages=explode(',', $_server_packages);

			foreach ($_server_packages as $_server_package) {
				if (isset($_packages[$_server_package])) {
					unset($_packages[$_server_package]);
				}
				$_server_package=explode('-', $_server_package);
				$_dir=$abs_path.'data/'.$_server_package[0].'/'.$_server_package[1].'/package.checksum';
				if (file_exists($_dir)) {
					#$server_checksum.=$_server_package[0].'#'.$_server_package[1].'#'.sha1(file_get_contents($_dir));
					$server_checksum.=file_get_contents($_dir);
				}
			}
		} else {
			die('___404___');
		}

		foreach ($_packages as $package) {
			$_dir=$abs_path.'data/'.$package['package'].'/'.$package['release'].'/';
			_delDir($_dir);
		}
		die(sha1($server_checksum));
		break;
	# Aktualisiert das Package.Release (Lizenzen)
	case 'server_update_license' :
		$error=false;
		if (!isset($_POST['token'])) {
			$token='';
		} else {
			$token=$_POST['token'];
		}

		$send_package=serialize(unserialize(base64_decode(substr(_mc_decrypt(base64_decode(substr(trim(file_get_contents($_FILES['data']['tmp_name'])), 4, -4)), $server_data['server_secure']), 4, -4))));
		$_token=sha1($send_package.'#'.$server_data['server_token']);

		if ($token!=$_token) {
			die('error (token)');
		}

		$send_package=unserialize($send_package);
		if (isset($send_package['license_data'])) {
			foreach ($send_package['license_data'] as $package=>$license) {
				$package=explode('-', $package);
				$_dir=$abs_path.'data/'.$package[0].'/'.$package[1].'/';

				$file_packer_license=$_dir.'package.license';
				_mkDir($_dir);

				if ($license!=[]) {
					file_put_contents($file_packer_license, implode("\n", $license));
					chmod($file_packer_license, 0664);
				} else {
					unlink($file_packer_license);
				}
			}
		}
		die('ok');
		break;
	# Aktualisiert das Package.Release (Lizenzen)
	case 'server_packages' :
		$_packages=[];
		$_dir=$abs_path.'data/';

		if ($handle_package=opendir($_dir)) {
			while (false!==($package=readdir($handle_package))) {
				if (((is_dir($_dir.$package))==true)&&($package!='.')&&($package!='..')) {
					if ($handle_release=opendir($_dir.$package)) {
						while (false!==($release=readdir($handle_release))) {
							if (((is_dir($_dir.$package.'/'.$release))==true)&&($release!='.')&&($release!='..')) {
								$_file_checksum=$_dir.$package.'/'.$release.'/package.checksum';
								$_file_version=$_dir.$package.'/'.$release.'/package.version';
								$_file_info=$_dir.$package.'/'.$release.'/package.info';

								$file=$_dir.$package.'/'.$release.'/package.license';
								if (file_exists($file)) {
									$handle=fopen($file, 'r');
									$valid=false;
									while (($buffer=fgets($handle))!==false) {
										if (strpos($buffer, $license)!==false) {
											$valid=true;
											break;
										}
									}
									fclose($handle);
								} else {
									$valid=true;
								}

								if ($valid===true) {
									$_packages[$package.'-'.$release]=['package'=>$package, 'release'=>$release, 'checksum'=>file_get_contents($_file_checksum), 'version'=>file_get_contents($_file_version)]+json_decode(file_get_contents($_file_info), true);
									unset($_packages[$package.'-'.$release]['info']['package']);
									unset($_packages[$package.'-'.$release]['info']['version']);
									unset($_packages[$package.'-'.$release]['info']['release']);
								}
							}
						}
						closedir($handle_release);
					}
				}
			}
			closedir($handle_package);
		}
		ksort($_packages);
		die(json_encode($_packages));
		break;
	# Gibt die Version vom Server zurueck
	case 'server_version' :
		die($server_data['server_version']);
		break;
	# Aktualisiert den Server
	case 'server_upgrade' :
		$error=false;
		if (!isset($_POST['token'])) {
			$token='';
		} else {
			$token=$_POST['token'];
		}
		$send_package=base64_decode(substr(_mc_decrypt(base64_decode(substr(file_get_contents($_FILES['data']['tmp_name']), 4, -4)), $server_data['server_secure']), 4, -4));
		copy($abs_path.'index.php', $abs_path.'index-backup.php');
		file_put_contents($abs_path.'index.php', $send_package);
		break;
	# Funktionen zur Ueberpruefung des Servers
	case 'hello' :
	case 'goodbye' :
		die($server_data['server_name']);
		break;
	default :
		die('request not found');
		break;
}

?>