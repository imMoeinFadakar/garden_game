<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\CryptoCurrency\CryptoCurrencyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CryptoCurrencyController extends Controller
{

    public function transactionRequest(CryptoCurrencyRequest  $transaction)
    {
        $response = Http::get("https://apilist.tronscanapi.com/api/transaction-info", [
            'hash' => $transaction->hash,
        ]);

        return $response->json();
    }



}
