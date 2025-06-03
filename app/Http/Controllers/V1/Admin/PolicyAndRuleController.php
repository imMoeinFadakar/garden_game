<?php

namespace App\Http\Controllers\V1\Admin;

use App\Trait\DeleteCacheTrait;
use Illuminate\Http\Request;
use App\Models\PolicyAndRule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\V1\User\PoliciesResource;
use App\Http\Requests\V1\Admin\Policies\storePoliciesRequest;

class PolicyAndRuleController extends BaseAdminController
{
    use DeleteCacheTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $policyAndRules = PolicyAndRule::all();
        $this->deleteCache("all_policies");
        return $this->api(PoliciesResource::collection($policyAndRules),__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storePoliciesRequest $request,PolicyAndRule $policyAndRule)
    {   
        $policyAndRule = $policyAndRule->addNewPolicyAndRule($request);
        $this->deleteCache("all_policies");
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
        $this->deleteCache("all_policies");
        return $this->api(new PoliciesResource($policyAndRule->toArray()),__METHOD__);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PolicyAndRule $policyAndRule)
    {   
        $policyAndRule->deletePolicyAndRule();
        $this->deleteCache("all_policies");
        return $this->api(new PoliciesResource($policyAndRule->toArray()),__METHOD__);

    }





}
