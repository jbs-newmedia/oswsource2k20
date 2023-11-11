<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace osWFrame\Core;

trait BaseTemplateBridgeTrait
{
    protected ?Template $obj_Template = null;

    /**
     * Fügt das Objekt dem Template hinzu und arbeitet über Referenzen.
     *
     */
    public function setTemplate(Template $obj_Template): bool
    {
        $this->obj_Template = $obj_Template;

        return true;
    }

    public function getTemplate(): ?Template
    {
        return $this->obj_Template;
    }

    /**
     *
     * @param string $js
     */
    public function addJSCodeHead(string $jscode): bool
    {
        return $this->obj_Template->addJSCodeHead($jscode);
    }

    /**
     *
     * @param string $js
     */
    public function addJSCodeBody(string $jscode): bool
    {
        return $this->obj_Template->addJSCodeBody($jscode);
    }

    /**
     *
     * @param string $css
     */
    public function addCSSCodeHead(string $csscode): bool
    {
        return $this->obj_Template->addCSSCodeHead($csscode);
    }

    /**
     *
     * @param string $css
     */
    public function addCSSCodeBody(string $csscode): bool
    {
        return $this->obj_Template->addCSSCodeBody($csscode);
    }

    public function addJSFileHead(string $jsfile): bool
    {
        return $this->obj_Template->addJSFileHead($jsfile);
    }

    public function addJSFileBody(string $jsfile): bool
    {
        return $this->obj_Template->addJSFileBody($jsfile);
    }

    public function addCSSFileHead(string $cssfile): bool
    {
        return $this->obj_Template->addCSSFileHead($cssfile);
    }

    public function addCSSFileBody(string $cssfile): bool
    {
        return $this->obj_Template->addCSSFileBody($cssfile);
    }

    public function addTemplateJSFile(string $pos, string $file): bool
    {
        return $this->obj_Template->addTemplateFile($pos, 'js', $file);
    }

    public function addTemplateJSFiles(string $pos, array $files): bool
    {
        return $this->obj_Template->addTemplateFiles($pos, 'js', $files);
    }

    public function addTemplateCSSFile(string $pos, string $file): bool
    {
        return $this->obj_Template->addTemplateFile($pos, 'css', $file);
    }

    public function addTemplateCSSFiles(string $pos, array $files): bool
    {
        return $this->obj_Template->addTemplateFiles($pos, 'css', $files);
    }

    public function addTemplateFile(string $pos, string $type, string $file): bool
    {
        if (!isset($this->obj_Template->template_files[$pos])) {
            $this->obj_Template->template_files[$pos] = [];
        }
        if (!isset($this->obj_Template->template_files[$pos][$type])) {
            $this->obj_Template->template_files[$pos][$type] = [];
        }
        $this->obj_Template->template_files[$pos][$type][md5($file)] = $file;

        return true;
    }

    public function addTemplateFiles(string $pos, string $type, array $files): bool
    {
        foreach ($files as $file) {
            $this->obj_Template->addTemplateFile($pos, $type, $file);
        }

        return true;
    }

    public function clearTemplateFiles(): array
    {
        return $this->obj_Template->template_files = [];
    }

    public function getTemplateFiles(string $pos = '', string $type = ''): array
    {
        if ($pos !== '') {
            if (!isset($this->obj_Template->template_files[$pos])) {
                return [];
            }
            if ($type !== '') {
                if (!isset($this->obj_Template->template_files[$pos][$type])) {
                    return [];
                }

                return $this->obj_Template->template_files[$pos][$type];
            }

            return $this->obj_Template->template_files[$pos];
        }

        return $this->obj_Template->template_files;
    }

    public function addTemplateJSCode(string $pos, string $code): bool
    {
        return $this->obj_Template->addTemplateCode($pos, 'js', $code);
    }

    public function addTemplateJSCodes(string $pos, array $codes): bool
    {
        return $this->obj_Template->addTemplateCodes($pos, 'js', $codes);
    }

    public function addTemplateCSSCode(string $pos, string $code): bool
    {
        return $this->obj_Template->addTemplateCode($pos, 'css', $code);
    }

    public function addTemplateCSSCodes(string $pos, array $codes): bool
    {
        return $this->obj_Template->addTemplateCodes($pos, 'css', $codes);
    }

    public function addTemplateCode(string $pos, string $type, string $code): bool
    {
        if (!isset($this->obj_Template->template_codes[$pos])) {
            $this->obj_Template->template_codes[$pos] = [];
        }
        if (!isset($this->obj_Template->template_codes[$pos][$type])) {
            $this->obj_Template->template_codes[$pos][$type] = [];
        }
        $this->obj_Template->template_codes[$pos][$type][md5($code)] = $code;

        return true;
    }

    public function addTemplateCodes(string $pos, string $type, array $codes): bool
    {
        foreach ($codes as $code) {
            $this->obj_Template->addTemplateCode($pos, $type, $code);
        }

        return true;
    }

    public function clearTemplateCodes(): array
    {
        return $this->obj_Template->template_codes = [];
    }

    public function getTemplateCodes(string $pos = '', string $type = ''): array
    {
        if ($pos !== '') {
            if (!isset($this->obj_Template->template_codes[$pos])) {
                return [];
            }
            if ($type !== '') {
                if (!isset($this->obj_Template->template_codes[$pos][$type])) {
                    return [];
                }

                return $this->obj_Template->template_codes[$pos][$type];
            }

            return $this->obj_Template->template_codes[$pos];
        }

        return $this->obj_Template->template_codes;
    }
}
