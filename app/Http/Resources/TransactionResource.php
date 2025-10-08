<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'transaction_status' => $this->status,
            'origin_number' => $this->fromCreditCard->masked,
            'destination_number' => $this->toCreditCard->masked,
            'amount' => $this->amount_toman,
            'refrence' => $this->refrence,
            'transaction_done_at' => $this->performed_at->toISOString(),
            'transaction_fee' => $this->transactionFee->fee_toman
        ];
    }
}
