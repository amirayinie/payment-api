<?php

namespace App\Services\Sms\providers;

use App\Services\Sms\contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KavenegarProvider implements SmsProviderInterface
{
    protected string $apiKey;
    protected string $sender;

    public function __construct()
    {
        $this->apiKey = config('sms.kavenegar.api_key');
        $this->sender = config('sms.kavenegar.sender');
    }

    public function send(string $to, string $message): bool
    {
        try {
            $response = Http::asForm()->post("https://api.kavenegar.com/v1/{$this->apiKey}/sms/send.json", [
                'sender' => $this->sender,
                'receptor' => $to,
                'message'  => $message,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['return']['status']) && $data['return']['status'] == 200) {
                return true;
            }

            Log::warning('Kavenegar send failed', [
                'status' => $data['return']['status'] ?? null,
                'message' => $data['return']['message'] ?? null,
                'response' => $data,
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('Kavenegar exception', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
