<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\TransactionResource;
use App\services\payment\TransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function App\utilities\json;

class TransferController extends Controller
{
    public function __construct(protected TransferService $transferService) {}

    public function transfer(TransferRequest $request)
    {
        $validated = $request->dto();

        try {
            $transaction = $this->transferService->handle($validated);

            return json(new TransactionResource($transaction));
        } catch (\Throwable $e) {
            Log::error('transfer_failed', [
                'error' => $e->getMessage(),
                'tracc' => $e->getTraceAsString()
            ]);

            return json('error => transaction failed', $e->getMessage(), 422);
        }
    }
}
