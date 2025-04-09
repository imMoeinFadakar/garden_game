<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'telegram_id',
        'market_status',
        'user_status',
        'avatar_id',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function addNewUser( $request){
        return $this->query()->create( $request->validated());
    }

    public static function addUsername($request): int
    {
       $userId =  self::findAuthUser();

      return  self::query()
           ->where("id",$userId)
           ->update($request->validated());

    }

    public static function findAuthUser()
    {
        return self::query()->find(Auth::id());
    }

    public function updateUser($request): static
    {
        $this->update($request->validated());
        return $this;
    }

    public function createUserAccessToken($name = "USER TOKEN")
    {
        return $this->createToken($name,[null],carbon::now()->addHours(5))->plainTextToken;
    }

    public function deleteUser(): ?bool
    {
        return $this->delete();
    }
}
