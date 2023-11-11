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

class CoreTool
{
    use Frame\BaseStaticTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 1;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 2;

    /**
     * Release-Version der Klasse.
     */
    private const CLASS_RELEASE_VERSION = 0;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    /**
     */
    protected bool $fluid_navigation = false;

    /**
     */
    protected bool $fluid_content = false;

    /**
     */
    protected bool $vh = false;

    /**
     */
    protected string $serverlist = '';

    /**
     */
    protected string $package = '';

    /**
     */
    protected string $release = '';

    /**
     */
    protected array $details = [];

    /**
     */
    protected array $navigation = [];

    /**
     */
    protected array $actions = [];

    /**
     */
    protected array $actions_names = [];

    /**
     */
    protected array $used_software = [];

    /**
     *
     */
    public function __construct(string $serverlist, string $package, string $release)
    {
        $this->setServerlist($serverlist);
        $this->setPackage($package);
        $this->setRelease($release);
        $this->checkFrameKey();
        $this->initTool();
        $this->initUsedSoftware();
        Frame\MessageWriter::addIgnore('result');
        Frame\MessageWriter::addIgnore('configure');
        if ($this->checkProtection() !== true) {
            \osWFrame\Core\MessageStack::addMessage('result', 'danger', ['msg' => 'Please protect your tools. Check "osWTools:Main" ➜ "More" ➜ "Protect tools"']);
        }
        if ($this->checkSSL() !== true) {
            \osWFrame\Core\MessageStack::addMessage('result', 'danger', ['msg' => 'Please use secure connection via SSL.']);
        }
    }

    /**
     * @return $this
     */
    public function setVH(bool $vh): self
    {
        $this->vh = $vh;

        return $this;
    }

    /**
     */
    public function getVH(): bool
    {
        return $this->vh;
    }

    /**
     * @return $this
     */
    public function setFluidNavigation(bool $fluid): self
    {
        $this->fluid_navigation = $fluid;

        return $this;
    }

    /**
     */
    public function getFluidNavigation(): bool
    {
        return $this->fluid_navigation;
    }

    /**
     * @return $this
     */
    public function setFluidContent(bool $fluid): self
    {
        $this->fluid_content = $fluid;

        return $this;
    }

    /**
     */
    public function getFluidContent(): bool
    {
        return $this->fluid_content;
    }

    /**
     * @return $this
     */
    public function setServerlist(string $serverlist): self
    {
        $this->serverlist = $serverlist;

        return $this;
    }

    /**
     * @return $this
     */
    public function setPackage(string $package): self
    {
        $this->package = $package;

        return $this;
    }

    /**
     * @return $this
     */
    public function setRelease(string $release): self
    {
        $this->release = $release;

        return $this;
    }

    /**
     */
    public function getServerlist(): string
    {
        return $this->serverlist;
    }

    /**
     */
    public function getPackage(): string
    {
        return $this->package;
    }

    /**
     */
    public function getRelease(): string
    {
        return $this->release;
    }

    /**
     * @return $this
     */
    public function initTool(): self
    {
        $file = Frame\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'json' . \DIRECTORY_SEPARATOR . 'package' . \DIRECTORY_SEPARATOR . $this->getPackage() . '-' . $this->getRelease() . '.json';
        $this->details['name'] = '';
        $this->details['author'] = '';
        $this->details['copyright'] = '';
        $this->details['link'] = '';
        $this->details['license'] = '';
        $this->details['package'] = '';
        $this->details['release'] = '';
        $this->details['version'] = '';
        $this->details['version_update'] = '';
        if (file_exists($file)) {
            $info = json_decode(file_get_contents($file), true);
            foreach ($info['info'] as $key => $value) {
                $this->details[$key] = $value;
            }
        }
        $this->details['server'] = '';
        $this->details['updtserver'] = '';
        $this->details['connected'] = false;
        $this->details['updterror'] = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function initUsedSoftware(): self
    {
        $this->addUsedSoftware('jQuery', 'https://jquery.com', 'Write less, do more. JavaScript library');
        $this->addUsedSoftware('Bootstrap', 'https://getbootstrap.com', 'The most popular HTML, CSS, and JS library in the world');
        $this->addUsedSoftware('Font Awesome', 'https://fontawesome.com', 'the iconic font and CSS toolkit');
        $this->addUsedSoftware('DataTables', 'https://datatables.net', 'Table plug-in for jQuery/Bootstrap');
        $this->addUsedSoftware('Bootbox.js', 'http://bootboxjs.com', 'Bootstrap modals made easy');
        $this->addUsedSoftware('bootstrap-select', 'https://developer.snapappointments.com/bootstrap-select/', 'Bootstrap-select is a jQuery plugin that utilizes Bootstrap\'s dropdown.js to style and bring additional functionality to standard select elements');
        $this->addUsedSoftware('jQuery Easing', 'https://github.com/gdsmith/jquery.easing/', 'A jQuery plugin from GSGD to give advanced easing options');

        return $this;
    }

    /**
     * @return $this
     */
    public function addUsedSoftware(string $name, string $url, string $description): self
    {
        $this->used_software[md5($name)] = ['name' => $name, 'url' => $url, 'description' => $description];

        return $this;
    }

    /**
     */
    public function getUsedSoftware(): array
    {
        return $this->used_software;
    }

    /**
     */
    public function getStringValue(string $key): ?string
    {
        if (isset($this->details[$key])) {
            return (string) ($this->details[$key]);
        }

        return null;
    }

    /**
     */
    public function getBoolValue(string $key): ?bool
    {
        if (isset($this->details[$key])) {
            return (bool) ($this->details[$key]);
        }

        return null;
    }

    /**
     */
    public function blockUpdate(string $link): void
    {
        $update = Frame\Session::getArrayVar('update');
        if ($update === null) {
            $update = [];
        }
        if (!isset($update[$this->getServerlist() . '#' . $this->getPackage() . '#' . $this->getRelease()])) {
            $update[$this->getServerlist() . '#' . $this->getPackage() . '#' . $this->getRelease()] = time();
            Frame\Session::setArrayVar('update', $update);
        }

        Frame\Network::directHeader($link);
    }

    /**
     */
    public function hasUpdate(): bool
    {
        $file = Frame\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'json' . \DIRECTORY_SEPARATOR . 'package' . \DIRECTORY_SEPARATOR . $this->getPackage() . '-' . $this->getRelease() . '.json';
        if (file_exists($file)) {
            $info = json_decode(file_get_contents($file), true);
            $server_data = Tools\Server::getConnectedServer($this->getServerlist());
            if ((isset($server_data['connected'])) && ($server_data['connected'] === true)) {
                $package_version = Tools\Server::getUrlData($server_data['server_url'] . '?action=get_version&package=' . $this->getPackage() . '&release=' . $this->getRelease() . '&version=' . $info['info']['version']);
                $this->details['version_update'] = $package_version;
                if (Tools\Helper::checkVersion($this->getStringValue('version'), $package_version)) {
                    $update = Frame\Session::getArrayVar('update');
                    if (($update === null) || (!isset($update[$this->getServerlist() . '#' . $this->getPackage() . '#' . $this->getRelease()]))) {
                        Tools\Server::updatePackageList(true);

                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     */
    public function getUpdateConfirm(string $update_link, string $update_no): string
    {
        return '$(function() { osWTools_confirmUpdate(\'Update <strong>' . $this->getStringValue('name') . '</strong> to version <strong>' . $this->getStringValue('version_update') . '</strong>\', \'' . $update_link . '\', \'' . $update_no . '\');});';
    }

    /**
     */
    public function installUpdate(string $link): void
    {
        $Manager = new Tools\Manager();
        $Manager->installPackage($this->getServerlist(), $this->getPackage(), $this->getRelease());
        Frame\Network::directHeader($link);
    }

    /**
     * @return $this
     */
    public function addNavigationElement(string $element_name, array $element_details, string $position = ''): self
    {
        if (isset($element_details['action'])) {
            $this->addAction($element_details['action']);
        }
        if ($position === '') {
            $this->navigation[$element_name] = $element_details;
            $this->navigation[$element_name]['active'] = false;
            $this->navigation[$element_name]['links'] = [];
        } else {
            $this->navigation[$position]['links'][$element_name] = $element_details;
            $this->navigation[$position]['links'][$element_name]['active'] = false;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function checkNavigation(string $action): self
    {
        foreach ($this->navigation as $element_name => $a) {
            if ((isset($this->navigation[$element_name]['action'])) && ($this->navigation[$element_name]['action'] === $action)) {
                $this->navigation[$element_name]['active'] = true;
                $this->actions_names[$this->navigation[$element_name]['action']] = $this->navigation[$element_name]['title'];
            }

            if (isset($this->navigation[$element_name]['links'])) {
                foreach ($this->navigation[$element_name]['links'] as $element_link_name => $b) {
                    if ((isset($this->navigation[$element_name]['links'][$element_link_name]['action'])) && ($this->navigation[$element_name]['links'][$element_link_name]['action'] === $action)) {
                        $this->navigation[$element_name]['active'] = true;
                        $this->navigation[$element_name]['links'][$element_link_name]['active'] = true;
                        $this->actions_names[$this->navigation[$element_name]['links'][$element_link_name]['action']] = $this->navigation[$element_name]['links'][$element_link_name]['title'];
                    }
                }
            }
        }

        return $this;
    }

    /**
     */
    public function getActionName(string $action): string
    {
        if (isset($this->actions_names[$action])) {
            return $this->actions_names[$action];
        }

        return 'Unnamed';
    }

    /**
     * @return $this
     */
    public function addAction(string $action): self
    {
        if (!\in_array($action, $this->actions, true)) {
            $this->actions[] = $action;
        }

        return $this;
    }

    /**
     */
    public function validateAction(string $action): string
    {
        if (\in_array($action, $this->actions, true)) {
            return $action;
        }

        return 'start';
    }

    /**
     */
    public function getNavigation(): array
    {
        return $this->navigation;
    }

    /**
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     */
    public function hasProtection(): bool
    {
        $file_ht = \osWFrame\Core\Settings::getStringVar('settings_abspath') . '.htaccess';
        if (Frame\Filesystem::existsFile($file_ht)) {
            if (strpos(file_get_contents($file_ht), 'AuthType Basic') > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     */
    public function checkProtection(): bool
    {
        $file_dev = \osWFrame\Core\Settings::getStringVar('settings_framepath') . 'modules' . \DIRECTORY_SEPARATOR . 'configure.project-dev.php';
        if (Frame\Filesystem::existsFile($file_dev) === true) {
            return true;
        }

        return $this->hasProtection();
    }

    /**
     */
    public function hasSSL(): bool
    {
        return Tools\Configure::getFrameConfigBool('settings_ssl');
    }

    /**
     */
    public function checkSSL(): bool
    {
        $file_dev = \osWFrame\Core\Settings::getStringVar('settings_framepath') . 'modules' . \DIRECTORY_SEPARATOR . 'configure.project-dev.php';
        if (Frame\Filesystem::existsFile($file_dev) === true) {
            return true;
        }

        return $this->hasSSL();
    }

    /**
     * @return $this
     */
    public function checkFrameKey(): self
    {
        $file_framekey = \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'frame.key';
        if ((Frame\Filesystem::existsFile($file_framekey) !== true) || (filesize($file_framekey) !== 64)) {
            $this->writeFrameKey($this->generateFrameKey());
            \osWFrame\Core\MessageStack::addMessage('result', 'success', ['msg' => 'Frame-Key generated successfully. Check "osWTools:Main" ➜ "More" ➜ "Frame-Key"']);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function createNewFrameKey(): self
    {
        $this->writeFrameKey($this->generateFrameKey());
        \osWFrame\Core\MessageStack::addMessage('result', 'success', ['msg' => 'Frame-Key generated successfully. Check "osWTools:Main" ➜ "More" ➜ "Frame-Key"']);

        return $this;
    }

    /**
     * @return $this
     */
    public function writeFrameKey(string $frame_key): self
    {
        $file_framekey = \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'frame.key';
        file_put_contents($file_framekey, $frame_key);
        Frame\Filesystem::changeFilemode($file_framekey, Tools\Configure::getFrameConfigInt('settings_chmod_file'));

        return $this;
    }

    /**
     * @return $this
     */
    public function writeAccountEMail(string $account_email): self
    {
        $file_account_email = \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'account.email';
        file_put_contents($file_account_email, $account_email);
        Frame\Filesystem::changeFilemode($file_account_email, Tools\Configure::getFrameConfigInt('settings_chmod_file'));

        return $this;
    }

    /**
     */
    public function generateFrameKey(): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars_length = \strlen($chars);
        $frame_key = '';
        for ($i = 0; $i < 64; $i++) {
            $frame_key .= $chars[rand(0, $chars_length - 1)];
        }

        return $frame_key;
    }

    /**
     */
    public function validateFrameKey(string $frame_key): bool
    {
        if (preg_match('/^([0-9a-zA-Z]{64,64})$/Uis', $frame_key)) {
            return true;
        }

        return false;
    }
}
