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

?>

<th class="ddm_element_<?php echo $this->getListElementValue($element, 'id') ?>">
	<?php echo \osWFrame\Core\HTML::outputString($this->getListElementValue($element, 'title')) ?>
</th>