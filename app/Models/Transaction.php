<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
        'from_card_id',
        'to_card_id',
        'amount_toman',
        'status',
        'refrence',
        'performed_at'
    ];

    public const STATUS_INITIATED = 'initiated';
    public const STATUS_SUCCEEDED = 'succeeded';
    public const STATUS_FAILED    =  'failed';


    public function fromCreditCard()
    {
        return $this->belongsTo(CreditCard::class, 'from_card_id');
    }

    public function toCreditCard()
    {
        return $this->belongsTo(CreditCard::class, 'to_card_id');
    }

    public function transactionFee()
    {
        return $this->hasOne(transactionFee::class);
    }

    //scopes

    public function scopeSucceeded(Builder $q): Builder
    {
        return $q->where('status', 'Succeeded');
    }

    public function scopeInWindowsMinute(Builder $q, int $minutes): Builder
    {
        return $q->where('performed_at', '>=', now()->subMinutes($minutes));
    }


    public function scopeTopUsersByWindowMinutes(Builder $q, int $minutes, int $limit = 3): Builder
    {
        return $q->selectRaw('users.id as user_id, users.name, count(transactions.id) as tx_count')
            ->join('credit_cards as from_cards', 'transactions.from_card_id', '=', 'from_cards.id')
            ->join('accounts', 'from_cards.account_id', '=', 'accounts.id')
            ->join('users', 'accounts.user_id', '=', 'users.id')
            ->where('transactions.status', self::STATUS_SUCCEEDED)
            ->where('transactions.performed_at', '>=', now()->subMinutes($minutes))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('tx_count')
            ->limit($limit);
    }
}
