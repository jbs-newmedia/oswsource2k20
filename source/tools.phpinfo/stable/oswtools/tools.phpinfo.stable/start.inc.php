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

ob_start();
phpinfo();
$phpinfo = ob_get_clean();

# Body-Content rausholen
$phpinfo = preg_replace('#^.*<body>(.*)</body>.*$#s', '$1', $phpinfo);
# HTML5-Fehler korrigieren
$phpinfo = str_replace('module_Zend Optimizer', 'module_Zend_Optimizer', $phpinfo);
$phpinfo = str_replace('module_Zend OPcache', 'module_Zend_OPcache', $phpinfo);
$phpinfo = str_replace('<a name="', '<a id="', $phpinfo);
$phpinfo = str_replace('<img border="0"', '<img', $phpinfo);
# <font> durch <span> ersetzen
$phpinfo = str_replace('<font', '<span', $phpinfo);
$phpinfo = str_replace('</font>', '</span>', $phpinfo);
#Table
$phpinfo = str_replace('<table>', '<table class="table table-bordered table-striped" style="table-layout: fixed;word-wrap: break-word;">', $phpinfo );
#$phpinfo = str_replace('<tr class="h"><th', '<thead><tr><th', $phpinfo);
#$phpinfo = str_replace('</th></tr>', '</th></tr></thead><tbody>', $phpinfo);
#$phpinfo = str_replace('</table>', '</tbody></table>', $phpinfo);
# Schlüsselwörter grün oder rot einfärben
$phpinfo = preg_replace('#>(on|enabled|active)#i', '><span class="text-success">$1</span>', $phpinfo);
$phpinfo = preg_replace('#>(off|disabled)#i', '><span class="text-danger">$1</span>', $phpinfo);

?>

<div class="container">

	<?php echo $phpinfo?>

</div>