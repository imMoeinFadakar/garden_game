<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Str;

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
        'telegram_id',
        'name',
        'username',
        'gender',
        'market_status',
        'warehouse_status',
        'user_status',
        'token_amount',
        'gem_amount',
        'referral_code',
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
    

    public function directInvites()  
    {  
        // Define the relationship to get UserReferral records where this user is the inviter  
        return $this->hasMany(UserReferral::class, 'invading_user');   
    }  

    public function getInvitesWithIndirect()  
    {  
        // Fetch direct invites along with their indirect invites  
        $directInvites = $this->directInvites()
        ->with('reffred:id,name,username')
        ->withCount('reffred')
        ->orderBy("reffred_count",'desc')
        ->limit(5)
        ->get();  

        // Map through each direct invite to get their indirect invites  
        $invitationData = $directInvites->map(function ($invite) {  
           
            $indirectInvites = UserReferral::where('invading_user', $invite->invented_user)  
                ->with('reffred:id,name,username')
                ->limit(5) 
                ->get(['id','gender']);  
            $referralcount = $indirectInvites->count();

            $invite['invented_user'] = null;
            $invite['invading_user'] = null;
            $invite["indirect_invites"] = $indirectInvites;
            $invite["count"] = $referralcount;

            return [  
                $invite
            ];  
        });  

        return $invitationData;  
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

    public function findOrNewUser($request)
    {
        $user =  $this->query()
        ->where("telegram_id",$request["telegram_id"])
        ->first();

        if($user){
            return $user;
        }else{
            $reffralCode = Str::uuid();
            $request["referral_code"] = $reffralCode;
            $newUser = $this->query()->create($request);

            return $newUser;

        }

       


    }


    public static function findAuthUser()
    {
        return self::query()->find(Auth::id());
    }

    public static function insertNewUserValue(int $gem,int $token): int
    {
        $user = self::find(auth()->id());
       return  $user->update(["token_amount" => $token,"gem_amount" => $gem]);
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
