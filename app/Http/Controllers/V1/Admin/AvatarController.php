<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\Avatar\StoreAvatarRequest;
use App\Http\Requests\V1\Admin\Avatar\UpdateAvatarRequest;
use App\Http\Resources\V1\Admin\AvatarResource;
use App\Models\Avatar;
use Illuminate\Http\Request;



/**
 * avatar:is an image that picked by user
 */
class AvatarController extends BaseAdminController
{
    /**
     * avatar/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $avatar =  Avatar::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query)=> $query->where("id", $request->id))
        ->when(isset($request->gender),fn($query)=> $query->where("gender","like","%".$request->id."%"))
        ->get();

        return $this->api(AvatarResource::collection($avatar),__METHOD__);

    }

    /**
     * avatar/store
     * @param \App\Http\Requests\V1\Admin\Avatar\StoreAvatarRequest $request
     * @param \App\Models\Avatar $avatar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreAvatarRequest $request,Avatar $avatar)
    {
        $avatar = $avatar->addNewAvatar($request);
        return $this->api( new AvatarResource($avatar->toArray()),__METHOD__);
    }

    /**
     * avatar/show
     * @param \App\Models\Avatar $avatar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Avatar $avatar)
    {
        return $this->api( new AvatarResource($avatar->toArray()),__METHOD__);

    }

    /**
     * avatar/update
     * @param \App\Http\Requests\V1\Admin\Avatar\UpdateAvatarRequest $request
     * @param \App\Models\Avatar $avatar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateAvatarRequest $request, Avatar $avatar)
    {
        $avatar->updateAvatar($request);
        return $this->api( new AvatarResource($avatar->toArray()),__METHOD__);


    }

    /**
     * avatar/destroy
     * @param \App\Models\Avatar $avatar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Avatar $avatar)
    {
        $avatar->deleteAvatar();
        return $this->api( new AvatarResource($avatar),__METHOD__);

    }
}
