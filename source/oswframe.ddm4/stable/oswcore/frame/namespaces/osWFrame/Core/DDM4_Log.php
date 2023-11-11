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

class DDM4_Log
{
    use BaseStaticTrait;
    use BaseConnectionTrait;

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

    protected static array $elements = [];

    public function __construct()
    {
    }

    public static function addValue(
        string $group,
        string $key,
        string $module,
        string|int $value_old,
        string|int $value_new,
        int $user_id_old = 0,
        int $time_old = 0,
        int $user_id_new = 0,
        int $time_new = 0
    ): bool {
        if (!isset(self::$elements[$group])) {
            self::$elements[$group] = [];
        }

        $value_old = (string)$value_old;
        $value_new = (string)$value_new;

        self::$elements[$group][$key] = [
            'key' => $key,
            'module' => $module,
            'value_old' => $value_old,
            'value_new' => $value_new,
            'user_id_old' => $user_id_old,
            'time_old' => $time_old,
            'user_id_new' => $user_id_new,
            'time_new' => $time_new,
        ];

        return true;
    }

    public static function getValue(string $group, string $key): ?string
    {
        if (!isset(self::$elements[$group])) {
            return null;
        }
        if (!isset(self::$elements[$group][$key])) {
            return null;
        }

        return self::$elements[$group][$key];
    }

    public static function getValues(string $group): ?array
    {
        if (!isset(self::$elements[$group])) {
            return null;
        }

        return self::$elements[$group];
    }

    public static function writeValues(string $group, string $index, string|int $value, string $connection = ''): ?bool
    {
        if (!isset(self::$elements[$group])) {
            return null;
        }

        $value = (string)$value;

        foreach (self::$elements[$group] as $key => $values) {
            $QsaveData = self::getConnection($connection);
            $QsaveData->prepare(
                'INSERT INTO :table_ddm4_log: (log_group, name_index, value_index, log_key, log_module, log_value_new, log_value_old, log_value_user_id_new, log_value_user_id_old, log_value_time_new, log_value_time_old) VALUES (:log_group:, :name_index:, :value_index:, :log_key:, :log_module:, :log_value_new:, :log_value_old:, :log_value_user_id_new:, :log_value_user_id_old:, :log_value_time_new:, :log_value_time_old:)'
            );
            $QsaveData->bindTable(':table_ddm4_log:', 'ddm4_log');
            $QsaveData->bindString(':log_group:', $group);
            $QsaveData->bindString(':name_index:', $index);
            $QsaveData->bindString(':value_index:', $value);
            $QsaveData->bindString(':log_key:', $values['key']);
            $QsaveData->bindString(':log_module:', $values['module']);
            $QsaveData->bindString(':log_value_new:', $values['value_new']);
            $QsaveData->bindString(':log_value_old:', $values['value_old']);
            $QsaveData->bindInt(':log_value_user_id_new:', $values['user_id_new']);
            $QsaveData->bindInt(':log_value_user_id_old:', $values['user_id_old']);
            $QsaveData->bindInt(':log_value_time_new:', $values['time_new']);
            $QsaveData->bindInt(':log_value_time_old:', $values['time_old']);
            $QsaveData->execute();
        }
        self::clearValues($group);

        return true;
    }

    public static function clearValues(string $group): ?bool
    {
        if (!isset(self::$elements[$group])) {
            return null;
        }
        self::$elements[$group] = [];

        return true;
    }
}
