<?php

namespace src\push_drivers;

/**
 * Interface NotificationPushInterface
 * @package common\models\push_drivers
 */
interface NotificationPushInterface
{
    /**
     * @param string $token
     * @param array $message
     * @return mixed
     */
    public function sendMessage(string $token, array $message = []): string;
}
