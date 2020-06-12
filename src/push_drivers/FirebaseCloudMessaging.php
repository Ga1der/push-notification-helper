<?php

namespace src\push_drivers;

use Exception;
use src\base\BaseObject;
use src\helpers\Curl;

/**
 * Class FirebaseCloudMessaging
 *
 * @package src\push_drivers
 */
final class FirebaseCloudMessaging extends BaseObject implements NotificationPushInterface
{
    public $server      = 'https://android.googleapis.com/gcm/send';
    public $certificate = 'AAAA90TMYDc:APA91bHbvN_qoUzPxw1Avug0y5a3GpHqdpR9BuC7IR_1ZldBjXO9hWxiMWjfW3cDYGpsoPg9muuLWoZdqqhdYJWfxlpw7QqAF9OFQz6-HAM-cxu6th9gWi_NKoI5S-TZD765ZD91QAj7';

    /**
     * @param string $token
     * @param array  $message
     *
     * @return string
     * @throws \Exception
     */
    public function sendMessage($token, array $message = [])
    {
        $url           = $this->server;
        $authorization = $this->certificate;

        /**
         *
         */
        $message['to'] = $token;

        $result = self::curl($url, [
            "Authorization: key={$authorization}",
            'Content-Type: application/json',
        ], $message);

        return "{$result}";
    }

    /**
     * @param string $url
     * @param array  $headers
     * @param array  $body
     *
     * @return string
     * @throws \Exception
     */
    final private static function curl($url, array $headers, array $body)
    {
        $response = Curl::exec([
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POST           => TRUE,
            CURLOPT_POSTFIELDS     => json_encode($body),
        ]);
        if (FALSE === $response) throw new Exception(__METHOD__, __LINE__);

        return $response;
    }
}