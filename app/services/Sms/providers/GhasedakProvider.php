<?php

namespace App\Services\Sms\providers;

use App\Services\Sms\contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GhasedakProvider implements SmsProviderInterface
{
    protected string $apiKey;
    protected string $lineNumber;

    public function __construct()
    {
        $this->apiKey = config('sms.ghasedak.api_key');
        $this->lineNumber = config('sms.ghasedak.lineNumber');
    }

    public function send(string $to, string $message): bool
    {
        try {
            $response = Http::asForm()->post("https://gateway.ghasedak.me/rest/api/v1/WebService/SendSingleSMS", [
                'apiKey' => $this->apiKey,
                'lineNumber' => $this->lineNumber,
                'receptor' => $to,
                'message'  => $message,
            ]);

            $data = $response->json();

            if ($response->successful() && $data['IsSuccess'] && $data['StatusCode'] == 200) {
                return true;
            }

            Log::warning('ghasedak send failed', [
                'status' => $data['IsSuccess'] ?? null,
                'message' => $data['Message'] ?? null,
                'response' => $data,
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('ghasedak exception', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
