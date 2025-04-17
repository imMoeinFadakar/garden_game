<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Policies\storePoliciesRequest;
use App\Http\Requests\V1\Admin\Policies\UpdatePoliciesRequest;
use App\Http\Resources\V1\Admin\PolicyResource;
use App\Models\Policy;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class PolicyController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policy = Policy::query()
        ->orderBy("id")
        ->get();

        return $this->api(PolicyResource::collection($policy),__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storePoliciesRequest $request,Policy $policy)
    {
        $policy = $policy->addNewPolicy($request->validated());
        return $this->api(new PolicyResource($policy->toArray()),__METHOD__);
    }

    /**
     * Display the specified resource.
     */
    public function show(Policy $policy)
    {
        return $this->api(new PolicyResource($policy->toArray()),__METHOD__);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePoliciesRequest $request, Policy $policy)
    {
        
        $policy->updatePolicy($request->validated());
        return $this->api(new PolicyResource($policy->toArray()),__METHOD__);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Policy $policy)
    {
        $policy->deletePolicy();
        return $this->api(new PolicyResource($policy->toArray()),__METHOD__);

    }
}
