<?php

namespace App\Services\Sms;

use App\Services\Sms\contracts\SmsProviderInterface;
use App\Services\Sms\providers\GhasedakProvider;
use App\Services\Sms\providers\KavenegarProvider;
use InvalidArgumentException;

class SmsProviderFactory
{
    public static function make(?string $provider = null) :SmsProviderInterface
    {
        $provider = $provider ?? config('sms.default');

        return match ($provider) {
            'kavenegar' => new KavenegarProvider(),
            'ghasekad' => new GhasedakProvider(),
            default => throw new InvalidArgumentException("SMS provider [$provider] does not exist or is not supported")
        };
    }
}
