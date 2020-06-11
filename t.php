<?php
require_once './src/helper/Push.php';
require_once './src/helper/JWT.php';
require_once './src/helper/Curl.php';
require_once './src/base/BaseObject.php';
require_once './src/push_drivers/NotificationPushInterface.php';
require_once './src/push_drivers/NotificationPushIOs.php';
require_once './src/push_drivers/NotificationPushAndroid.php';

[$response, $error] = \src\helpers\Push::apple('$token', [
    "aps"   => [
        "alert" => [
            "title"          => "Game Request",
            "body"           => "Bob wants to play poker",
            "action-loc-key" => "PLAY",
        ],
        "badge" => 5,
    ],
    "acme1" => "bar",
    "acme2" => ["bang", "whiz"],
]);
var_dump([$response, $error]);
[$response, $error] = \src\helpers\Push::google('$token', [

]);

var_dump([$response, $error]);
