<?php

use yii\helpers\ArrayHelper;

$params = [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'bsVersion' => '5.x', // this will set globally `bsVersion` to Bootstrap 5.x for all Krajee Extensions
    'smsServiceToken' => 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ'
];

if (is_file(__DIR__.'/params-local.php')) {
    $params = ArrayHelper::merge(
        $params,
        require(__DIR__.'/params-local.php')
    );
}

return $params;
