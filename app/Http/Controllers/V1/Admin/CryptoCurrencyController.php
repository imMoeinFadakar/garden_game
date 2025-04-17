<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\CryptoCurrency\CryptoCurrencyRequest;
use App\Http\Resources\V1\Admin\CryptoCurrencyResource;
use App\Models\cryptocurrency;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CryptoCurrencyController extends BaseAdminController
{

    public function index(Request $request)
    {
        $cryptocurrency = cryptocurrency::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query)=> $query->where("id",$request->id))
        ->when(isset($request->tx_hash), fn($query)=> $query->where("tx_hash","LIKE",'%'.$request->tx_hash.'%'))
        ->when(isset($request->status), fn($query)=> $query->where("status","LIKE",'%'.$request->status.'%'))
        ->with(["user:id,username,user_status"])
        ->get();

        return $this->api(CryptoCurrencyResource::collection($cryptocurrency),__METHOD__);
    }

    public function show(cryptocurrency $cryptocurrency)
    {
        $cryptocurrency->load(["user:id,username,user_status"]);
        return $this->api(new CryptoCurrencyResource($cryptocurrency->toArray()),__METHOD__);
    }







    public function transactionRequest(CryptoCurrencyRequest $transaction)
    {   

        try{
            $response = Http::get("https://apilist.tronscanapi.com/api/transaction-info", [
                'hash' => $transaction
            ]);
    
            return $response->json();

        }catch(Exception $e){

            throw new HttpResponseException(response()->json([
                "message" => "connection failed",
            ]));

        }
     
    }


    public function getNeededInformation(CryptoCurrencyRequest $request)
    {
        // $transactionData = $this->transactionRequest($request->hash);
        // $transactionData[""]



    }




}
