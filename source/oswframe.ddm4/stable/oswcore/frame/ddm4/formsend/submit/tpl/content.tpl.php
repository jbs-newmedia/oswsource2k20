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


?>

<div class="form-group ddm_element_<?php echo $this->getSendElementValue($element, 'id') ?>">

    <?php /* input */ ?>

    <?php echo $this->getTemplate()->Form()->drawSubmit('btn_ddm_submit', $this->getGroupMessage('form_send'), [
        'input_class' => 'btn btn-primary',
    ]) ?>
    &nbsp;
    <?php echo $this->getTemplate()->Form()->drawReset('btn_ddm_reset', $this->getGroupMessage('form_reset'), [
        'input_class' => 'btn btn-secondary',
    ]) ?>

</div>
