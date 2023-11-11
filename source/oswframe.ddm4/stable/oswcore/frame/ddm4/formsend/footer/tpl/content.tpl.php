<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var string $element
 * @var \osWFrame\Core\DDM4 $this
 *
 */

use osWFrame\Core\HTML;

?>

<div
    class="form-group<?php if ($this->getTemplate()->Form()->getErrorMessage(
        $element
    )
    ): ?> has-error<?php endif ?> ddm_element_<?php echo $this->getSendElementValue($element, 'id') ?>">
    <h4><?php echo HTML::outputString($this->getSendElementValue($element, 'title')) ?></h4>
</div>
