<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

?>

<?php

$view=false;

if (($this->getCounter('edit_elements')>0)&&($this->getListElementOption($element, 'disable_edit')!==true)) {
	$view=true;
}

if (($this->getCounter('delete_elements')>0)&&($this->getListElementOption($element, 'disable_delete')!==true)) {
	$view=true;
}

if (is_array($this->getListElementOption($element, 'links'))) {
	$view=true;
}
?>

<?php if ($view===true): ?>
	<th class="ddm_element_<?php echo $this->getListElementValue($element, 'id') ?>">
		<div style="text-align:center;"><?php echo \osWFrame\Core\HTML::outputString($this->getListElementValue($element, 'title')) ?></div>
	</th>
<?php endif ?>