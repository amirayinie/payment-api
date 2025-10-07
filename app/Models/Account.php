<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'iban',
        'title'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creditCards()
    {
        return $this->hasMany(CreditCard::class);
    }
}
