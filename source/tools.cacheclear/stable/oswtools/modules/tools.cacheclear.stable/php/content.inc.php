<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 *
 * @var \osWFrame\Core\Template $osW_Template
 */

$Tool = new \osWFrame\Tools\Tool\CacheClear('oswframe2k20', 'tools.cacheclear', 'stable');
if (\osWFrame\Core\Settings::getAction() === 'noupdate') {
    $Tool->blockUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}
if (\osWFrame\Core\Settings::getAction() === 'update') {
    $Tool->installUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}
if ($Tool->hasUpdate() === true) {
    $osW_Template->addJSCodeHead($Tool->getUpdateConfirm($osW_Template->buildhrefLink('current', 'action=update'), $osW_Template->buildhrefLink('current', 'action=noupdate')));
}

$Tool->addNavigationElement('start', ['action' => 'start', 'title' => 'Start', 'icon' => 'fa fa-home fa-fw']);
$Tool->addNavigationElement('more', ['title' => 'More', 'icon' => 'fas fa-cog fa-fw']);
$Tool->addNavigationElement('changelog', ['action' => 'changelog', 'title' => 'Changelog', 'icon' => 'fas fa-list fa-fw'], 'more');
$Tool->addNavigationElement('about', ['action' => 'about', 'title' => 'About', 'icon' => 'fas fa-info fa-fw'], 'more');
\osWFrame\Core\Settings::setAction($Tool->validateAction(\osWFrame\Core\Settings::getAction()));
$Tool->checkNavigation(\osWFrame\Core\Settings::getAction());

if (in_array(\osWFrame\Core\Settings::getAction(), ['about'], true)) {
    include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'about.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'], true)) {
    include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'changelog.inc.php';
} else {
    $jsfiles = ['resources' . \DIRECTORY_SEPARATOR . 'js' . \DIRECTORY_SEPARATOR . 'tools.cacheclear.js'];
    $osW_Template->addTemplateJSFiles('head', $jsfiles);

    $Tool->readCacheList(\osWFrame\Core\Settings::getStringVar('settings_framepath') . \osWFrame\Tools\Configure::getFrameConfigString('cache_path'));

    if (\osWFrame\Tools\Helper::getDoAction() === 'doclear') {
        $i = 0;

        $cache_dirs = $Tool->getCacheList();
        if (isset($_POST['dir'])) {
            foreach ($_POST['dir'] as $dir => $status) {
                if (($status === 1) && (in_array($dir, $cache_dirs, true))) {
                    \osWFrame\Core\Filesystem::delDir(\osWFrame\Core\Settings::getStringVar('settings_framepath') . \osWFrame\Tools\Configure::getFrameConfigString('cache_path') . $dir);
                    $i++;
                }
            }
        }
        if ($i === 0) {
            \osWFrame\Core\MessageStack::addMessage('result', 'info', ['msg' => 'No directories were cleared.']);
        } elseif ($i === 1) {
            \osWFrame\Core\MessageStack::addMessage('result', 'success', ['msg' => $i . ' directory was cleared.']);
        } else {
            \osWFrame\Core\MessageStack::addMessage('result', 'success', ['msg' => $i . ' directories were cleared.']);
        }
        $Tool->readCacheList(\osWFrame\Core\Settings::getStringVar('settings_framepath') . \osWFrame\Tools\Configure::getFrameConfigString('cache_path'));
    }

    $osW_Form = new \osWFrame\Core\Form();
    $osW_Template->setVar('osW_Form', $osW_Form);
}

$osW_Template->setVar('Tool', $Tool);
