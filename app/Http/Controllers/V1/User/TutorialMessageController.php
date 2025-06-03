<?php

namespace App\Http\Controllers\V1\User;


use App\Http\Requests\V1\User\TutorialIsDoneRequest;
use App\Http\Resources\V1\User\TutorialMessageResource;
use App\Models\TutorialMessage;
use App\Models\UserTutorialMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TutorialMessageController extends BaseUserController
{
    public function getTutorialMessage(Request $request)
    {
        $cacheKey = 'all_tutorial_messages';

        if ($request->filled('page')) {
            $cacheKey .= '_page_' . $request->page;
        }

        $tutorialMessages = Cache::remember($cacheKey, now()->addDays(1), function () use ($request) {
            return TutorialMessage::query()
                ->orderBy('id')
                ->when($request->filled('page'), fn($q) => $q->where('page', 'like', '%' . $request->page . '%'))
                ->get();
        });

        return $this->api(TutorialMessageResource::collection($tutorialMessages), __METHOD__);
    }

    public function isUserDoneWithTutorial()
    {
        $cacheKey = 'user_done_tutorial_' . auth()->id();

        $isDone = Cache::remember($cacheKey, now()->addHours(12), function () {
            return $this->getUserDoneTutorial();
        });

        return $this->api($isDone, __METHOD__);
    }

    protected function getUserDoneTutorial(): bool
    {
        return UserTutorialMessage::query()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function addNewUserToDoneList(TutorialIsDoneRequest $request, UserTutorialMessage $userTutorialMessage)
    {
        $userExists = $this->findUserId();

        if ($userExists) {
            return $this->api(null, __METHOD__, 'user exists before');
        }

        $userTutorialMessage = $userTutorialMessage->addNewUserTutorialMessage($request);

        // ❌ پاک کردن کش مربوط به وضعیت آموزشی کاربر
        Cache::forget('user_done_tutorial_' . auth()->id());

        return $this->api(new TutorialMessageResource($userTutorialMessage->toArray()), __METHOD__);
    }

    public function findUserId(): bool
    {
        return Cache::remember('user_done_tutorial_' . auth()->id(), now()->addHours(12), function () {
            return UserTutorialMessage::query()
                ->where('user_id', auth()->id())
                ->exists();
        });
    }
}
