<?php

namespace App\Models;

use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'fullname', 'email', 'password','type'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function createAccessToken($name = "ADMIN API TOKEN")
    {
        return $this->createToken($name,[null],Carbon::now()->addHours(5))->plainTextToken;
    }


    public function addNewAdmin( $request){
    return $this->query()->create( $request->validated());
    }

    public function updateAdmin($request){
    $this->update($request->validated());
    return $this;
    }


    public function deleteAdmin(){
    return $this->delete();
    }

}
