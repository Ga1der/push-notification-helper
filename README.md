# Easy way to push notifications

## Apple
* [Apple Payload Format](https://developer.apple.com/library/archive/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/CreatingtheNotificationPayload.html#//apple_ref/doc/uid/TP40008194-CH10-SW1)
* [Apple Errors](https://developer.apple.com/library/archive/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/CommunicatingwithAPNs.html#//apple_ref/doc/uid/TP40008194-CH11-SW17)

```php
/** @var \src\push_drivers\ApplePushNotificationService $apple_push_notification_service */
$apple_push_notification_service = new ApplePushNotificationService([
    'app_id'           => "{$aps['app_id']}",
    'server'           => "{$aps['server']}",
    'certificate_path' => "{$aps['certificate']}",
    'certificate_key'  => "{$aps['certificate_key']}",
    'team'             => "{$aps['team']}",
]);

$response = $apple_push_notification_service->sendMessage("{$device['token']}", [
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

```php
/* error message (if any) */
$error = Push::apple("{$aps['certificate_path']}", "{$aps['certificate_key']}", "{$aps['team']}", "{$aps['server']}", "{$aps['app_id']}", "{$device['token']}", [
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
```

## Google
* [Google Payload Format](https://firebase.google.com/docs/cloud-messaging/concept-options#notifications)
* [Google Errors](https://firebase.google.com/docs/cloud-messaging/http-server-ref#error-codes)

```php
/** @var \src\push_drivers\FirebaseCloudMessaging $google_cloud_messaging */
$google_cloud_messaging = new FirebaseCloudMessaging([
    'server' => "{$gcm['server']}",
    'certificate' => "{$gcm['certificate']}",
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

```php
/* error message (if any) */
$error = Push::google("{$gcm['certificate']}", "{$gcm['server']}", "{$device['token']}", [
    "notification" => [
        "title" => "Portugal vs. Denmark",
        "body"  => "great match!",
    ],
    "data"         => [
        "Nick" => "Mario",
        "Room" => "PortugalVSDenmark",
    ],
]);
```
