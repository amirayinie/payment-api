<?php

namespace App\Services\payment;

use App\Models\CreditCard;
use App\Models\Transaction;
use App\Models\TransactionFee;
use App\Services\Sms\SmsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function App\utilities\json;

class TransferService
{
    protected  int $feeAmount = 500;

    public function handle(array $transferData)
    {
        $fromCardNumber = $transferData['from_card'];
        $toCanrdNumber  = $transferData['to_card'];
        $amount         = (int)$transferData['amount'];
        $smsService = new SmsService();

        $fromCard = CreditCard::where('card_number', $fromCardNumber)->lockForUpdate()->first();
        $toCard = CreditCard::where('card_number', $toCanrdNumber)->lockForUpdate()->first();

        if (!$fromCard || !$toCard) {
            throw new Exception('کارت مبدا یا مقصد یافت نشد');
        }

        if ($fromCard->status !== 'active' || $toCard->status !== 'active') {
            throw new Exception('کارت مبدا یا مقصد غیرفعال است.');
        }

        $totalDebit = $this->feeAmount + $amount;

        if ($fromCard->balance_toman < $totalDebit) {
            throw new Exception('موجودی کارت مبدا برای انجام تراکنش کافی نیست.');
        }

        return DB::transaction(function () use ($fromCard, $toCard, $amount, $totalDebit, $smsService) {

            $fromCard->decrement('balance_toman', $totalDebit);
            $toCard->increment('balance_toman', $amount);

            $refrence = 'TRX' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(5));

            $transaction = Transaction::create([
                'from_card_id' => $fromCard->id,
                'to_card_id'   => $toCard->id,
                'amount_toman' => $amount,
                'status'       => 'succeeded',
                'refrence'     => $refrence,
                'performed_at' => Carbon::now()
            ]);

            $transactionFee = TransactionFee::create([
                'transaction_id' => $transaction->id,
                'fee_toman'      => $this->feeAmount
            ]);


            $smsService->send($fromCard->account->user->phone_number, "مبلف $amount تومن از حساب شما برداشت شد");
            $smsService->send($toCard->account->user->phone_number, "مبلغ $amount تومن به حساب شما واریز شد");

            return $transaction;
        });
    }
}
