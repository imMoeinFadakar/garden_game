<?php 

namespace   App\Services;

use App\Models\MarketHistory;
use App\Models\User;
use App\Models\Farms;
use App\Models\Wherehouse;
use Illuminate\Support\Facades\DB;
use Dotenv\Exception\ValidationException;

class MarketService{

    public function sellProduct(User $user,array $data)
    {
        if($user->market_status === 'inactive')
            throw new ValidationException("active your market first");
        

        $farm = $this->findFarm($data);
        if(! $farm)
             throw new  ValidationException('Farm not found.');
        
        $warehouse = $this->hasUserWarehouse($user,$data);

        if(! $warehouse){
             throw new  ValidationException('ware house is not found');
        }


        if($warehouse->amount < $data['amount'])
             throw new  ValidationException('You do not have enough product');


       return  DB::transaction(function()use  ($user, $data, $farm, $warehouse){

            $warehouse->decrement('amount',$data['amount']);


            $benefit = $data['amount'] * $farm->max_token_value;
            $user->increment('token_amount',$benefit);
        
            $this->newMarketHistory($benefit,$user,$farm,$data);

        });


    }

    protected function newMarketHistory($benefit,$user,$farm,array $data)
    {
        return MarketHistory::query()
        ->create([
            'user_id'        => $user->id,
            'product_amount' => $data['amount'],
            'token_amount'   => $benefit,
            'farm_id'        => $farm->id,
        ]);
    }


    public function hasUserWarehouse($user,array $data)
    {
        return Wherehouse::query()
        ->where('user_id',$user->id)
        ->where('farm_id',$data['farm_id'])
        ->first();
    }

    public function findFarm(array $data)
    {
        return Farms::find($data['farm_id']);
    }


}

