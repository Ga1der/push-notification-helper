<?php

namespace src\helpers;

use src\exceptions\CertificateException;
use src\exceptions\FileAccessException;

/**
 * Class JWT
 *
 * @package src\helpers
 */
final class JWT
{
    /**
     * @param        $certificate
     * @param        $certificate_key
     * @param        $certificate_team
     * @param string $algorithm
     *
     * @return string
     * @throws \src\exceptions\CertificateException
     * @throws \src\exceptions\FileAccessException
     */
    public static function sign(
        $certificate,
        $certificate_key,
        $certificate_team,
        $algorithm = 'ES256'
    )
    {
        $jwt_header    = json_encode([
            'alg' => $algorithm,
            'kid' => $certificate_key,
        ]);
        $jwt_payload   = json_encode([
            'iat' => time(),
            'iss' => $certificate_team,
        ]);
        $jwt_header    = self::encode($jwt_header);
        $jwt_payload   = self::encode($jwt_payload);
        $cert          = self::cert($certificate);
        $sing          = self::ssl("{$jwt_header}.{$jwt_payload}", $cert);
        $jwt_signature = self::encode($sing);

        return "{$jwt_header}.{$jwt_payload}.{$jwt_signature}";
    }

    /**
     * @param string $input
     *
     * @return string
     */
    private static function encode($input)
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
     * @throws \src\exceptions\CertificateException
     * @throws \src\exceptions\FileAccessException
     */
    private static function cert($certificate)
    {
        if (is_file($certificate)) $certificate = file_get_contents($certificate);
        if (FALSE === $certificate) throw new FileAccessException(__METHOD__, __LINE__);
        if (empty($certificate)) throw new CertificateException(__METHOD__, __LINE__);

        return "{$certificate}";
    }

    /**
     * @param        $message
     * @param        $certificate
     * @param string $algorithm
     *
     * @return string
     * @throws \src\exceptions\CertificateException
     */
    private static function ssl(
        $message,
        $certificate,
        $algorithm = 'SHA256'
    )
    {
        $signature = '';
        $success   = openssl_sign(
            "{$message}",
            $signature,
            "{$certificate}",
            "{$algorithm}"
        );
        if (FALSE === $success) throw new CertificateException(__METHOD__, __LINE__);

        return $signature;
    }
}
