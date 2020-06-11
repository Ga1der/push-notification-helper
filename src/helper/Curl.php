<?php

namespace src\helpers;

use src\base\BaseObject;
use Yii;

/**
 * Class NotificationPushBase
 *
 * @package common\models\push_drivers
 */
final class Curl
{
    /**
     * @param array $curl_setopt_array
     *
     * @return string
     */
    public static function exec(array $curl_setopt_array): string
    {
        $ch = curl_init();
        curl_setopt_array($ch, $curl_setopt_array);
        $result = curl_exec($ch);
        static::curlLog($ch, $curl_setopt_array, $result);
        curl_close($ch);

        return "{$result}";
    }

    /**
     * @param       $ch
     * @param array $curl_setopt_array
     * @param       $result
     */
    protected static function curlLog($ch, array $curl_setopt_array, $result)
    {
        $log = [
            'curl_errno'        => curl_errno($ch),
            'curl_error'        => curl_error($ch),
            'curl_setopt_array' => self::curlOptsArray($curl_setopt_array),
            'curl_response'     => $result,
            'curl_getinfo'      => curl_getinfo($ch),
        ];

        $date = date('Y-m-d');
        $user = posix_geteuid();
        $user = posix_getpwuid($user);
        $user = $user['name'];
        $log  = var_export($log, TRUE);
        $log  = str_replace(PHP_EOL, NULL, $log);
        error_log("\n{$log};\n", 3, "/tmp/{$date}_{$user}.log");
    }

    /**
     * @param array $curl_setopt_array
     *
     * @return array|false
     */
    protected static function curlOptsArray(array $curl_setopt_array)
    {
        $constants = get_defined_constants(TRUE);
        $constants = $constants['curl'];
        $constants = array_filter($constants, function ($key) {
            return 0 === strpos($key, 'CURLOPT_');
        }, ARRAY_FILTER_USE_KEY);

        $keys   = array_keys($curl_setopt_array);
        $keys   = array_map(function ($key) use ($constants) {
            return array_search($key, $constants, TRUE);
        }, $keys);
        $values = array_values($curl_setopt_array);

        return array_combine($keys, $values);
    }
}
