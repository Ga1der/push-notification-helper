<?php
declare(strict_types=1);

namespace src\helpers;

use src\push_drivers\NotificationPushAndroid;
use src\push_drivers\NotificationPushIOs;
use Yii;

/**
 * Class PushNotificationHelper
 *
 * @package console\models\helpers
 */
final class Push
{
    /**
     * @return \src\push_drivers\NotificationPushIOs
     */
    private static function applePushDriver() : NotificationPushIOs
    {
        /** @var \src\push_drivers\NotificationPushIOs $apple_push_notification_service */
        $apple_push_notification_service = new NotificationPushIOs([
            'app_id'           => 'com.affiliate.stats',
            'server'           => 'https://api.development.push.apple.com/3/device',
            'certificate_path' => '-----BEGIN PRIVATE KEY-----
MIGTAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBHkwdwIBAQQg+rHVsRwahA0HS6uw
p959A6KvF1618Kn7fb43qaiXsiqgCgYIKoZIzj0DAQehRANCAARY10RixEuZBqZq
Y2dsHFHIUd6Pta7IRA1daZDWYLU1MMYRAjFBSK7l+50FQ3U2ARnQh5nqrWcD41Og
yEZ25ifY
-----END PRIVATE KEY-----',
            'certificate_key'  => 'TR2LS8UDHM',
            'team'             => '4VGVSLGP3J',
        ]);

        return $apple_push_notification_service;
    }

    /**
     * @return \src\push_drivers\NotificationPushAndroid
     */
    private static function googlePushDriver() : NotificationPushAndroid
    {
        /** @var \src\push_drivers\NotificationPushAndroid $google_cloud_messaging */
        $google_cloud_messaging = new NotificationPushAndroid([
            'server'      => 'https://fcm.googleapis.com/fcm/send',
            'certificate' => 'AAAA90TMYDc:APA91bHbvN_qoUzPxw1Avug0y5a3GpHqdpR9BuC7IR_1ZldBjXO9hWxiMWjfW3cDYGpsoPg9muuLWoZdqqhdYJWfxlpw7QqAF9OFQz6-HAM-cxu6th9gWi_NKoI5S-TZD765ZD91QAj7',
        ]);

        return $google_cloud_messaging;
    }

    /**
     * @param $response
     *
     * @return string|null
     */
    protected static function applePushError($response)
    {
        $pattern = '/HTTP\/2 (?P<code>\d+)\s.*\s*(?:apns-id:.*)(?:\s.*\s*(?P<json>{".*":.*}))?/';
        if (preg_match("{$pattern}", "{$response}", $matches)) {
            if (strval(200) !== strval($matches['code'])
                && isset($matches['json'])
                && ($json = json_decode($matches['json']))
                && isset($json->reason)
            ) return strval($json->reason);
        }

        return NULL;
    }

    /**
     * @param string $response
     *
     * @return string|null
     */
    protected static function googlePushError(string $response) : ?string
    {
        $pattern = '/(?P<json>{".*":.*})/';
        if (preg_match("{$pattern}", "{$response}", $matches)) {
            $json = $matches['json'];
            $json = json_decode($json);
            if (!empty($json->results[0]->error)) return strval($json->results[0]->error);
        }

        return NULL;
    }

    /**
     * @param string $token
     * @param array  $message
     *
     * @return array
     * @throws \Exception
     */
    public static function apple(string $token, array $message) : array
    {
        $response = self::applePushDriver()->sendMessage($token, $message);
        $error    = self::applePushError($response);

        return [$response, $error];
    }

    /**
     * @param string $token
     * @param array  $message
     *
     * @return array
     */
    public static function google(string $token, array $message) : array
    {
        $response = self::googlePushDriver()->sendMessage($token, $message);
        $error    = self::googlePushError($response);

        return [$response, $error];
    }
}
