<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\TutorialMessage\StoreTutorialMessagegeRequest;
use App\Http\Requests\V1\Admin\TutorialMessage\UpdateTutorialMessagegeRequest;
use App\Http\Resources\V1\Admin\TutorialMessageResource;
use App\Models\TutorialMessage;
use App\Trait\DeleteCacheTrait;
use Illuminate\Http\Request;

class TutorialMessageController extends BaseAdminController
{
    use DeleteCacheTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $TutorialMessage = TutorialMessage::all();
        return $this->api(
        TutorialMessageResource::collection($TutorialMessage),
        __METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTutorialMessagegeRequest $request,
                        TutorialMessage $tutorialMessage)
    {
        $tutorialMessage = $tutorialMessage->addNewTutorialMessage($request);

        $this->deleteCache('all_tutorial_messages');

        return $this->api(
        new TutorialMessageResource(
        $tutorialMessage->toArray()),
        __METHOD__);
    }

    /**
     * Display the specified resource.
     */
    public function show(TutorialMessage $tutorialMessage)
    {
        return $this->api(
        new TutorialMessageResource(
        $tutorialMessage->toArray()),
        __METHOD__);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTutorialMessagegeRequest $request, 
                            TutorialMessage $tutorialMessage)
    {
        $tutorialMessage->updateTutorialMessage($request);

        $this->deleteCache('all_tutorial_messages');

        return $this->api(
        new TutorialMessageResource(
        $tutorialMessage->toArray()),
        __METHOD__);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TutorialMessage $tutorialMessage)
    {
        $tutorialMessage->deleteTutorialMessage();

        $this->deleteCache('all_tutorial_messages');

        return $this->api(
        new TutorialMessageResource(
        $tutorialMessage->toArray()),
        __METHOD__);
    }
}
