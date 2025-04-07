<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\Policies\storePoliciesRequest;
use App\Http\Requests\V1\Admin\Policies\UpdatePoliciesRequest;
use App\Http\Resources\V1\User\PoliciesResource;
use App\Models\Policies;
use App\Models\Policy;

class PolicyController extends BaseAdminController
{
    /**
     * policy/index
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $policies = Policy::query()
        ->orderBy("id")
        ->get();

        return $this->api(PoliciesResource::collection($policies),__METHOD__);

    }

    /**
     * policy/store
     * @param \App\Http\Requests\V1\Admin\Policies\storePoliciesRequest $request
     * @param \App\Models\Policy $policies
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(storePoliciesRequest $request,Policy $policy)
    {
        $policy = $policy->addNewPolicy($request);
        return $this->api(new PoliciesResource($policy->toArray()),__METHOD__);
    }
    /**
     * policy/show
     * @param \App\Models\Policy $policies
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Policy $policy)
    {
        return $this->api(new PoliciesResource($policy->toArray()),__METHOD__);

    }

    /**
     * policy/update
     * @param \App\Http\Requests\V1\Admin\Policies\UpdatePoliciesRequest $request
     * @param \App\Models\Policy $policy
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdatePoliciesRequest $request, Policy $policy)
    {
        $policy->UpdatePolicies($request->validated());
        return $this->api(new PoliciesResource($policy->toArray()),__METHOD__);

    }

    /**
     * policy/destroy
     * @param \App\Models\Policy $policy
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Policy $policy)
    {
        $policy->deletePolicies();
        return $this->api(new PoliciesResource($policy->toArray()),__METHOD__);

    }
}
