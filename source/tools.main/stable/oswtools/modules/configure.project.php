<?php

# osWFrame configure block begin #

$url=parse_url($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);

if ($url['scheme']=='https') {
	osW_setVar('settings_ssl', true);
} else {
	osW_setVar('settings_ssl', false);
}

$host=explode('.', $url['host']);
if (count($host)==1) {
	osW_setVar('project_subdomain', '');
	osW_setVar('project_domain', $host[0]);
} elseif (count($host)==2) {
	osW_setVar('project_subdomain', '');
	osW_setVar('project_domain', $host[0].'.'.$host[1]);
} elseif (count($host)==3) {
	osW_setVar('project_subdomain', $host[0]);
	osW_setVar('project_domain', $host[1].'.'.$host[2]);
} elseif (count($host)==4) {
	osW_setVar('project_subdomain', $host[0].'.'.$host[1]);
	osW_setVar('project_domain', $host[2].'.'.$host[3]);
}

$path=explode('/', $url['path']);
if ($path[1]=='oswtools') {
	osW_setVar('project_path', 'oswtools');
} elseif ($path[2]=='oswtools') {
	osW_setVar('project_path', $path[1].'/oswtools');
} elseif ($path[3]=='oswtools') {
	osW_setVar('project_path', $path[1].'/'.$path[2].'/oswtools');
}

osW_setVar('project_name', 'osWTools');
osW_setVar('project_default_module', 'tools.main.stable');
osW_setVar('project_default_language', 'en_US');
osW_setVar('project_locale', 'en_US.utf8');
osW_setVar('project_timezone', 'Europe/Paris');
osW_setVar('oswtools_default_tool', 'tools.main.stable');

# osWFrame configure block end #

?>