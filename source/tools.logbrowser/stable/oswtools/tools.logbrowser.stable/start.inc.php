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

$_dir=root_path.osW_Tool::getInstance()->getFrameConfig('debug_path', 'string');
$last_days=osW_Tool::getInstance()->getFrameConfig('debug_maxdays', 'string');
$date='d.m.Y';
$time='H:i';
$fulldate='d.m.Y, H:i:s';

$data=array();

if (isset($_GET['dir'])) {
	$data['dir']=$_GET['dir'];
	$data['dir_path']=$_GET['dir'].'/';
} else {
	$data['dir']='';
	$data['dir_path']='';
}

if (isset($_GET['file'])) {
	$data['file']=$_GET['file'];
} else {
	$_GET['file']='';
	$data['file']='';
}

if (strpos(realpath($_dir.$data['dir_path'].$_GET['file']), realpath($_dir))!==0) {
	die('Illegal path');
}

if ($handle = opendir($_dir)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != '.' && $file != '..') {
			if ((is_dir($_dir.$file))==true) {
				$data['dirs'][]=$file;
			}
		}
	}
	closedir($handle);
}

if ($data['dir_path']!='') {
	if ($handle = opendir($_dir.$data['dir_path'])) {
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				if ((is_file($_dir.$data['dir_path'].'/'.$file))==true) {
					$datev=substr($file, 0, 8);
					if (!@checkdate(substr($datev, 4, 2), substr($datev, 6, 2), substr($datev, 0, 4))) {
						$datev=date('Ymd');
					}
					if ($datev>date('Ymd', (time()-(60*60*24*$last_days)))) {
						if (!isset($data['files'][$datev])) {
							$data['files'][$datev]=array();
						}
						$data['files'][$datev][]=$file;
					}
				}
			}
		}
		closedir($handle);
	}
}

if (isset($data['dirs'])) {
	sort($data['dirs']);
}

if (isset($data['files'])) {
	krsort($data['files']);
	foreach ($data['files'] as $key => $values) {
		asort($data['files'][$key]);
	}
}

?>

<div class="container-fluid">

	<div class="row">
		<div class="col-xs-12 col-lg-2">
<?php if(isset($data['dirs'])):?>
			<div class="panel panel-default">
				<div class="panel-heading">Classes</div>
				<div class="panel-body">
<?php foreach($data['dirs'] as $dir):?>
<?php if($dir==$data['dir']):?>
				<?php echo $dir?><br/>
<?php else:?>
  				<a href="?dir=<?php echo $dir?>"><?php echo $dir?></a><br/>
<?php endif?>
<?php endforeach?>
				</div>
			</div>
<?php endif?>

<?php if(isset($data['files'])):?>
			<div class="panel panel-default">
				<div class="panel-heading">Files</div>
				<div class="panel-body">
<?php foreach($data['files'] as $datekey => $datevalue):?>
<strong><?php echo date($date, mktime(12, 0, 0, substr($datekey, 4, 2), substr($datekey, 6, 2), substr($datekey, 0, 4)))?>:</strong><br/>
<?php foreach($datevalue as $file):?>

<?php if($file==$data['file']):?>
<?php if(!@checkdate(substr($file, 4, 2), substr($file, 6, 2), substr($file, 0, 4))):?>
<?php echo substr($file, 0)?><br/>
<?php else:?>
<?php echo substr($file, 9)?><br/>
<?php endif?>
<?php else:?>
<?php if(!@checkdate(substr($file, 4, 2), substr($file, 6, 2), substr($file, 0, 4))):?>
<a href="?dir=<?php echo $data['dir']?>&amp;file=<?php echo $file?>"><?php echo substr($file, 0)?></a><br/>
<?php else:?>
<a href="?dir=<?php echo $data['dir']?>&amp;file=<?php echo $file?>"><?php echo substr($file, 9)?></a><br/>
<?php endif?>
<?php endif?>
<?php endforeach?>
<?php endforeach?>
<?php endif?>
				</div>
			</div>
		</div>

		<div class="col-xs-12 col-lg-10">

<?php
if ($data['file']!='') {
	if (substr($_dir.$data['dir_path'].$data['file'], -3)=='csv') {
		$lines=file($_dir.$data['dir_path'].$data['file']);

		if (count($lines)>0) {
			$lines_head=$lines[0];
			unset($lines[0]);
			$lines=array_reverse($lines);
			echo '<div class="table-responsive">';
			echo '<table class="table table-bordered table-striped">';
			$line=trim($lines_head);
			$line=substr($line, 1, strlen($line)-2);
			$lines_head=explode('";"', $line);
			echo '<tr>';
			foreach ($lines_head as $line) {
				echo '<th>'.$line.'</th>';
			}
			echo '</tr>';

			foreach ($lines as $key => $line) {
				$line=trim($line);
				$line=substr($line, 1, strlen($line)-2);
				$lines_content=explode('";"', $line);
				echo '<tr>';
				foreach ($lines_content as $key => $line) {
					if ($line=='') {
						$line='&nbsp;';
					}
					if ($lines_head[$key]=='time') {
						echo '<td>'.date($fulldate, intval($line)).'</td>';
					} else {
						echo '<td>'.str_replace('#oswbr#', '<br/>', $line).'</td>';
					}
				}
				echo '</tr>';
			}
			echo '</table>';
			echo '</div>';
		} else {
			echo '&nbsp;';
		}
	} else {
		if (file_exists($_dir.$data['dir_path'].$data['file'])) {
			echo nl2br(file_get_contents($_dir.$data['dir_path'].$data['file']));
		} else {
			echo '&nbsp;';
		}
	}
} else {
	echo '&nbsp;';
}
?>
		</div>
	</div>
</div>