<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Badge\StoreBadgeRequest;
use App\Http\Resources\V1\Admin\BadgeResource;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    /**
     * badge/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $badges = Badge::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query) => $query->where("id", $request->id))
        ->get();

        return $this->api(BadgeResource::collection($badges),__METHOD__);

    }

    /**
     * badge/store
     * @param \App\Http\Requests\V1\Admin\Badge\StoreBadgeRequest $request
     * @param \App\Models\Badge $badge
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreBadgeRequest $request , Badge $badge)
    {
        $badge = $badge->addNewBadge($request);
        return $this->api(new BadgeResource($badge->toArray()),__METHOD__);
    }

    /**
     * badge/show
     * @param \App\Models\Badge $badge
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Badge $badge)
    {
        return $this->api(new BadgeResource($badge->toArray()),__METHOD__);

    }


    /**
     * badge/update
     * @param \App\Http\Requests\V1\Admin\Badge\StoreBadgeRequest $request
     * @param \App\Models\Badge $badge
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(StoreBadgeRequest $request , Badge $badge)
    {
        $badge->updateBadge($request);
        return $this->api(new BadgeResource($badge->toArray()),__METHOD__);

    }

    /**
     * badge/destroy
     * @param \App\Models\Badge $badge
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Badge $badge)
    {
        $badge->deleteBadge();
        return $this->api(new BadgeResource($badge->toArray()),__METHOD__);

    }
}
