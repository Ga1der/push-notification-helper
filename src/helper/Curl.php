<?php

namespace src\helpers;

use src\exceptions\ServiceUnavailableException;

/**
 * Class Curl
 *
 * @package src\helpers
 */
final class Curl
{
    /**
     * @param array $curl_setopt_array
     *
     * @return string
     * @throws \src\exceptions\ServiceUnavailableException
     */
    public static function exec(array $curl_setopt_array)
    {
        $ch = curl_init();
        curl_setopt_array($ch, $curl_setopt_array);
        $result = curl_exec($ch);
        static::log($ch, $curl_setopt_array, $result);
        curl_close($ch);
        if (FALSE === $result) throw new ServiceUnavailableException(__METHOD__, __LINE__);

        return "{$result}";
    }

    /**
     * @param       $ch
     * @param array $curl_setopt_array
     * @param       $result
     */
    protected static function log($ch, array $curl_setopt_array, $result)
    {
        $log = self::curlInfo($ch, $curl_setopt_array, $result);
        $log = var_export($log, TRUE);
        $log = str_replace(PHP_EOL, NULL, $log);
        $log_file    = self::logFileName();
        error_log(
            "\n{$log};\n",
            3,
            "{$log_file}"
        );
    }

    /**
     * @param       $ch
     * @param array $curl_setopt_array
     * @param       $result
     *
     * @return array
     */
    protected static function curlInfo($ch, array $curl_setopt_array, $result)
    {
        return [
            'curl_errno'        => curl_errno($ch),
            'curl_error'        => curl_error($ch),
            'curl_setopt_array' => self::curlOptions($curl_setopt_array),
            'curl_response'     => $result,
            'curl_getinfo'      => curl_getinfo($ch),
        ];
    }

    /**
     * @return string
     */
    protected static function logFileName()
    {
        $date = date('Y-m-d');
        $user = posix_geteuid();
        $user = posix_getpwuid($user);
        $user = $user['name'];

        return "/tmp/{$date}_{$user}.log";
    }

    /**
     * @param array $curl_setopt_array
     *
     * @return array|false
     */
    protected static function curlOptions(array $curl_setopt_array)
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
