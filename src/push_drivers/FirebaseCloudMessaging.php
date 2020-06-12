<?php

namespace src\push_drivers;

use src\base\BaseObject;
use src\exceptions\InvalidConfigException;
use src\exceptions\ServiceUnavailableException;
use src\helpers\Curl;

/**
 * Class FirebaseCloudMessaging
 *
 * @package src\push_drivers
 */
final class FirebaseCloudMessaging extends BaseObject implements NotificationPushInterface
{
    public $server;
    public $certificate;

    /**
     * @throws \src\exceptions\InvalidConfigException
     */
    public function init()
    {
        if (empty($this->server)) throw new InvalidConfigException(__METHOD__, __LINE__);
        if (empty($this->certificate)) throw new InvalidConfigException(__METHOD__, __LINE__);
    }

    /**
     * @param string $token
     * @param array  $message
     *
     * @return string
     * @throws \src\exceptions\ServiceUnavailableException
     */
    public function sendMessage($token, array $message = [])
    {
        $url           = $this->server;
        $authorization = $this->certificate;

        /**
         * @see https://firebase.google.com/docs/cloud-messaging/concept-options#notifications
         */
        $message['to']    = $token;
        $message['token'] = $token;

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
     * @throws \src\exceptions\ServiceUnavailableException
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
        if (FALSE === $response) throw new ServiceUnavailableException(__METHOD__, __LINE__);

        return $response;
    }
}
