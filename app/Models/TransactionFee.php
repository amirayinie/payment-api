<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PDO;

class TransactionFee extends Model
{
    protected $fillable = [
        'transaction_id',
        'fee_toman'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
