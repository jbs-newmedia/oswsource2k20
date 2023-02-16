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

?><?php

$_links=[];

if (is_array($this->getListElementOption($element, 'links_before'))) {
	foreach ($this->getListElementOption($element, 'links_before') as $__link) {
		if (((isset($__link['modal']))&&($__link['modal']==true))||((isset($__link['target']))&&($__link['target']=='modal'))) {
			$_links[]='<a onclick="openDDM4Modal_'.$this->getName().'(this, \''.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'\', \''.((($__link['type']))?$__link['type']:'modal').'\', 12)" title="'.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'" class="btn btn-primary btn-xs" pageName="'.$this->getTemplate()->buildhrefLink(((isset($__link['module']))?$__link['module']:$this->getDirectModule()), ((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].((($__link['parameter']))?'&'.$__link['parameter']:'').'&'.((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].'&modal=1&'.$this->getDirectParameters()).'">'.((($__link['content']))?$__link['content']:'<i class="fa fa-fw"></i>').'</a>';
		} elseif (((isset($__link['notify']))&&($__link['notify']==true))||((isset($__link['target']))&&($__link['target']=='notify'))) {
			$_links[]='<a onclick="openDDM4Notify_'.$this->getName().'(this)" title="'.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'" class="btn btn-primary btn-xs" pageName="'.$this->getTemplate()->buildhrefLink(((isset($__link['module']))?$__link['module']:$this->getDirectModule()), ((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].((($__link['parameter']))?'&'.$__link['parameter']:'').'&'.((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].'&'.$this->getDirectParameters()).'">'.((($__link['content']))?$__link['content']:'<i class="fa fa-fw"></i>').'</a>';
		} else {
			$_links[]='<a class="btn btn-primary btn-xs"'.((isset($__link['target']))?' target="'.$__link['target'].'"':'').' href="'.$this->getTemplate()->buildhrefLink(((isset($__link['module']))?$__link['module']:$this->getDirectModule()), ((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].((($__link['parameter']))?'&'.$__link['parameter']:'')).'" title="'.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'">'.((($__link['content']))?$__link['content']:'<i class="fa fa-fw"></i>').'</a>';
		}
	}
}

if (($this->getCounter('edit_elements')>0)&&($this->getListElementOption($element, 'disable_edit')!==true)) {
	$_links[]='<a onclick="openDDM4Modal_'.$this->getName().'(this, \''.$this->getGroupOption('edit_title', 'messages').'\', \'edit\', '.$this->getCounter('edit_elements').')" title="'.\osWFrame\Core\HTML::outputString($this->getGroupMessage('data_edit')).'" class="btn btn-primary btn-xs" pageName="'.$this->getTemplate()->buildhrefLink($this->getDirectModule(), 'action=edit&'.((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].'&modal=1&'.$this->getDirectParameters()).'"><i class="fas fa-pencil-alt fa-fw"></i></a>';
}

if (($this->getCounter('delete_elements')>0)&&($this->getListElementOption($element, 'disable_delete')!==true)) {
	$_links[]='<a onclick="openDDM4Modal_'.$this->getName().'(this, \''.$this->getGroupOption('delete_title', 'messages').'\', \'delete\', '.$this->getCounter('delete_elements').')" title="'.\osWFrame\Core\HTML::outputString($this->getGroupMessage('data_delete')).'" class="btn btn-primary btn-xs" pageName="'.$this->getTemplate()->buildhrefLink($this->getDirectModule(), 'action=delete&'.((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].'&modal=1&'.$this->getDirectParameters()).'"><i class="fa fa-trash-alt fa-fw"></i></a>';
}

if (($this->getGroupOption('enable_log')===true)&&($this->getListElementOption($element, 'disable_log')!==true)) {
	$_links[]='<a onclick="openDDM4Modal_'.$this->getName().'(this, \''.$this->getGroupOption('log_title', 'messages').'\', \'log\', '.$this->getCounter('delete_elements').')" title="'.\osWFrame\Core\HTML::outputString($this->getGroupMessage('data_log')).'" class="btn btn-primary btn-xs" pageName="'.$this->getTemplate()->buildhrefLink($this->getDirectModule(), 'action=log&'.((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].'&modal=1&'.$this->getDirectParameters()).'"><i class="fa fa-book fa-fw"></i></a>';
}

if (is_array($this->getListElementOption($element, 'links'))) {
	foreach ($this->getListElementOption($element, 'links') as $__link) {
		if (((isset($__link['modal']))&&($__link['modal']==true))||((isset($__link['target']))&&($__link['target']=='modal'))) {
			$_links[]='<a onclick="openDDM4Modal_'.$this->getName().'(this, \''.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'\', \''.((($__link['type']))?$__link['type']:'modal').'\', 12)" title="'.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'" class="btn btn-primary btn-xs" pageName="'.$this->getTemplate()->buildhrefLink(((isset($__link['module']))?$__link['module']:$this->getDirectModule()), ((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].((($__link['parameter']))?'&'.$__link['parameter']:'').'&'.((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].'&modal=1&'.$this->getDirectParameters()).'">'.((($__link['content']))?$__link['content']:'<i class="fa fa-fw"></i>').'</a>';
		} elseif (((isset($__link['notify']))&&($__link['notify']==true))||((isset($__link['target']))&&($__link['target']=='notify'))) {
			$_links[]='<a onclick="openDDM4Notify_'.$this->getName().'(this)" title="'.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'" class="btn btn-primary btn-xs" pageName="'.$this->getTemplate()->buildhrefLink(((isset($__link['module']))?$__link['module']:$this->getDirectModule()), ((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].((($__link['parameter']))?'&'.$__link['parameter']:'').'&'.((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].'&'.$this->getDirectParameters()).'">'.((($__link['content']))?$__link['content']:'<i class="fa fa-fw"></i>').'</a>';
		} else {

			$_links[]='<a class="btn btn-primary btn-xs"'.((isset($__link['target']))?' target="'.$__link['target'].'"':'').' href="'.$this->getTemplate()->buildhrefLink(((isset($__link['module']))?$__link['module']:$this->getDirectModule()), ((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].((($__link['parameter']))?'&'.$__link['parameter']:'')).'" title="'.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'">'.((($__link['content']))?$__link['content']:'<i class="fa fa-fw"></i>').'</a>';
		}
	}
}

if (is_array($this->getListElementOption($element, 'links_after'))) {
	foreach ($this->getListElementOption($element, 'links_after') as $__link) {
		if (((isset($__link['modal']))&&($__link['modal']==true))||((isset($__link['target']))&&($__link['target']=='modal'))) {
			$_links[]='<a onclick="openDDM4Modal_'.$this->getName().'(this, \''.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'\', \''.((($__link['type']))?$__link['type']:'modal').'\', 12)" title="'.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'" class="btn btn-primary btn-xs" pageName="'.$this->getTemplate()->buildhrefLink(((isset($__link['module']))?$__link['module']:$this->getDirectModule()), ((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].((($__link['parameter']))?'&'.$__link['parameter']:'').'&'.((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].'&modal=1&'.$this->getDirectParameters()).'">'.((($__link['content']))?$__link['content']:'<i class="fa fa-fw"></i>').'</a>';
		} elseif (((isset($__link['notify']))&&($__link['notify']==true))||((isset($__link['target']))&&($__link['target']=='notify'))) {
			$_links[]='<a onclick="openDDM4Notify_'.$this->getName().'(this)" title="'.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'" class="btn btn-primary btn-xs" pageName="'.$this->getTemplate()->buildhrefLink(((isset($__link['module']))?$__link['module']:$this->getDirectModule()), ((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].((($__link['parameter']))?'&'.$__link['parameter']:'').'&'.((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].'&'.$this->getDirectParameters()).'">'.((($__link['content']))?$__link['content']:'<i class="fa fa-fw"></i>').'</a>';
		} else {
			$_links[]='<a class="btn btn-primary btn-xs" '.((isset($__link['target']))?' target="'.$__link['target'].'"':'').' href="'.$this->getTemplate()->buildhrefLink(((isset($__link['module']))?$__link['module']:$this->getDirectModule()), ((isset($__link['index']))?osWFrame\Core\HTML::outputString($__link['index']):$this->getGroupOption('index', 'database')).'='.$view_data[$this->getGroupOption('index', 'database')].((($__link['parameter']))?'&'.$__link['parameter']:'')).'" title="'.((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined').'">'.((($__link['content']))?$__link['content']:'<i class="fa fa-fw"></i>').'</a>';
		}
	}
}

$view_data[$element]='<div style="text-align:center; white-space:nowrap;">'.implode(' ', $_links).'</div>';

?>