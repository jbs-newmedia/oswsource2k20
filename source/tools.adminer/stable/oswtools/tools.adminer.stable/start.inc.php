<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

/*
 * TOOL - Start
 */



?>
<script>
$(function() {
	$("#info").height(($(document).height()-140)+'px');
});
</script>

<iframe src="adminer.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>" id="info" class="iframe" name="info" seamless="" width="100%" height="100%" frameBorder="0"></iframe>
