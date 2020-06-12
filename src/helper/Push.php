<?php

namespace src\helpers;

use src\push_drivers\ApplePushNotificationService;
use src\push_drivers\FirebaseCloudMessaging;
use Yii;

/**
 * Class PushNotificationHelper
 *
 * @package console\models\helpers
 */
final class Push
{
    /**
     * @param $app_id
     * @param $server
     * @param $certificate
     * @param $certificate_key
     * @param $team
     *
     * @return \src\push_drivers\ApplePushNotificationService
     */
    public static function appleDriver(
        $app_id,
        $server,
        $certificate,
        $certificate_key,
        $team
    )
    {
        static $cache;
        $key = serialize(func_get_args());
        if (isset($cache[$key])) return $cache[$key];

        /** @var \src\push_drivers\ApplePushNotificationService $apple_push_notification_service */
        $apple_push_notification_service = new ApplePushNotificationService([
            'app_id'           => $app_id,
            'server'           => $server,
            'certificate_path' => $certificate,
            'certificate_key'  => $certificate_key,
            'team'             => $team,
        ]);

        return $cache[$key] = $apple_push_notification_service;
    }

    /**
     * @param $response
     *
     * @return string|null
     */
    public static function applePushError($response)
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
     * @param $server
     * @param $certificate
     *
     * @return mixed|\src\push_drivers\FirebaseCloudMessaging
     */
    public static function googleDriver(
        $server,
        $certificate
    )
    {
        static $cache;
        $key = serialize(func_get_args());
        if (isset($cache[$key])) return $cache[$key];

        /** @var \src\push_drivers\FirebaseCloudMessaging $google_cloud_messaging */
        $google_cloud_messaging = new FirebaseCloudMessaging([
            'server'      => $server,
            'certificate' => $certificate,
        ]);

        return $cache[$key] = $google_cloud_messaging;
    }

    /**
     * @param string $response
     *
     * @return string|null
     */
    public static function googlePushError($response)
    {
        $pattern = '/(?P<json>{".*":.*})/';
        if (preg_match("{$pattern}", "{$response}", $matches)) {
            $json = $matches['json'];
            $json = json_decode($json);
            if (!empty($json->results[0]->error)) return strval($json->results[0]->error);
        }

        return NULL;
    }
}
