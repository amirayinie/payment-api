<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

use function App\utilities\json;

class AnalyticsController extends Controller
{
    public function topUsers()
    {
    
        $window = (int) request()->get('window', 10);
        $limit  = (int) request()->get('limit', 3);

        $topUsers = Transaction::query()
            ->topUsersByWindowMinutes($window, $limit)
            ->get();

        $result = $topUsers->map(function ($user) {
            $transactions = Transaction::with([
                    'fromCreditCard:id,card_number',
                    'toCreditCard:id,card_number',
                ])
                ->where(function ($q) use ($user) {
                    $q->whereHas('fromCreditCard.account', fn($qq) => $qq->where('user_id', $user->user_id))
                      ->orWhereHas('toCreditCard.account', fn($qq) => $qq->where('user_id', $user->user_id));
                })
                ->where('status', Transaction::STATUS_SUCCEEDED)
                ->orderByDesc('performed_at')
                ->limit(10)
                ->get()
                ->map(fn($t) => [
                    'reference'    => $t->reference,
                    'amount'       => $t->amount_toman,
                    'from_card'    => '****' . substr($t->fromCreditCard->card_number, -4),
                    'to_card'      => '****' . substr($t->toCreditCard->card_number, -4),
                    'performed_at' => $t->performed_at,
                ]);

            return [
                'user' => [
                    'id'   => $user->user_id,
                    'name' => $user->name,
                    'transaction_count' => $user->tx_count,
                ],
                'last_transactions' => $transactions,
            ];
        });

        return json([
            'window_minutes' => $window,
            'top_users'      => $result,
        ]);
    }
    
}
