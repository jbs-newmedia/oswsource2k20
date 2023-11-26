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

class Template
{
    use BaseStaticTrait;
    use BaseTemplateTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 2;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 0;

    /**
     * Release-Version der Klasse.
     */
    private const CLASS_RELEASE_VERSION = 0;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    protected array $textarea_matches = [];

    protected int $textarea_counter = 0;

    protected array $vars = [];

    protected array $conf = [];

    protected array $loader = [];

    protected array $tags = [];

    protected array $codes = [];

    protected array $forms = [];

    public function __construct()
    {
        $this->setConf('gzipcompression', Settings::getBoolVar('template_gzipcompression'));
        $this->setConf('gzipcompression_level', Settings::getIntVar('template_gzipcompression_level'));
        $this->setConf('stripoutput', Settings::getBoolVar('template_stripoutput'));
    }

    public function getOutput(string $file = 'content', string $module = 'project', string $dir = 'modules')
    {
        $content = $this->fetchFileIfExists($file, $module, $dir);
        if (($this->getConf('gzipcompression') === true) && (!headers_sent()) && (!connection_aborted(
        )) && (ob_get_length() === 0)
        ) {
            ini_set('zlib.output_compression_level', $this->getConf('gzipcompression_level'));
            ob_start('ob_gzhandler');
        }

        // strip content
        if ($this->getConf('stripoutput') === true) {
            $content = $this->strip($content);
        }

        // # highlight words in body
        // osW_Template::getInstance()->setHighlightColors(array('#FFFF66', '#A0FFFF', '#99FF99', '#FF9999', '#FF66FF', '#880000', '#00AA00', '#886800'));
        // if ((osW_Session::getInstance()->isSpider()!==true)&&(osW_vOut('frame_highlight_words'))) {
        // $contents=osW_Template::getInstance()->highlightWords($contents, $words);
        // }
        return $content;
    }

    public function strip(string $c): string
    {
        if (Settings::getBoolVar('template_textarea_used') === true) {
            $c = preg_replace_callback('/<textarea [^>]*>.*<\/textarea>/Uis', [$this, 'callback_marktextarea'], $c);
        }
        $c = HTML::stripContent($c);
        if (Settings::getBoolVar('template_textarea_used') === true) {
            foreach (array_keys($this->textarea_matches) as $key) {
                $c = preg_replace(
                    '/<<<OSW_STRIP_REPLACE_TEXTAREA_MARKER_' . $key . '>>>/',
                    $this->textarea_matches[$key],
                    $c
                );
            }
        }

        return $c;
    }

    /**
     * @param bool $ref
     *
     * @return bool
     */
    public function setVar(string $name, &$value, $ref = true)
    {
        if ($ref === true) {
            return $this->setVarAsRef($name, $value);
        }

        return $this->setVarAsCopy($name, $value);
    }

    /**
     * @return mixed
     */
    public function prependVar(string $name, $value)
    {
        return $this->prependVarAsCopy($name, $value);
    }

    /**
     * @return mixed
     */
    public function appendVar(string $name, $value)
    {
        return $this->appendVarAsCopy($name, $value);
    }

    /**
     * @return bool
     */
    public function setVarAsRef(string $name, &$value)
    {
        $this->vars[$name] = &$value;

        return true;
    }

    public function setVarAsCopy(string $name, $value): bool
    {
        $this->vars[$name] = $value;

        return true;
    }

    public function prependVarAsCopy(string $name, $value): bool
    {
        if (isset($this->vars[$name])) {
            $this->vars[$name] = $value . $this->vars[$name];
        } else {
            $this->vars[$name] = $value;
        }

        return true;
    }

    public function appendVarAsCopy(string $name, $value): bool
    {
        if (isset($this->vars[$name])) {
            $this->vars[$name] = $this->vars[$name] . $value;
        } else {
            $this->vars[$name] = $value;
        }

        return true;
    }

    /**
     * @return mixed|null
     */
    public function getVar(string $name)
    {
        if (($name !== '') && (isset($this->vars[$name]))) {
            return $this->vars[$name];
        }

        return null;
    }

    public function setConf(string $name, $value): bool
    {
        $this->conf[$name] = $value;

        return true;
    }

    /**
     * @return mixed|null
     */
    public function getConf(string $name)
    {
        if (($name !== '') && (isset($this->conf[$name]))) {
            return $this->conf[$name];
        }

        return null;
    }

    public function setVarFromFile(
        string $name,
        string $file = 'content',
        string $module = 'project',
        string $dir = 'modules'
    ): bool {
        $module = $this->getModuleByShort($module);

        return $this->setVarAsCopy($name, $this->fetchFileIfExists($file, $module, $dir));
    }

    /**
     * @param string $file
     * @param string $module
     * @param string $dir
     */
    public function isfetchFile($file = 'content', $module = 'project', $dir = 'modules'): bool
    {
        $module = $this->getModuleByShort($module);
        $filename = Settings::getStringVar('settings_abspath').'oswproject' . \DIRECTORY_SEPARATOR;
        $filename_core = Settings::getStringVar('settings_abspath') . 'oswcore' . \DIRECTORY_SEPARATOR;
        if ($dir !== '') {
            $filename .= $dir . \DIRECTORY_SEPARATOR;
            $filename_core .= $dir . \DIRECTORY_SEPARATOR;
        }
        if ($module !== '') {
            $filename .= $module . \DIRECTORY_SEPARATOR;
            $filename_core .= $module . \DIRECTORY_SEPARATOR;
        }
        $filename .= 'tpl' . \DIRECTORY_SEPARATOR . $file . '.tpl.php';
        $filename_core .= 'tpl' . \DIRECTORY_SEPARATOR . $file . '.tpl.php';
        if (file_exists($filename) === true) {
            return true;
        } elseif (file_exists($filename_core) === true) {
            return true;
        }

        return false;
    }

    /**
     * @param string $file
     * @param string $module
     * @param string $dir
     */
    public function fetchFileIfExists($file = 'content', $module = 'project', $dir = 'modules'): string
    {
        $module = $this->getModuleByShort($module);
        if ($this->isfetchFile($file, $module, $dir) === true) {
            return $this->fetchFile($file, $module, $dir);
        }

        return '';
    }

    /**
     * @param string $file
     * @param string $module
     * @param string $dir
     */
    public function fetchFile($file = 'content', $module = 'project', $dir = 'modules'): string
    {
        $module = $this->getModuleByShort($module);
        $filename = Settings::getStringVar('settings_abspath') . 'oswproject' . \DIRECTORY_SEPARATOR;
        $filename_core = Settings::getStringVar('settings_abspath') . 'oswcore' . \DIRECTORY_SEPARATOR;
        if ($dir !== '') {
            $filename .= $dir . \DIRECTORY_SEPARATOR;
            $filename_core .= $dir . \DIRECTORY_SEPARATOR;
        }
        if ($module !== '') {
            $filename .= $module . \DIRECTORY_SEPARATOR;
            $filename_core .= $module . \DIRECTORY_SEPARATOR;
        }
        $filename .= 'tpl' . \DIRECTORY_SEPARATOR . $file . '.tpl.php';
        $filename_core .= 'tpl' . \DIRECTORY_SEPARATOR . $file . '.tpl.php';

        return $this->fetch($filename, $filename_core);
    }

    public function fetch(string $file1, string $file2): string
    {
        extract($this->vars);
        ob_start();
        if (file_exists($file1)) {
            require $file1;
        } elseif (file_exists($file2)) {
            require $file2;
        }
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    public function buildhrefLink(string $module = '', string $parameters = '', bool $replace_amp = true): string
    {
        if (($module === '') || ($module === 'default')) {
            $module = Settings::getStringVar('project_default_module');
        }
        if ($module === 'current') {
            $module = Settings::getStringVar('frame_current_module');
        }
        if ($replace_amp === true) {
            return str_replace('&', '&amp;', Navigation::buildUrl($module, $parameters));
        }

        return Navigation::buildUrl($module, $parameters);
    }

    /**
     * Fügt einen Tag hinzu.
     * (<script src="" ...></script>)
     *
     */
    public function addTag(string $tag, array $attributes, string $pos = 'head', bool $void = false): bool
    {
        if (!isset($this->tags[$pos])) {
            $this->tags[$pos] = [];
        }
        $this->tags[$pos][md5(
            serialize([
                'tag' => $tag,
                'attributes' => $attributes,
                'void' => $void,
                'string' => false,
            ])
        )] = [
            'tag' => $tag,
            'attributes' => $attributes,
            'void' => $void,
            'string' => false,
        ];

        return true;
    }

    /**
     * Fügt eine Void-Tag hinzu.
     * (<img ... />)
     *
     */
    public function addVoidTag(string $tag, array $attributes, string $pos = 'head'): bool
    {
        return $this->addTag($tag, $attributes, $pos, true);
    }

    /**
     * Fügt einen String-Tag hinzu.
     * <title>...</title>
     *
     */
    public function addStringTag(string $tag, string $attributes, string $pos = 'head'): bool
    {
        if (!isset($this->tags[$pos])) {
            $this->tags[$pos] = [];
        }
        $this->tags[$pos][md5(
            serialize([
                'tag' => $tag,
                'attributes' => $attributes,
                'void' => false,
                'string' => true,
            ])
        )] = [
            'tag' => $tag,
            'attributes' => $attributes,
            'void' => false,
            'string' => true,
        ];

        return true;
    }

    /**
     *
     * @param string $contnt
     */
    public function addCode(string $tag, array $attributes, string $content, string $pos = 'head'): bool
    {
        if (!isset($this->codes[$pos])) {
            $this->codes[$pos] = [];
        }
        $this->codes[$pos][md5(
            serialize([
                'tag' => $tag,
                'content' => $content,
                'attributes' => $attributes,
            ])
        )] = [
            'tag' => $tag,
            'content' => $content,
            'attributes' => $attributes,
        ];

        return true;
    }

    public function getHead(): string
    {
        $this->getJSFiles('head');
        $this->getCSSFiles('head');
        $this->getJSCodes('head');
        $this->getCSSCodes('head');
        $this->getJSFiles('headend');
        $this->getCSSFiles('headend');
        $this->getJSCodes('headend');
        $this->getCSSCodes('headend');
        $content = '';
        $content .= $this->outputTags('head');
        $content .= $this->outputCodes('head');
        $content .= $this->outputTags('headend');
        $content .= $this->outputCodes('headend');

        return $content;
    }

    public function getBody(): string
    {
        $this->getJSFiles('body');
        $this->getCSSFiles('body');
        $this->getJSCodes('body');
        $this->getCSSCodes('body');
        $content = '';
        $content .= $this->outputTags('body');
        $content .= $this->outputCodes('body');

        return $content;
    }

    public function getFooter(): string
    {
        $this->getJSFiles('footer');
        $this->getCSSFiles('footer');
        $this->getJSCodes('footer');
        $this->getCSSCodes('footer');
        $content = '';
        $content .= $this->outputTags('footer');
        $content .= $this->outputCodes('footer');

        return $content;
    }

    public function setForm(string $alias = 'default', string $namespace = ''): bool
    {
        if ($namespace === '') {
            $namespace = 'osWFrame\Core\Form';
        }
        $this->forms[$alias] = new $namespace();

        return true;
    }

    public function Form(string $alias = 'default'): Form
    {
        if (!isset($this->forms[$alias])) {
            $this->setForm($alias);
        }

        return $this->forms[$alias];
    }

    public function getOptimizedImage(string $filename, array $options = []): string
    {
        if (!isset($options['module'])) {
            $options['module'] = '';
        }
        if ((!isset($options['path'])) || ($options['path'] === '')) {
            if (($options['module'] === '') || ($options['module'] === 'default')) {
                $options['module'] = Settings::getStringVar('project_default_module');
            }
            if ($options['module'] === 'current') {
                $options['module'] = Settings::getStringVar('frame_current_module');
            }

            $options['path'] = 'modules' . \DIRECTORY_SEPARATOR . $options['module'] . \DIRECTORY_SEPARATOR . 'img' . \DIRECTORY_SEPARATOR;
        }

        if (isset($options['subdir'])) {
            $options['path'] .= $options['subdir'] . \DIRECTORY_SEPARATOR;
        }

        $rel_file = $options['path'] . $filename;
        $abs_file = Settings::getStringVar('settings_abspath') . $rel_file;
        if (!file_exists($abs_file)) {
            MessageStack::addMessage(self::getNameAsString(), 'error', [
                'time' => time(),
                'line' => __LINE__,
                'function' => __FUNCTION__,
                'error' => 'File not found (' . $rel_file . ')',
            ]);

            return '';
        }

        $osW_ImageOptimizer = new ImageOptimizer();
        $osW_ImageOptimizer->setOptionsByArray($options);

        $path_filename = pathinfo($abs_file, \PATHINFO_FILENAME);
        $path_extension = pathinfo($abs_file, \PATHINFO_EXTENSION);

        if (!isset($options['alt'])) {
            $options['alt'] = $path_filename;
        }

        if (!isset($options['title'])) {
            $options['title'] = '';
        }

        if (!isset($options['parameter'])) {
            $options['parameter'] = '';
        }

        if (Settings::getBoolVar('imageoptimizer_protect_files') === true) {
            $osW_ImageOptimizer->setPS($rel_file);
        }

        $new_filename = $path_filename . '.' . $osW_ImageOptimizer->getOptionsAsString() . '.' . $path_extension;

        $out = '';
        $out .= '<img ' . $options['parameter'] . ' src="static/' . Settings::getStringVar(
            'imageoptimizer_module'
        ) . '/' . $options['path'] . $new_filename;
        /* ToDo: height/width ermitteln und angeben */
        $out .= '" alt="' . HTML::outputString($options['alt']) . '" title="' . HTML::outputString($options['title']) . '" />';

        return $out;
    }

    protected function callback_marktextarea(array $matches): string
    {
        $this->textarea_matches[] = $matches[0];

        return '<<<OSW_STRIP_REPLACE_TEXTAREA_MARKER_' . $this->textarea_counter++ . '>>>';
    }

    /**
     * @param string $module
     */
    protected function getModuleByShort($module = 'project'): string
    {
        if ($module === 'project') {
            return Settings::getStringVar('project_default_module');
        } elseif ($module === 'default') {
            return Settings::getStringVar('frame_default_module');
        } elseif ($module === 'current') {
            return Settings::getStringVar('frame_current_module');
        }

        return $module;
    }

    /**
     * Gibt die Tags aus.
     *
     */
    protected function outputTags(string $pos): string
    {
        if (!isset($this->tags[$pos])) {
            return '';
        }
        $content = '';
        foreach ($this->tags[$pos] as $tag) {
            $content .= $this->buildTag($tag) . "\n";
        }

        return $content;
    }

    /**
     * Baut einen Tag zusammen.
     *
     */
    protected function buildTag(array $tag): string
    {
        if ($tag['string'] === true) {
            return '<' . $tag['tag'] . '>' . $tag['attributes'] . '</' . $tag['tag'] . '>';
        }
        if ($tag['void'] === true) {
            return '<' . $tag['tag'] . ' ' . $this->buildAttributes($tag['attributes']) . ' />';
        }

        return '<' . $tag['tag'] . ' ' . $this->buildAttributes($tag['attributes']) . '></' . $tag['tag'] . '>';


        return '';
    }

    protected function outputCodes(string $pos): string
    {
        if (!isset($this->codes[$pos])) {
            return '';
        }
        $content = '';
        foreach ($this->codes[$pos] as $code) {
            $content .= '<' . $code['tag'] . ' ' . $this->buildAttributes(
                $code['attributes']
            ) . '>' . $code['content'] . '</' . $code['tag'] . ">\n";
        }

        return $content;
    }

    protected function buildAttributes(array $attributes): string
    {
        $result = [];
        foreach ($attributes as $key => $value) {
            $result[] = $key . '="' . $value . '"';
        }

        return implode(' ', $result);
    }

    protected function getTemplateFiles(string $pos = '', string $type = ''): array
    {
        if ((isset($this->template_files[$pos])) && (isset($this->template_files[$pos][$type]))) {
            return $this->template_files[$pos][$type];
        }

        return [];
    }

    protected function getTemplateCodes(string $pos = '', string $type = ''): array
    {
        if ((isset($this->template_codes[$pos])) && (isset($this->template_codes[$pos][$type]))) {
            return $this->template_codes[$pos][$type];
        }

        return [];
    }

    protected function getJSFiles(string $pos): bool
    {
        if ($this->getTemplateFiles($pos, 'js') !== []) {
            if (Settings::getBoolVar('smartoptimizer_combine_files') === true) {
                $str = implode(',', $this->getTemplateFiles($pos, 'js'));
                $file = md5($str) . '.js';
                SmartOptimizer::writeCacheFile($file, $str);
                if (Settings::getStringVar('template_versionnumber') === '') {
                    $this->addTag('script', [
                        'src' => 'static/' . Settings::getStringVar('scriptoptimizer_module') . '/' . $file,
                    ], $pos);
                } elseif (Settings::getStringVar('template_versionnumber') === 'cachetime') {
                    $this->addTag('script', [
                        'src' => 'static/' . Settings::getStringVar(
                            'scriptoptimizer_module'
                        ) . '/' . $file . '?v=' . Filesystem::getFileModTime(
                            Cache::getDirName('smartoptimizer') . $file,
                            false
                        ),
                    ], $pos);
                } else {
                    $this->addTag('script', [
                        'src' => 'static/' . Settings::getStringVar(
                            'scriptoptimizer_module'
                        ) . '/' . $file . '?v=' . Settings::getStringVar('template_versionnumber'),
                    ], $pos);
                }
            } else {
                foreach ($this->getTemplateFiles($pos, 'js') as $file) {
                    if (strstr($file, '?')) {
                        $c = '&';
                    } else {
                        $c = '?';
                    }
                    if (Settings::getBoolVar('smartoptimizer_stripoutput') === true) {
                        if (Settings::getStringVar('template_versionnumber') === '') {
                            $this->addTag('script', [
                                'src' => 'static/' . Settings::getStringVar('scriptoptimizer_module') . '/' . $file,
                            ], $pos);
                        } elseif (Settings::getStringVar('template_versionnumber') === 'cachetime') {
                            $this->addTag('script', [
                                'src' => 'static/' . Settings::getStringVar(
                                    'scriptoptimizer_module'
                                ) . '/' . $file . $c . 'v=' . Filesystem::getFileModTime(
                                    Settings::getStringVar('settings_abspath') . $file,
                                    false
                                ),
                            ], $pos);
                        } else {
                            $this->addTag('script', [
                                'src' => 'static/' . Settings::getStringVar(
                                    'scriptoptimizer_module'
                                ) . '/' . $file . $c . 'v=' . Settings::getStringVar('template_versionnumber'),
                            ], $pos);
                        }
                    } else {
                        if (Settings::getStringVar('template_versionnumber') === '') {
                            $this->addTag('script', [
                                'src' => $file,
                            ], $pos);
                        } elseif (Settings::getStringVar('template_versionnumber') === 'cachetime') {
                            $this->addTag('script', [
                                'src' => $file . $c . 'v=' . Filesystem::getFileModTime(
                                    Settings::getStringVar('settings_abspath') . $file,
                                    false
                                ),
                            ], $pos);
                        } else {
                            $this->addTag('script', [
                                'src' => $file . $c . 'v=' . Settings::getStringVar('template_versionnumber'),
                            ], $pos);
                        }
                    }
                }
            }
        }

        return true;
    }

    protected function getCSSFiles(string $pos): bool
    {
        if ($this->getTemplateFiles($pos, 'css') !== []) {
            if (Settings::getBoolVar('smartoptimizer_combine_files') === true) {
                $str = implode(',', $this->getTemplateFiles($pos, 'css'));
                $file = md5($str) . '.css';
                SmartOptimizer::writeCacheFile($file, $str);
                if (Settings::getStringVar('template_versionnumber') === '') {
                    $this->addVoidTag('link', [
                        'rel' => 'stylesheet',
                        'type' => 'text/css',
                        'href' => 'static/' . Settings::getStringVar('styleoptimizer_module') . '/' . $file,
                    ], $pos);
                } elseif (Settings::getStringVar('template_versionnumber') === 'cachetime') {
                    $this->addVoidTag('link', [
                        'rel' => 'stylesheet',
                        'type' => 'text/css',
                        'href' => 'static/' . Settings::getStringVar(
                            'styleoptimizer_module'
                        ) . '/' . $file . '?v=' . Filesystem::getFileModTime(
                            Cache::getDirName('smartoptimizer') . $file,
                            false
                        ),
                    ], $pos);
                } else {
                    $this->addVoidTag('link', [
                        'rel' => 'stylesheet',
                        'type' => 'text/css',
                        'href' => 'static/' . Settings::getStringVar(
                            'styleoptimizer_module'
                        ) . '/' . $file . '?v=' . Settings::getStringVar('template_versionnumber'),
                    ], $pos);
                }
            } else {
                foreach ($this->getTemplateFiles($pos, 'css') as $file) {
                    if (strstr($file, '?')) {
                        $c = '&';
                    } else {
                        $c = '?';
                    }
                    if (Settings::getBoolVar('smartoptimizer_stripoutput') === true) {
                        if (Settings::getStringVar('template_versionnumber') === '') {
                            $this->addVoidTag('link', [
                                'rel' => 'stylesheet',
                                'type' => 'text/css',
                                'href' => 'static/' . Settings::getStringVar('styleoptimizer_module') . '/' . $file,
                            ], $pos);
                        } elseif (Settings::getStringVar('template_versionnumber') === 'cachetime') {
                            $this->addVoidTag('link', [
                                'rel' => 'stylesheet',
                                'type' => 'text/css',
                                'href' => 'static/' . Settings::getStringVar(
                                    'styleoptimizer_module'
                                ) . '/' . $file . $c . 'v=' . Filesystem::getFileModTime(
                                    Settings::getStringVar('settings_abspath') . $file,
                                    false
                                ),
                            ], $pos);
                        } else {
                            $this->addVoidTag('link', [
                                'rel' => 'stylesheet',
                                'type' => 'text/css',
                                'href' => 'static/' . Settings::getStringVar(
                                    'styleoptimizer_module'
                                ) . '/' . $file . $c . 'v=' . Settings::getStringVar('template_versionnumber'),
                            ], $pos);
                        }
                    } else {
                        if (Settings::getStringVar('template_versionnumber') === '') {
                            $this->addVoidTag('link', [
                                'rel' => 'stylesheet',
                                'type' => 'text/css',
                                'href' => $file,
                            ], $pos);
                        } elseif (Settings::getStringVar('template_versionnumber') === 'cachetime') {
                            $this->addVoidTag('link', [
                                'rel' => 'stylesheet',
                                'type' => 'text/css',
                                'href' => $file . $c . 'v=' . Filesystem::getFileModTime(
                                    Settings::getStringVar('settings_abspath') . $file,
                                    false
                                ),
                            ], $pos);
                        } else {
                            $this->addVoidTag('link', [
                                'rel' => 'stylesheet',
                                'type' => 'text/css',
                                'href' => $file . $c . 'v=' . Settings::getStringVar('template_versionnumber'),
                            ], $pos);
                        }
                    }
                }
            }
        }

        return true;
    }

    protected function getJSCodes(string $pos): bool
    {
        $codes = [];
        if ($this->getTemplateCodes($pos, 'js') !== []) {
            foreach ($this->getTemplateCodes($pos, 'js') as $code) {
                $codes[] = $code;
            }
        }
        if ($codes !== []) {
            if (Settings::getBoolVar('smartoptimizer_stripoutput') === true) {
                $this->addCode('script', [
                    'type' => 'text/javascript',
                ], "\n" . implode("\n\n", $codes) . "\n", $pos);
            } else {
                $this->addCode('script', [
                    'type' => 'text/javascript',
                ], "\n" . implode("\n\n", $codes) . "\n", $pos);
            }
        }

        return true;
    }

    protected function getCSSCodes(string $pos): bool
    {
        $codes = [];
        if ($this->getTemplateCodes($pos, 'css') !== []) {
            foreach ($this->getTemplateCodes($pos, 'css') as $code) {
                $codes[] = $code;
            }
        }
        if ($codes !== []) {
            if (Settings::getBoolVar('smartoptimizer_stripoutput') === true) {
                $this->addCode('style', [
                    'type' => 'text/css',
                    'title' => 'text/css',
                ], "\n" . implode("\n\n", $codes) . "\n", $pos);
            } else {
                $this->addCode('style', [
                    'type' => 'text/css',
                    'title' => 'text/css',
                ], "\n" . implode("\n\n", $codes) . "\n", $pos);
            }
        }

        return true;
    }
}
