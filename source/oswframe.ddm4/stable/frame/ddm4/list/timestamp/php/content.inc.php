<?php

if ((isset($options['name']))&&($options['name']!='')) {
	$_columns[$options['name']]=['name'=>$options['name'], 'order'=>(isset($_order[$options['name']]))?true:false, 'search'=>(isset($_search[$options['name']]))?true:false,];
}

$this->incCounter('list_view_elements');

?>