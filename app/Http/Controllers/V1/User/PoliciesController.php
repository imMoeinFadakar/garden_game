<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\PoliciesResource;
use App\Models\Policies;
use App\Models\Policy;
use App\Models\PolicyAndRule;
use Illuminate\Http\Request;

class PoliciesController extends BaseUserController
{
    /**
     * all policy
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $policies = PolicyAndRule::all();

        return $this->api(PoliciesResource::collection($policies),__METHOD__);
    }


}
