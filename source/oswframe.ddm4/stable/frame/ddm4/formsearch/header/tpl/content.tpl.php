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

<div class="form-group<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> has-error<?php endif ?> ddm_element_<?php echo $this->getSearchElementValue($element, 'id') ?>">
	<h4><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementValue($element, 'title')) ?></h4>
</div>