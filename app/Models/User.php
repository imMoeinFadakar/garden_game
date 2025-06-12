<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Str;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        'has_parent'
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
     * Get the cart_user associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cart()
    {
        return $this->hasOne(CartUser::class, 'user_id', 'id');
    }


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
        return $this->hasMany(UserReferral::class, 'invading_user','id');   
    
    }  



    public function getTopReferralTreeCountsWithFarmOwners()
    {
        $cacheKey = "referral_tree_with_farms_" . auth()->id();

        return Cache::remember($cacheKey, now()->addMinutes( 10), function () {

            $directWithFarm = $this->directInvites()
                ->whereIn('invented_user', function ($q) {
                    $q->select('user_id')->from('user_farms');
                })
                ->with(['reffred:id,username'])
                ->get();

            $topDirectInvites = $directWithFarm
                ->map(function ($invite) {
                    $referrerId = $invite->invented_user;
                    $level1 = UserReferral::where('invading_user', $referrerId)
                        ->pluck('invented_user')->toArray();

                    return [
                        'invite' => $invite,
                        'level_1_user_ids' => $level1,
                        'level_1_total' => count($level1),
                    ];
                })
                ->sortByDesc('level_1_total')
                ->take(5);

            $topUserIds = $topDirectInvites->pluck('invite.invented_user')->toArray();

            $referralTree = $topDirectInvites->map(function ($data) {
                $invite = $data['invite'];
                $referrerId = $invite->invented_user;

                $level1WithFarms = UserReferral::where('invading_user', $referrerId)
                    ->pluck('invented_user')
                    ->filter(fn($id) => UserFarms::where('user_id', $id)->exists())
                    ->values()->toArray();

                $level2WithFarms = UserReferral::whereIn('invading_user', $level1WithFarms)
                    ->pluck('invented_user')
                    ->filter(fn($id) => UserFarms::where('user_id', $id)->exists())
                    ->values()->toArray();

                $level3WithFarms = UserReferral::whereIn('invading_user', $level2WithFarms)
                    ->pluck('invented_user')
                    ->filter(fn($id) => UserFarms::where('user_id', $id)->exists())
                    ->values()->toArray();

                return [
                    'username' => optional($invite->reffred)->username,
                    'level_1_count' => count($level1WithFarms),
                    'level_2_count' => count($level2WithFarms),
                    'level_3_count' => count($level3WithFarms),
                ];
            });

            $totalDirectCount = $directWithFarm
                ->filter(fn($invite) => !in_array($invite->invented_user, $topUserIds))
                ->count();

            return [
                'total_direct_count' => $totalDirectCount,
                'referral_tree' => $referralTree->values(),
            ];
        });
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