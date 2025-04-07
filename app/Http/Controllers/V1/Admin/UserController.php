<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\User\StoreUserRequest;
use App\Http\Requests\V1\Admin\User\UpdateUserRequest;
use App\Http\Resources\V1\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * user/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = User::query()
        ->orderBy('id')
        ->when(isset($request->id), fn($query)=> $query->where('id', $request->id))
        ->when(isset($request->name), fn($query)=> $query->where('name',"like",'%'.$request->name.'%'))
        ->when(isset($request->username), fn($query)=> $query->where('username',"like",'%'.$request->username.'%'))
        ->when(isset($request->market_status), fn($query)=> $query->where('market_status',"like",'%'.$request->market_status.'%'))
        ->when(isset($request->wherehouse_status), fn($query)=> $query->where('wherehouse_status',"like",'%'.$request->wherehouse_status.'%'))
        ->when(isset($request->user_status), fn($query)=> $query->where('user_status',"like",'%'.$request->user_status.'%'))
        ->get();


        return $this->api(UserResource::collection($user),__METHOD__);
    }

    /**
     * use5r/store
     * @param \App\Http\Requests\V1\Admin\User\StoreUserRequest $request
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request,User $user)
    {
        $user = $user->addNewUser($request);
        return $this->api(new UserResource($user->toArray()),__METHOD__);
    }

    /**
     * user/show
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return $this->api(new UserResource($user->toArray()),__METHOD__);

    }

    /**
     * user/update
     * @param \App\Http\Requests\V1\Admin\User\UpdateUserRequest $request
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->updateUser($request);
        return $this->api(new UserResource($user->toArray()),__METHOD__);

    }

    /**
     * user/destroy
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->deleteUser();
        return $this->api(new UserResource($user->toArray()),__METHOD__);

    }
}
