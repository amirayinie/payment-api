<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'card_number',
        'cvv2',
        'expire_month',
        'expire_year',
        'balance_toman',
        'status'
    ];

    protected $casts = [
        'balance_toman' => 'integer',
        'expire_month'  => 'integer',
        'expire_year'   => 'integer'
    ];


    public function Account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transactionsFrom()
    {
        return $this->hasMany(Transaction::class, 'from_card_id');
    }

    public function transactionsTo()
    {
        return $this->hasMany(Transaction::class, 'to_card_id');
    }


    //query scopes
    public function scopeActive(Builder $q) :Builder
    {
        return $q->where('status','active');
    }

    public function scopeWithMinBalacnce(Builder $q , int $amount) :Builder
    {
        return $q->where('balance_toman' , '>=' , $amount );
    }

    public function scopeMasked(Builder $q) :Builder
    {
        return $q->selectRaw('id, RIGHT(card_number,4) as last4, balance_toman, status, account_id');
    }


    //accessors

    public function getLast4Attribute() :string
    {
        return substr($this->card_number , -4);
    }

    public function getMaskedAttribute() :string
    {
        return '**** **** **** ' . $this->last4;
    }
}
