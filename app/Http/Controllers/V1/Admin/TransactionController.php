<?php

namespace App\Http\Controllers\V1\Admin;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Resources\V1\Admin\TransactionResource;
use App\Http\Requests\V1\Admin\Transaction\UpdateTransactionRequest;

class TransactionController extends BaseAdminController
{
    /**
     * Transaction/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $Transaction = Transaction::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query) =>  $query->where("id", $request->id))
        ->when(isset($request->user_id),fn($query) =>  $query->where("user_id", $request->user_id))
        ->when(isset($request->type),fn($query) =>  $query->where("type", "like",'%'.$request->type.'%'))
        ->when(isset($request->amount),fn($query) =>  $query->where("amount", $request->amount))
        ->with(["user:id,name"])
        ->get();

        return $this->api(TransactionResource::collection($Transaction),__METHOD__);
    }


    public function update(UpdateTransactionRequest $request,Transaction $transaction)
    {
  
        $transaction->updateTransaction($request);
        return $this->api(new TransactionResource($transaction->toArray()),__METHOD__);
    }

}
