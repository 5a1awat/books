<?php

declare(strict_types=1);

use app\contracts\SmsInterface;
use app\services\SmsService;

return [
    'definitions' => [
        SmsInterface::class => SmsService::class,
    ],
];