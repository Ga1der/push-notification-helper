<?php

namespace src\push_drivers;

use Exception;
use src\base\BaseObject;
use src\helpers\Curl;
use src\helpers\JWT;
use Yii;

/**
 * Class ApplePushNotificationService
 *
 * @package src\push_drivers
 */
final class ApplePushNotificationService extends BaseObject implements NotificationPushInterface
{
    protected $server           = 'https://api.development.push.apple.com/3/device';
    protected $certificate_path = 'PushNotificationKeyiOS.p8';
    protected $certificate_key  = 'ASN64B8YH6';
    protected $team             = '4VGVSLGP3J';
    protected $app_id           = 'com.affiliate.stats';

    /**
     * @param string $token
     * @param array  $message
     *
     * @return string
     * @throws \Exception
     */
    public function sendMessage($token, array $message = [])
    {
        $url = vsprintf('%s/%s', [
            rtrim($this->server, '/'),
            trim($token, '/'),
        ]);

        $authorization = JWT::sign(
            $this->certificate_path,
            $this->certificate_key,
            $this->team
        );

        $manual = 'https://developer.apple.com/library/archive/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/CreatingtheNotificationPayload.html#//apple_ref/doc/uid/TP40008194-CH10-SW1';
        if (!isset($message['aps'])) throw new Exception("'aps' not found! More: {$manual}", __LINE__);
        if (!is_array($message['aps'])) throw new Exception("'aps' is invalid! More: {$manual}", __LINE__);
        if (isset($message['aps']['alert'])) {
            if (is_string($message['aps']['alert']) && is_array($message['aps']['alert'])) throw new Exception("'aps -> alert' is invalid! More: {$manual}", __LINE__);
        }

        /**
         * @see https://developer.apple.com/library/archive/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/CreatingtheNotificationPayload.html#//apple_ref/doc/uid/TP40008194-CH10-SW1
         * @see https://developer.apple.com/library/archive/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/CommunicatingwithAPNs.html
         */
        $result = self::curl($url, [
            "apns-topic: {$this->app_id}",
            "Authorization: bearer {$authorization}",
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
    private static function curl($url, array $headers, array $body)
    {
        $response = Curl::exec([
            CURLOPT_PORT           => 443,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_2_0,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_HEADER         => 1,
            // ...
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
