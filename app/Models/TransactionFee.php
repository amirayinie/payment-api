<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionFee extends Model
{
        protected $fillable = [
        'transation_id',
        'fee_toman'
    ];
}
