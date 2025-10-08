<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\services\payment\TransferService;
use Illuminate\Http\Request;

use function App\utilities\json;

class TransferController extends Controller
{
    public function __construct(protected TransferService $transferService) {}

    public function transfer(TransferRequest $request) {
        $validated = $request->dto();

        $result = $this->transferService->handle($validated);

        return json();
    }
}
