<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\FarmResource;
use App\Models\Farms;
use Illuminate\Http\Request;

class FarmController extends BaseUserController
{
    /**
     * farm/index
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $farms = Farms::query()
            ->orderBy("id")
            ->get();

        return $this->api(FarmResource::collection($farms),__METHOD__);
    }


    public function show(Farms $farms,$id)
    {
        return $this->api(new FarmResource($farms->find($id)),__METHOD__);
    }


}
