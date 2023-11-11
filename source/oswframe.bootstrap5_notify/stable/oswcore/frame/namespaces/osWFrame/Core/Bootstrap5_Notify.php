<?php declare(strict_types=0);

/**
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace osWFrame\Core;

class Bootstrap5_Notify
{
    use BaseStaticTrait;
    use BaseTemplateBridgeTrait;

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

    public function __construct(
        object $Template
    ) {
        $this->setTemplate($Template);
    }

    public function sendNotify(
        string $msg,
        string $type = 'success',
        array $_options = [],
        bool $addfunction = true
    ): bool {
        switch ($type) {
            case 'info':
                $type = 'info';

                break;
            case 'warning':
                $type = 'warning';

                break;
            case 'error':
            case 'danger':
                $type = 'danger';

                break;
            case 'success':
            default:
                $type = 'success';

                break;
        }
        $options = [];
        $options['offset']['x'] = 20;
        $options['offset']['y'] = 60;
        $options['placement']['from'] = 'top';
        $options['placement']['align'] = 'center';
        $options['delay'] = 2500;
        $options['mouse_over'] = 'pause';
        $options['type'] = $type;
        $options['z_index'] = 1331;
        $options['template'] = '<div data-notify="container" class="alert alert-{0} col-6 alert-dismissible fade show" role="alert"><span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a><div class="float-right"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        $options = array_merge_recursive($options, $_options);
        $c = '';
        if ($addfunction === true) {
            $c .= '
$(function() {';
        }
        $c .= '
	$.notify({
		message: \'' . addslashes($msg) . '\'
	},
		' . json_encode($options) . '
	);';
        if ($addfunction === true) {
            $c .= '
});';
        }
        $this->addTemplateJSCode('head', $c);

        return true;
    }

    public function getNotifyCode(
        string $msg,
        string $type = 'success',
        array $_options = [],
        bool $addfunction = true
    ): string {
        switch ($type) {
            case 'info':
                $type = 'info';

                break;
            case 'warning':
                $type = 'warning';

                break;
            case 'error':
            case 'danger':
                $type = 'danger';

                break;
            case 'success':
            default:
                $type = 'success';

                break;
        }
        $options = [];
        $options['offset']['x'] = 20;
        $options['offset']['y'] = 60;
        $options['placement']['from'] = 'top';
        $options['placement']['align'] = 'center';
        $options['delay'] = 2500;
        $options['mouse_over'] = 'pause';
        $options['type'] = $type;
        $options['z_index'] = 1331;
        $options['template'] = '<div data-notify="container" class="alert alert-{0} col-6 alert-dismissible fade show" role="alert"><span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a><div class="float-right"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        $options = array_merge_recursive($options, $_options);
        $c = '';
        if ($addfunction === true) {
            $c .= '
$(function() {';
        }
        $c .= '
	$.notify({
		message: \'' . addslashes($msg) . '\'
	},
		' . json_encode($options) . '
	);';
        if ($addfunction === true) {
            $c .= '
});';
        }

        return $c;
    }
}
