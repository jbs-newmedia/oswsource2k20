<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link http://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

/*
 * TOOL - Start
 */

if (isset($_GET['export'])) {
	$export=$_GET['export'];
} else {
	$export='';
}

if ($export!='') {
	switch ($export) {
		case 'csv':
		default:
			$export='csv';
			break;
	}
}

$_dir=root_path.osW_Tool::getInstance()->getFrameConfig('debug_path', 'string');
$date='d.m.Y';
$time='H:i';
$fulldate='d.m.Y, H:i:s';

$data=array();
$data['dir']='osw_errorhandler';
$data['dir_path']='osw_errorhandler/';

if (isset($_GET['date'])) {
	$data['date']=$_GET['date'];
} else {
	$_GET['date']='';
	$data['date']='';
}

if (!isset($_GET['file'])) {
	$_GET['file']='';
}

$path=realpath($_dir.$data['dir_path'].$_GET['file']);
if ($path!==false) {
	if (strpos(realpath($_dir.$data['dir_path'].$_GET['file']), realpath($_dir))!==0) {
		die('Illegal path');
	}
}

$path=realpath($_dir.$data['dir_path']);
if ($path!==false) {
	if (strpos(realpath($_dir.$data['dir_path']), realpath($_dir))!==0) {
		die('Illegal path');
	}
}

$path=realpath($_dir);
if ($path!==false) {
	if (strpos(realpath($_dir), realpath($_dir))!==0) {
		die('Illegal path');
	}
}

$data['files']=array();

if (($data['dir_path']!='')&&(is_dir($_dir.$data['dir_path']))) {
	if ($handle = opendir($_dir.$data['dir_path'])) {
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				if ((is_file($_dir.$data['dir_path'].'/'.$file))==true) {
					$datev=substr($file, 0, 8);
					if (!@checkdate(substr($datev, 4, 2), substr($datev, 6, 2), substr($datev, 0, 4))) {
						$datev=date('Ymd');
					}
					if (!isset($data['files'][$datev])) {
						$data['files'][$datev]=array();
					}
					$data['files'][$datev][]=$file;
				}
			}
		}
		closedir($handle);
	}

	if ($handle = opendir($_dir.$data['dir_path'])) {
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				if ((is_file($_dir.$data['dir_path'].'/'.$file))==true) {
					$datev=substr($file, 0, 8);
					if (!@checkdate(substr($datev, 4, 2), substr($datev, 6, 2), substr($datev, 0, 4))) {
						$datev=date('Ymd');
					}
					if (!isset($data['files'][$datev])) {
						$data['files'][$datev]=array();
					}
					$data['files'][$datev][]=$file;
					$data['dates'][$datev]=$datev;
				}
			}
		}
		closedir($handle);
	}
}

if (isset($data['dates'])) {
	krsort($data['dates']);
}

function tool_sort($a, $b) {
	global $sort;
	if ($sort=='count') {
		return $a[1]<$b[1];
	}
	if ($sort=='errstr') {
		return $a[2]<$b[2];
	}
	if ($sort=='errfile') {
		return $a[3]<$b[3];
	}
	if ($sort=='errline') {
		return $a[4]>$b[4];
	}
	return 0;
}

$sort='';
if (isset($_GET['sort'])) {
	$sort=$_GET['sort'];
}

switch ($sort) {
	case 'count':
	case 'errstr':
	case 'errfile':
	case 'errline':
	case 'work':
		break;
	default:
		$sort='count';
		break;
}

if (($data['date']!='')&&(isset($data['dates'][$data['date']]))) {
	$results=array();
	foreach ($data['files'][$data['date']] as $file) {
		$_file=$_dir.$data['dir_path'].$file;
		if (($handle=fopen($_file, 'r'))!==false) {
			$i=0;
			while (($csv=fgetcsv($handle, 1000, ';'))!==false) {
				if ($i==0) {
					$i++;
				} else {
					if (isset($csv[0])) {
						unset($csv[0]);
					}
					if (isset($csv[1])) {
						unset($csv[1]);
					}

					$md5=md5(serialize($csv));
					if ($sort=='work') {
						$file_md5=md5($csv[3]);
						if (!isset($results[$file_md5][$md5])) {
							$results[$file_md5][$md5]=$csv;
							$results[$file_md5][$md5][1]=0;
						}
						$results[$file_md5][$md5][1]++;
					} else {
						if (!isset($results[$md5])) {
							$results[$md5]=$csv;
							$results[$md5][1]=0;
						}
						$results[$md5][1]++;
					}
					$i++;
				}
			}
			fclose($handle);
		}

		if ($sort=='work') {
			$sort='errline';
			foreach ($results as $file => $content) {
				uasort($results[$file], "tool_sort");
			}
			$sort='work';
		} else {
			usort($results, "tool_sort");
		}
	}

}

if ($export=='csv') {
	if (($data['date']!='')&&(isset($data['dates'][$data['date']]))) {
		ob_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".package."_".date('YmdHis').".csv\";");
		header("Content-Transfer-Encoding: binary");

		echo '"errfile";"count";"errline";"errstr"'."\n";

		foreach ($results as $result) {
			echo '"'.$result[3].'";"'.$result[1].'";"'.$result[4].'";"'.$result[2].'"'."\n";
		}

		die();
	}
}

?>

<div class="container-fluid">

	<div class="row">
		<div class="col-xs-12 col-lg-2">
			<div class="panel panel-default">
				<div class="panel-heading">Days</div>
				<div class="panel-body">

<?php if(isset($data['dates'])):?>
<?php foreach($data['dates'] as $datekey => $datevalue):?>
<?php if($datekey==$data['date']):?>
<?php echo date($date, mktime(12, 0, 0, substr($datekey, 4, 2), substr($datekey, 6, 2), substr($datekey, 0, 4)))?><br/>
<?php else:?>
<a href="?dir=<?php echo $data['dir']?>&amp;date=<?php echo $datevalue?>"><?php echo date($date, mktime(12, 0, 0, substr($datekey, 4, 2), substr($datekey, 6, 2), substr($datekey, 0, 4)))?></a><br/>
<?php endif?>
<?php endforeach?>
<?php else:?>
- empty -
<?php endif?>
				</div>
			</div>
		</div>

		<div class="col-xs-12 col-lg-10">

<?php

if (($data['date']!='')&&(isset($data['dates'][$data['date']]))) {
	if ($results!=array()) {
		echo '<div class="table-responsive">';
		echo '<table class="table table-bordered table-striped">';
		echo '<tr>';
		echo '<th>#</th>';
		if ($sort=='errfile') {
			echo '<th>errfile</th>';
		} else {
			echo '<th><a href="'.$_SERVER['PHP_SELF'].'?sort=errfile&amp;date='.$data['date'].'">errfile</a> (<a href="'.$_SERVER['PHP_SELF'].'?sort=work&amp;date='.$data['date'].'">work</a>)</th>';
		}
		if ($sort=='count') {
			echo '<th>count</th>';
		} else {
			echo '<th><a href="'.$_SERVER['PHP_SELF'].'?sort=count&amp;date='.$data['date'].'">count</a></th>';
		}
		if ($sort=='errline') {
			echo '<th>errline</th>';
		} else {
			echo '<th><a href="'.$_SERVER['PHP_SELF'].'?sort=errline&amp;date='.$data['date'].'">errline</a></th>';
		}
		if ($sort=='errstr') {
			echo '<th>errstr</th>';
		} else {
			echo '<th><a href="'.$_SERVER['PHP_SELF'].'?sort=errstr&amp;date='.$data['date'].'">errstr</a></th>';
		}
		echo '</tr>';
		if ($sort=='work') {

			$i=0;
			foreach ($results as $file => $_result) {
				$cfile='';
				foreach ($_result as $result) {
					$i++;
					echo '<tr id="i'.$file.'">';
					echo '<td>'.$i.'</td>';
					if ($cfile!=$result[3]) {
						$cfile=$result[3];
						echo '<td rowspan="'.count($_result).'">'.$result[3].' (<a href="'.$_SERVER['PHP_SELF'].'?sort='.$sort.'&amp;date='.$data['date'].'#i'.$file.'">Link</a>)</td>';
					}
					echo '<td>'.$result[1].'</td>';
					echo '<td>'.$result[4].'</td>';
					echo '<td>'.$result[2].'</td>';
					echo '</tr>';
				}
			}
		} else {
			$i=0;
			foreach ($results as $result) {
				$i++;
				echo '<tr id="i'.$i.'">';
				echo '<td>'.$i.'</td>';
				echo '<td>'.$result[3].'</td>';
				echo '<td>'.$result[1].'</td>';
				echo '<td>'.$result[4].'</td>';
				echo '<td>'.$result[2].'</td>';
				echo '</tr>';
			}
			echo '</table>';
			echo '<hr/>';
			echo '<a class="btn btn-primary btn-block" target="_blank" href="'.$_SERVER['PHP_SELF'].'?export=csv&amp;date='.$data['date'].'">Export results</a>';
			echo '</div>';
		}
	} else {
		echo '&nbsp;';
	}
} else {
	echo '&nbsp;';
}

?>

		</div>
	</div>
</div>