<?php

namespace App\Http\Controllers\V1\User;

use App\Services\TeammateRequestService;
use App\Trait\UserActiveTrait;
use Auth;
use App\Models\User;
use App\Http\Resources\UserRegistrationResource;
use App\Http\Requests\V1\User\Auth\SecondStepAuth;
use App\Http\Requests\V1\User\RegistrationRequest;
use App\Http\Resources\V1\User\RegistrationResource;

class RegistrationController extends BaseUserController
{   


    public function store(SecondStepAuth $request, User $user)
    {
        $user = $user->addnewUser($request);
        return $this->api(new UserRegistrationResource($user->toArray()),__METHOD__);
    }


    protected TeammateRequestService $teammateRequestService;
    protected UserController $userController;
    /**
     * Class constructor.
     */
    public function __construct(TeammateRequestService $teammateRequestService, UserController $userController)
    {
        $this->userController = $userController;
        $this->teammateRequestService = $teammateRequestService;
    }



    /**
     * add user name and gender to user table
     * @param \App\Http\Requests\V1\User\RegistrationRequest $request
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function addUsernameAndGender(RegistrationRequest $request,User $user)
    {   
         


        
        $validated = $request->validated();
        $user = auth()->user();
        $user->gender = $validated["gender"];
        $user->username = $validated["username"];
        $user->save();
        $user->id = null;

       $hasTeammateRequest =  $this->userController->getPendingTeammateRequest();
        $parentlessUser = $this->userController->getAllParentlessUsers();

            if($parentlessUser && $hasTeammateRequest){

                foreach($parentlessUser as $user){
                    
                    $teammateRequest =  $this->userController->getPendingTeammateRequest();

                    if(! $teammateRequest || $user->id === auth()->id()) break;

                    $this->userController->updateTeammateRequest($teammateRequest);
                    
                    $this->userController->addNewTeammateForUser($user,$teammateRequest->user_id);

                    $user->has_parent = "true";
                    $user->save();

                }


            }


        return $this->api(new RegistrationResource($user),__METHOD__);
    }

}
