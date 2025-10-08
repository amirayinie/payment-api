<?php

namespace App\utilities;

use Nette\Utils\Random;

if (!function_exists("generateRandomValidCardNumber")) {
    function generateRandomValidCardNumber()
    {
        $bins = config('banks.bins');
        $randomBank = $bins[random_int(0, count($bins) - 1)];
        $account = Random::generate(9, '0-9');

        $prefix = $randomBank . $account;
        $num = $prefix . "0";
        $check = validateLuhnRule($num);

        return $prefix . $check['safe_checksum_number'];
    };
}

if (! function_exists("validateLuhnRule")) {
    function validateLuhnRule($num): array
    {
        $digits = str_split($num);
        $len = count($digits);
        $sum = 0;

        for ($i = $len - 1; $i >= 0; $i--) {
            $positionFromRight = $len - $i;
            if (! ($positionFromRight % 2)) {

                $digits[$i] = $digits[$i] * 2;

                if ($digits[$i] > 9) {
                    $digits[$i] = $digits[$i] - 9;
                }
            }
            $sum += $digits[$i];
        }

        $validation = !($sum % 10);

        $check = (10 - ($sum % 10)) % 10;

        return ['is_account_valid' => $validation, 'safe_checksum_number' => $check];
    }
}

if (! function_exists("json")) {
    function json($data = [], $message = 'ok', $httpStatus = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'status' => (int) ($httpStatus / 100) === 2 ? 'success' : 'fail',
            'meta' => [
                'message' => $message,
            ],
            'data' => $data ?? [],
        ];

        return response()->json($response, $httpStatus);
    }
}

if (!function_exists('toEnglishDigits')) {
    function toEnglishDigits(?string $input)
    {
        $persian = config('banks.persian_digits');
        $arabic = config('banks.arabic_digits');
        $english = config('banks.english_digits');

        $output = str_replace($persian, $english, $input);
        $output = str_replace($arabic, $english, $output);

        return trim($output);
    }
}

if (!function_exists('cleanCardNumber')) {
    function cleanCardNumber($input): string
    {
        $card = toEnglishDigits($input);
        return preg_replace('/[^0-9]/', '', $card);
    }
}

if (!function_exists('isCardValid')) {
    function isCardValid(string $cardNumber): bool
    {
        $cleanCardNumber = cleanCardNumber($cardNumber);
        $bankBin = substr($cleanCardNumber, 0, 6);

        if (!in_array($bankBin, config('banks.bins'))) {
            return false;
        }

        $validatedAccount = validateLuhnRule($cleanCardNumber);

        if (!$validatedAccount['is_account_valid']) {
            return false;
        }

        return true;
    }
}
