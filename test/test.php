<?php

use src\helpers\Push;

require_once __DIR__ . './../src/helper/Push.php';
require_once __DIR__ . './../src/helper/JWT.php';
require_once __DIR__ . './../src/helper/Curl.php';
require_once __DIR__ . './../src/base/BaseObject.php';
require_once __DIR__ . './../src/push_drivers/NotificationPushInterface.php';
require_once __DIR__ . './../src/push_drivers/ApplePushNotificationService.php';
require_once __DIR__ . './../src/push_drivers/FirebaseCloudMessaging.php';

/**
 * Class TestData
 */
final class TestData
{
//    public static $private_key = <<<EOD
//-----BEGIN RSA PRIVATE KEY-----
//MIIBOgIBAAJBANDiE2+Xi/WnO+s120NiiJhNyIButVu6zxqlVzz0wy2j4kQVUC4Z
//RZD80IY+4wIiX2YxKBZKGnd2TtPkcJ/ljkUCAwEAAQJAL151ZeMKHEU2c1qdRKS9
//sTxCcc2pVwoAGVzRccNX16tfmCf8FjxuM3WmLdsPxYoHrwb1LFNxiNk1MXrxjH3R
//6QIhAPB7edmcjH4bhMaJBztcbNE1VRCEi/bisAwiPPMq9/2nAiEA3lyc5+f6DEIJ
//h1y6BWkdVULDSM+jpi1XiV/DevxuijMCIQCAEPGqHsF+4v7Jj+3HAgh9PU6otj2n
//Y79nJtCYmvhoHwIgNDePaS4inApN7omp7WdXyhPZhBmulnGDYvEoGJN66d0CIHra
//I2SvDkQ5CmrzkW5qPaE2oO7BSqAhRZxiYpZFb5CI
//-----END RSA PRIVATE KEY-----
//EOD;

//    public static $public_key = <<<EOD
//-----BEGIN PUBLIC KEY-----
//MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBANDiE2+Xi/WnO+s120NiiJhNyIButVu6
//zxqlVzz0wy2j4kQVUC4ZRZD80IY+4wIiX2YxKBZKGnd2TtPkcJ/ljkUCAwEAAQ==
//-----END PUBLIC KEY-----
//EOD;

//    public static $k = '-----BEGIN PRIVATE KEY-----
//MIGTAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBHkwdwIBAQQg+rHVsRwahA0HS6uw
//p959A6KvF1618Kn7fb43qaiXsiqgCgYIKoZIzj0DAQehRANCAARY10RixEuZBqZq
//Y2dsHFHIUd6Pta7IRA1daZDWYLU1MMYRAjFBSK7l+50FQ3U2ARnQh5nqrWcD41Og
//yEZ25ifY
//-----END PRIVATE KEY-----';

    public static $aps = [
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
    ];
    public static $gcm = [
        'server'      => 'https://fcm.googleapis.com/fcm/send',
        'certificate' => 'AAAA90TMYDc:APA91bHbvN_qoUzPxw1Avug0y5a3GpHqdpR9BuC7IR_1ZldBjXO9hWxiMWjfW3cDYGpsoPg9muuLWoZdqqhdYJWfxlpw7QqAF9OFQz6-HAM-cxu6th9gWi_NKoI5S-TZD765ZD91QAj7',
    ];
}

{
    $aps = TestData::$aps;

    $response = Push::appleDriver(
        "{$aps['app_id']}",
        "{$aps['server']}",
        "{$aps['certificate_path']}",
        "{$aps['certificate_key']}",
        "{$aps['team']}"
    )->sendMessage($token = '9fe13e47c98f6f3a652cf66bbb17f4b8a1ffae77df8144ab91009094baf41fb7', [
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
    $error    = Push::applePushError($response);

    {
        echo __FILE__ . "::" . __LINE__ . PHP_EOL;
        echo str_repeat('=', 120) . PHP_EOL;
        echo "{$aps['server']}" . PHP_EOL;
        echo "{$aps['app_id']}" . PHP_EOL;
        echo "{$token}" . PHP_EOL;
        echo str_repeat('-', 120) . PHP_EOL;
        echo "{$response}" . PHP_EOL;
        echo str_repeat('-', 120) . PHP_EOL;
        echo "{$error}" . PHP_EOL;
        echo str_repeat('=', 120) . PHP_EOL;
    }
}
{
    $gcm = TestData::$gcm;

    $response = Push::googleDriver(
        "{$gcm['server']}",
        "{$gcm['certificate']}"
    )->sendMessage($token = 'fsfXi7LGoQ0:APA91bFY0z18d2Fx9OADvciwnmAfXUi6k709Rtk1kys9An52QAw97LTUD-azvUIv_S8cQtvC6WJ5npwp03ptj19poIAyBWfFX1_5edwyhZb2T9mDeOEy-VR9ur5nnGqhPGB9GHzy6xRt', [
        "token"        => "{$token}",
        "notification" => [
            "title" => "Portugal vs. Denmark",
            "body"  => "great match!",
        ],
        "data"         => [
            "Nick" => "Mario",
            "Room" => "PortugalVSDenmark",
        ],
    ]);
    $error    = Push::googlePushError($response);

    {
        echo __FILE__ . "::" . __LINE__ . PHP_EOL;
        echo str_repeat('=', 120) . PHP_EOL;
        echo "{$gcm['server']}" . PHP_EOL;
        echo "{$token}" . PHP_EOL;
        echo str_repeat('-', 120) . PHP_EOL;
        echo "{$response}" . PHP_EOL;
        echo str_repeat('-', 120) . PHP_EOL;
        echo "{$error}" . PHP_EOL;
        echo str_repeat('=', 120) . PHP_EOL;
    }
}
