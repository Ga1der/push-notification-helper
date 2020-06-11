<?php

namespace src\push_drivers;

use src\base\BaseObject;
use src\helpers\Curl;

/**
 * Class NotificationPushAndroid
 *
 * @package common\models\push_drivers
 */
final class NotificationPushAndroid extends BaseObject implements NotificationPushInterface
{
    public $server      = 'https://android.googleapis.com/gcm/send';
    public $certificate = 'AAAA90TMYDc:APA91bHbvN_qoUzPxw1Avug0y5a3GpHqdpR9BuC7IR_1ZldBjXO9hWxiMWjfW3cDYGpsoPg9muuLWoZdqqhdYJWfxlpw7QqAF9OFQz6-HAM-cxu6th9gWi_NKoI5S-TZD765ZD91QAj7';

    /**
     * @param string $token
     * @param array $message
     * @return string
     */
    public function sendMessage(string $token, array $message = []): string
    {
        $url = $this->server;
        $authorization = $this->certificate;

        /**
         *
         */
        $result = self::curl($url, [
            "Authorization: key={$authorization}",
            'Content-Type: application/json',
        ], [
            'to'       => $token,
            'priority' => 'high',
            'data'     => [
                'icon'  => 'icon',
                'sound' => 'default',
                'info'  => $message,
            ],
        ]);

        return "{$result}";
    }

    /**
     * @param string $url
     * @param array  $headers
     * @param array  $body
     *
     * @return string
     */
    final private static function curl(string $url, array $headers, array $body): string
    {
        return Curl::exec([
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POST           => TRUE,
            CURLOPT_POSTFIELDS     => json_encode($body),
        ]);
    }
}
