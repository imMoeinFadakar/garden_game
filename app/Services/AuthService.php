<?php 


namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class AuthService{

    public function findOrCreateUser(array $data)
    {
         return User::query()
            ->where("telegram_id" ,$data['telegram_id'])
            ->first()
             ??
            User::query()
            ->create(
['telegram_id'=>$data['telegram_id'],
            'name'=>$data['name'],
            'referral_code'=> Str::uuid()]);
    }


    protected function generateUid(): string
    {
         $uuid =  (string) Str::uuid();
         return $uuid;
    }


    public function createAccessToken(User $user)
    {
         return $user->createToken("USER TOKEN",[null],Carbon::now()->addHours(6))->plainTextToken;
    }

    public function loginUser(User $user)
    {
       return  Auth::login($user);
    }

    public function generateUuid()
    {
      
    }


}



