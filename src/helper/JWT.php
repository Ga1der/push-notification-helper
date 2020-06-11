<?php

namespace src\helpers;

use Exception;

/**
 * Class JWT
 *
 * @package src\helpers
 */
final class JWT
{
    /**
     * @param string $certificate
     * @param string $certificate_key
     * @param string $certificate_team
     *
     * @return string
     * @throws \Exception
     */
    final public static function sign(string $certificate, string $certificate_key, string $certificate_team) : string
    {
        $p1       = json_encode([
            'alg' => 'ES256',
            'kid' => $certificate_key,
        ]);
        $p2       = json_encode([
            'iat' => time(),
            'iss' => $certificate_team,
        ]);
        $p1       = self::encode($p1);
        $p2       = self::encode($p2);
        $auth_key = "{$p1}.{$p2}";

        $cert     = self::cert($certificate);
        $sing     = self::ssl($auth_key, $cert);
        $p3       = self::encode($sing);
        $auth_key .= ".{$p3}";

        return $auth_key;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    private static function encode(string $input) : string
    {
        $input = base64_encode($input);
        $input = strtr($input, '+/', '-_');
        $input = str_replace('=', '', $input);

        return $input;
    }

    /**
     * @param string $certificate
     *
     * @return string
     * @throws \Exception
     */
    private static function cert(string $certificate) : string
    {
        if (is_file($certificate)) $certificate = file_get_contents($certificate);
        if (FALSE === $certificate) throw new Exception(__METHOD__, __LINE__);

        return "{$certificate}";
    }

    /**
     * @param string $msg
     * @param string $cert
     *
     * @return string
     * @throws \Exception
     */
    private static function ssl(string $msg, string $cert) : string
    {
        $signature = '';
        $success   = openssl_sign(
            "{$msg}",
            $signature,
            "{$cert}",
            'SHA256'
        );
        if (FALSE === $success) throw new Exception(__METHOD__, __LINE__);

        return $signature;
    }
}
