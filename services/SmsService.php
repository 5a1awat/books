<?php

declare(strict_types=1);

namespace app\services;

use app\contracts\SmsInterface;
use Yii;

class SmsService implements SmsInterface
{
    public function send(string $phoneNumber, string $message): bool
    {
        $sender = 'INFORM'; //  имя отправителя из списка https://smspilot.ru/my-sender.php

        $apikey = Yii::$app->params['smsServiceToken'];

        $url = 'https://smspilot.ru/api.php'
            .'?send='.urlencode($message)
            .'&to='.urlencode($phoneNumber)
            .'&from='.$sender
            .'&apikey='.$apikey
            .'&format=json';

        $json = json_decode(file_get_contents($url));

        if (!isset($json->error)) {
            return true;
        }

        return false;
    }
}