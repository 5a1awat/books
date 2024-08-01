<?php

namespace app\contracts;

interface SmsInterface
{
    public function send(string $phoneNumber, string $message): bool;
}