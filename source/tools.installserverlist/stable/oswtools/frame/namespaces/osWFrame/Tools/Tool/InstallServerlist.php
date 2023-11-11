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

namespace osWFrame\Tools\Tool;

use osWFrame\Core as Frame;
use osWFrame\Tools as Tools;

class InstallServerlist extends CoreTool
{
    use Frame\BaseStaticTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 1;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 0;

    /**
     * Release-Version der Klasse.
     */
    private const CLASS_RELEASE_VERSION = 1;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    /**
     *
     */
    public function __construct(string $serverlist, string $package, string $release)
    {
        parent::__construct($serverlist, $package, $release);
    }

    /**
     * @return $this
     */
    public function installServerListPackage(string $url): self
    {
        if ($url !== '') {
            $file = Frame\Settings::getStringVar('settings_abspath') . Frame\Settings::getStringVar('cache_path') . md5($url) . '.zip';
            $package_data = Tools\Server::getUrlData($url . '/index.php?action=get_serverlist');
            file_put_contents($file, $package_data);
            $Zip = new Frame\Zip($file);
            if ($Zip->unpackDir(Frame\Settings::getStringVar('settings_framepath'), Tools\Configure::getFrameConfigInt('settings_chmod_dir'), Tools\Configure::getFrameConfigInt('settings_chmod_file')) === true) {
                \osWFrame\Core\MessageStack::addMessage('result', 'success', ['msg' => 'Serverlist "' . htmlspecialchars($url) . '" installed successfully.']);
            } else {
                \osWFrame\Core\MessageStack::addMessage('result', 'danger', ['msg' => 'Serverlist "' . htmlspecialchars($url) . '" could not be installed.']);
            }
            Frame\Filesystem::delFile($file);
        }

        return $this;
    }
}
