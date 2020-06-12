# push-notification-helper

###### [Apple Payload Format](https://developer.apple.com/library/archive/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/CreatingtheNotificationPayload.html#//apple_ref/doc/uid/TP40008194-CH10-SW1)
```php
/** @var \src\push_drivers\FirebaseCloudMessaging $google_cloud_messaging */
$google_cloud_messaging = new FirebaseCloudMessaging([
    'server'      => "{$gcm['server']}",
    'certificate' => "{$gcm['certificate']}",
]);

$response = $google_cloud_messaging->sendMessage("{$device['token']}", [
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

/* extract error message (if any) */
$error = Push::applePushError($response);
```

###### [Google Payload Format](https://firebase.google.com/docs/cloud-messaging/concept-options#notifications)
```php
/** @var \src\push_drivers\FirebaseCloudMessaging $google_cloud_messaging */
$google_cloud_messaging = new FirebaseCloudMessaging([
    "server" => "{$gcm['server']}",
    "certificate" => "{$gcm['certificate']}",
]);

$response = $google_cloud_messaging->sendMessage("{$device['token']}", [
    "notification" => [
      "title" => "Portugal vs. Denmark",
      "body"  => "great match!"
    ],
    "data"         => [
      "Nick" => "Mario",
      "Room" => "PortugalVSDenmark"
    ]
]);

/* extract error message (if any) */
$error = Push::googlePushError($response);
```
