<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $provider;

    public function __construct(?string $provider = null)
    {
        $this->provider = SmsProviderFactory::make($provider);
    }

    public function send(string $to, string $message): bool
    {
        Log::info("sending SMS message to [$to]", ['provider' => get_class($this->provider)]);

        return $this->provider->send($to, $message);
    }
}
