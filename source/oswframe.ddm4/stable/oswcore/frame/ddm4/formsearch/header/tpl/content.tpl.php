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

<h4 class="form-group bg-primary text-white ddm4_element_header px-2 py-2 ddm_element_<?php echo $this->getSearchElementValue(
    $element,
    'id'
) ?>"><?php echo HTML::outputString($this->getSearchElementValue($element, 'title')) ?></h4>
