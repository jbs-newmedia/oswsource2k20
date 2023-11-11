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

class CKEditor5
{
    use BaseStaticTrait;

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

    protected string $selector_type = '#';

    protected string $selector = '';

    protected array $conf = [];

    protected array $then = [];

    protected array $catch = [];

    protected array $simpleUpload = [];

    protected array $file = [];

    public function __construct(
        string $selector_type,
        string $selector,
        array $conf = [],
        array $then = [],
        array $catch = []
    ) {
        if ($conf === []) {
            $conf = self::getDefaultConf();
        }

        $this->setSelectorType($selector_type);
        $this->setSelector($selector);
        $this->setConf($conf);
        $this->setThen($then);
        $this->setCatch($catch);
    }

    public static function getDefaultConf(): array
    {
        $conf = [];
        $conf['toolbar'] = [];
        $conf['toolbar']['items'] = [
            'undo',
            'redo',
            '|',
            'findAndReplace',
            'selectAll',
            '|',
            'heading',
            '|',
            'removeFormat',
            'bold',
            'italic',
            'strikethrough',
            'underline',
            'subscript',
            'superscript',
            '|',
            'fontColor',
            'fontBackgroundColor',
            '|',
            'specialCharacters',
            'link',
            'insertTable',
            'insertImage',
            'mediaEmbed',
            'htmlEmbed',
            '|',
            'bulletedList',
            'numberedList',
            'alignment',
            '|',
            'sourceEditing',
        ];
        $conf['toolbar']['shouldNotGroupWhenFull'] = true;
        $conf['image'] = [];
        $conf['image']['styles'] = ['alignCenter', 'alignLeft', 'alignRight'];
        $conf['image']['resizeOptions'] = [
            [
                'name' => 'resizeImage:original',
                'label' => 'Original',
                'value' => null,
            ],
            [
                'name' => 'resizeImage:25',
                'label' => '25%',
                'value' => '25',
            ],
            [
                'name' => 'resizeImage:50',
                'label' => '50%',
                'value' => '50',
            ],
            [
                'name' => 'resizeImage:75',
                'label' => '75%',
                'value' => '75',
            ],
            [
                'name' => 'resizeImage:100',
                'label' => '100%',
                'value' => '100',
            ],
        ];
        $conf['image']['toolbar'] = [
            'linkImage',
            'imageTextAlternative',
            '|',
            'imageStyle:inline',
            'imageStyle:wrapText',
            'imageStyle:breakText',
            'imageStyle:side',
            '|',
            'resizeImage',
        ];
        $conf['image']['insert'] = [
            'integrations' => ['insertImageViaUrl'],
        ];
        $conf['list'] = [];
        $conf['list']['properties'] = [
            'styles' => true,
            'startIndex' => true,
            'reversed' => true,
        ];
        $conf['table'] = [];
        $conf['table']['contentToolbar'] = ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties'];
        $conf['link'] = [];
        $conf['link']['decorators']['openInNewTab']['mode'] = 'manual';
        $conf['link']['decorators']['openInNewTab']['label'] = 'Open in a new tab';
        $conf['link']['decorators']['openInNewTab']['attributes']['target'] = '_blank';
        $conf['link']['decorators']['openInNewTab']['attributes']['rel'] = 'noopener noreferrer';

        return $conf;
    }

    public function setConf(array $conf): void
    {
        $this->conf = $conf;
    }

    public function getConf(): array
    {
        return $this->conf;
    }

    public function setThen(array $then): void
    {
        $this->then = $then;
    }

    public function getThen(): array
    {
        return $this->then;
    }

    public function setCatch(array $catch): void
    {
        $this->catch = $catch;
    }

    public function getCatch(): array
    {
        return $this->catch;
    }

    public function setSelectorType(string $selector_type): void
    {
        $this->selector_type = $selector_type;
    }

    public function getSelectorType(): string
    {
        return $this->selector_type;
    }

    public function setSelector(string $selector): void
    {
        $this->selector = $selector;
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function setSimpleUploadUrl(string $url): void
    {
        $this->simpleUpload['uploadUrl'] = $url;
    }

    public function setSimpleUploadWithCredentials(bool $withCredentials): void
    {
        $this->simpleUpload['withCredentials'] = $withCredentials;
    }

    public function setSimpleUploadHeaders(array $headers): void
    {
        $this->simpleUpload['headers'] = $headers;
    }

    public function setSimpleUploadHeader(string $key, string $value): void
    {
        $this->simpleUpload['headers'][$key] = $value;
    }

    public function getSimpleUpload(): array
    {
        return $this->simpleUpload;
    }

    public function getJS(): string
    {
        $conf = $this->getConf();
        if ($this->getSimpleUpload() !== []) {
            Session::setArrayVar(
                'ck5editor_' . md5(
                    '_ckeditor5_simple_upload#' . $this->getSelector() . '#' . Settings::getStringVar(
                        'settings_protection_salt'
                    )
                ),
                $this->getFile()
            );
            $conf['simpleUpload'] = $this->getSimpleUpload();
        }

        $output = [];
        $output[] = 'ClassicEditor';
        $output[] = '	.create(document.querySelector(\'' . $this->getSelectorType() . $this->getSelector() . '\'), {';
        $output[] = substr(json_encode($conf), 1, -1);
        $output[] = '	})';
        $output[] = '	.then(editor => {';
        if ($this->getThen() !== []) {
            $output[] = implode("\n", $this->getThen());
        }
        $output[] = '	})';
        $output[] = '	.catch(error => {';
        if ($this->getCatch() !== []) {
            $output[] = implode("\n", $this->getCatch());
        }
        $output[] = '	});';

        return implode("\n", $output);
    }

    public function setFile(array $file): void
    {
        $this->file = $file;
    }

    public function getFile(): array
    {
        return $this->file;
    }

    public function setFileDir(string $dir): void
    {
        $this->file['file_dir'] = $dir;
    }

    public function getFileDir(): string
    {
        if (isset($this->file['file_dir'])) {
            return $this->file['file_dir'];
        }

        return '';
    }

    public function setFileName(string $name): void
    {
        $this->file['file_name'] = $name;
    }

    public function getFileName(): string
    {
        if (isset($this->file['file_name'])) {
            return $this->file['file_name'];
        }

        return '';
    }

    public function setFileTypes(array $types): void
    {
        $this->file['file_types'] = $types;
    }

    public function getFileTypes(): array
    {
        if (isset($this->file['file_types'])) {
            return $this->file['file_types'];
        }

        return [];
    }

    public function setFileExtensions(array $extensions): void
    {
        $this->file['file_extensions'] = $extensions;
    }

    public function getFileExtensions(): array
    {
        if (isset($this->file['file_extensions'])) {
            return $this->file['file_extensions'];
        }

        return [];
    }

    public function setFileSizeMin(int $size): void
    {
        $this->file['file_size_min'] = $size;
    }

    public function getFileSizeMin(): int
    {
        if (isset($this->file['file_size_min'])) {
            return $this->file['file_size_min'];
        }

        return 0;
    }

    public function setFileSizeMax(int $size): void
    {
        $this->file['file_size_max'] = $size;
    }

    public function getFileSizeMax(): int
    {
        if (isset($this->file['file_size_max'])) {
            return $this->file['file_size_max'];
        }

        return 0;
    }

    public function setFileWidthMin(int $width): void
    {
        $this->file['file_width_min'] = $width;
    }

    public function getFileWidthMin(): int
    {
        if (isset($this->file['file_width_min'])) {
            return $this->file['file_width_min'];
        }

        return 0;
    }

    public function setFileWidthMax(int $width): void
    {
        $this->file['file_width_max'] = $width;
    }

    public function getFileWidthMax(): int
    {
        if (isset($this->file['file_width_max'])) {
            return $this->file['file_width_max'];
        }

        return 0;
    }

    public function setFileHeightMin(int $height): void
    {
        $this->file['file_height_min'] = $height;
    }

    public function getFileHeightMin(): int
    {
        if (isset($this->file['file_height_min'])) {
            return $this->file['file_height_min'];
        }

        return 0;
    }

    public function setFileHeightMax(int $height): void
    {
        $this->file['file_height_max'] = $height;
    }

    public function getFileHeightMax(): int
    {
        if (isset($this->file['file_height_max'])) {
            return $this->file['file_height_max'];
        }

        return 0;
    }

    public function setFileIntValue(string $key, int $value): void
    {
        $this->file[$key] = $value;
    }

    public function getFileIntValue(string $key): int
    {
        if (isset($this->file[$key])) {
            return $this->file[$key];
        }

        return 0;
    }

    public function setFileStringValue(string $key, string $value): void
    {
        $this->file[$key] = $value;
    }

    public function getFileStringValue(string $key): string
    {
        if (isset($this->file[$key])) {
            return $this->file[$key];
        }

        return '';
    }
}
