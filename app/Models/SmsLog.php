<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'provider',
        'to',
        'body',
        'status',
        'external_id',
        'payload'
    ];

    public function scopeByProvider(Builder $q, string $provider)
    {
        return $q->where('provider', $provider);
    }

    public function scopeRecent(Builder $q, int $minutes = 60)
    {
        return $q->where('created_at', '>=', now()->subMinutes($minutes));
    }
}
