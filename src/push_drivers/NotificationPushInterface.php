<?php

namespace src\push_drivers;

/**
 * Interface NotificationPushInterface
 *
 * @package common\models\push_drivers
 */
interface NotificationPushInterface
{
    /**
     * @param string $token
     * @param array  $message
     *
     * @return string
     */
    public function sendMessage($token, array $message = []);
}
