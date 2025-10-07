<?php

namespace App\utilities;

use Nette\Utils\Random;

function generateRandomValidCardNumber()
{
    $bins = config('banks.bins');
    $randomBank = $bins[random_int(0, count($bins) - 1)];
    $account = Random::generate(9, '0-9');

    $prefix = $randomBank . $account;
    $num = $prefix . "0";
    $check = validateLuhnRule($num);

    return $prefix . $check;
};

function validateLuhnRule($num)
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

    $check = (10 - ($sum % 10)) % 10;

    return $check;
}
