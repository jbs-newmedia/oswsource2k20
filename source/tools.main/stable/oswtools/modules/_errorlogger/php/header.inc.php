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

\osWFrame\Core\Settings::setStringVar('frame_default_engine', 'errorlogger');
\osWFrame\Core\Settings::setStringVar('frame_default_output', 'errorlogger');
\osWFrame\Core\Settings::setBoolVar('session_enabled', false);

?>