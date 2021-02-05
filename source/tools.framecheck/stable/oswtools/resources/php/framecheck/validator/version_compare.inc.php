<?php

if ((version_compare($data['getvalue'], $data['value'], '>='))&&($data['check_operator']=='>=')) {
	$data['score']=0;
} else {
	if ($data['required']===true) {
		$data['score']=10;
	} else {
		$config[$id]['score']=5;
	}
}