<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Policies\storePoliciesRequest;
use App\Http\Resources\V1\User\PoliciesResource;
use App\Models\PolicyAndRule;
use Illuminate\Http\Request;

class PolicyAndRuleController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $policyAndRules = PolicyAndRule::all();
        return $this->api(PoliciesResource::collection($policyAndRules),__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storePoliciesRequest $request,PolicyAndRule $policyAndRule)
    {   
        $policyAndRule = $policyAndRule->addNewPolicyAndRule($request);
        return $this->api(new PoliciesResource($policyAndRule->toArray()),__METHOD__);
    }

    /**
     * Display the specified resource.
     */
    public function show(PolicyAndRule $policyAndRule)
    {
        return $this->api(new PoliciesResource($policyAndRule->toArray()),__METHOD__);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(storePoliciesRequest $request,PolicyAndRule $policyAndRule)
    {   
        $policyAndRule->updatePolicyAndRule($request);
        return $this->api(new PoliciesResource($policyAndRule->toArray()),__METHOD__);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PolicyAndRule $policyAndRule)
    {   
        $policyAndRule->deletePolicyAndRule();
        return $this->api(new PoliciesResource($policyAndRule->toArray()),__METHOD__);

    }
}
