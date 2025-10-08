<?php

namespace App\Services\Sms\contracts;

interface SmsProviderInterface
{
    public function send(string $to, string $message): bool;
}
