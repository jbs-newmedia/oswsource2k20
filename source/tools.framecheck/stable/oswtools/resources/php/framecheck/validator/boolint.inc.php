<?php

if ($data['check_operator']=='=') {
	if ($data['getvalue']===$data['value']) {
		$data['score']=0;
	} else {
		if ($data['required']===true) {
			$data['score']=10;
		} else {
			$data['score']=5;
		}
	}
}

if ($data['check_operator']=='>') {
	if ($data['getvalue']>$data['value']) {
		$data['score']=0;
	} else {
		if ($data['required']===true) {
			$data['score']=10;
		} else {
			$data['score']=5;
		}
	}
}

if ($data['check_operator']=='<') {
	if ($data['getvalue']<$data['value']) {
		$data['score']=0;
	} else {
		if ($data['required']===true) {
			$data['score']=10;
		} else {
			$data['score']=5;
		}
	}
}

?>