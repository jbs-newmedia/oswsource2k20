<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

function print_a($var): void
{
    ob_start();
    var_dump($var);
    $content = ob_get_contents();
    ob_end_clean();
    echo '<pre style="border:2px solid red; padding:3px;"><strong>print_a</strong><br/>' . $content . '</pre>';
}
