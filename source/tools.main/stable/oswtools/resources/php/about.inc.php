<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

if (!isset($used_software)) {
	$used_software=array();
}

?>

<div class="container">
	<h4>About</h4>

	<div class="row">
	  <div class="col-sm-1"><strong>Tool:</strong></div>
	  <div class="col-sm-11"><?php echo osW_Tool::getInstance()->getToolValue('name')?></div>
	</div>

	<div class="row">
	  <div class="col-sm-1"><strong>Author:</strong></div>
	  <div class="col-sm-11"><?php echo osW_Tool::getInstance()->getToolValue('author')?></div>
	</div>

	<div class="row">
	  <div class="col-sm-1"><strong>Copyright:</strong></div>
	  <div class="col-sm-11"><?php echo osW_Tool::getInstance()->getToolValue('copyright')?></div>
	</div>

	<div class="row">
	  <div class="col-sm-1"><strong>Version:</strong></div>
	  <div class="col-sm-11"><?php echo osW_Tool::getInstance()->getToolValue('version')?><?php if(osW_Tool::getInstance()->checkUpdate(serverlist)==true):?> (<?php echo osW_Tool::getInstance()->getUpdateVersion(serverlist)?> available, <a href="index.php?action=update&session=<?php echo osW_Tool_Session::getInstance()->getId()?>">update</a>)<?php endif?></div>
	</div>

	<div class="row">
	  <div class="col-sm-1"><strong>Release:</strong></div>
	  <div class="col-sm-11"><?php echo osW_Tool::getInstance()->getToolValue('release')?></div>
	</div>

	<div class="row">
	  <div class="col-sm-1"><strong>Link:</strong></div>
	  <div class="col-sm-11"><?php echo osW_Tool::getInstance()->getToolValue('link')?></div>
	</div>

	<hr/>

	<h4>License</h4>
	<div class="panel panel-default panel-license">
		<div class="panel-body"><?php echo osW_Tool::getInstance()->getLicense(osW_Tool::getInstance()->getToolValue('license'))?></div>
	</div>

	<hr/>

	<h4>Used software</h4>
	<div class="used-software">
		<a target="_blank" href="https://jquery.com"><img alt="jquery" title="jQuery: The Write Less, Do More, JavaScript Library." src="../resources/img/logos/jquery.svg"></a>
		<a target="_blank" href="http://getbootstrap.com"><img alt="bootstrap" title="Bootstrap · The world's most popular mobile-first and responsive front-end framework." src="../resources/img/logos/bootstrap.svg"></a>
		<?php if ($used_software!=[]):?>
		<?php foreach ($used_software as $software):?>
		<?php echo $software?>
		<?php endforeach?>
		<?php endif?>
	</div>

	<br/>

	<ul class="list-unstyled">
		<li><a target="_blank" href="http://fontawesome.io">Font Awesome</a>: the iconic font and CSS toolkit</li>
		<li><a target="_blank" href="https://datatables.net">DataTables</a>: Table plug-in for jQuery/Bootstrap</li>
		<li><a target="_blank" href="http://bootboxjs.com">Bootbox.js</a>: Bootstrap modals made easy</li>
		<li><a target="_blank" href="https://silviomoreto.github.io/bootstrap-select/">bootstrap-select</a>: Bootstrap-select is a jQuery plugin that utilizes Bootstrap's dropdown.js to style and bring additional functionality to standard select elements</li>
	</ul>

</div>