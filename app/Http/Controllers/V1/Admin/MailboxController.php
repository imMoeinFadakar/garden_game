<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\Mailbox\StoreMailboxRequest;
use App\Http\Requests\V1\Admin\Mailbox\UpdateMailboxRequest;
use App\Http\Resources\V1\Admin\MailboxResource;
use App\Models\Mailbox;
use Illuminate\Http\Request;

class MailboxController extends BaseAdminController
{
    /**
     * mailbox/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $mailbox = Mailbox::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query) => $query->where("id", $request->id))
        ->when(isset($request->title), fn($query) => $query->where("title","like",'%'.$request->title.'%'))
        ->when(isset($request->body), fn($query) => $query->where("body","like",'%'.$request->body.'%'))
        ->get();

        return $this->api(MailboxResource::collection($mailbox),__METHOD__);

    }

    /**
     * mailbox/store
     * @param \App\Http\Requests\V1\Admin\Mailbox\StoreMailboxRequest $request
     * @param \App\Models\Mailbox $mailbox
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreMailboxRequest $request,Mailbox $mailbox)
    {
        $mailbox = $mailbox->addNewMailbox($request->validated());
        return $this->api(new MailboxResource($mailbox->toArray()),__METHOD__);
    }

    /**
     * mailbox/show
     * @param \App\Models\Mailbox $mailbox
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Mailbox $mailbox)
    {
        return $this->api(new MailboxResource($mailbox->toArray()),__METHOD__);
    }

    /**
     * mailbox/update
     * @param \App\Http\Requests\V1\Admin\Mailbox\UpdateMailboxRequest $request
     * @param \App\Models\Mailbox $mailbox
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateMailboxRequest $request, Mailbox $mailbox)
    {
        $mailbox->updateMailbox($request->validated());
        return $this->api(new MailboxResource($mailbox->toArray()),__METHOD__);
    }

    /**
     * mailbox/destroy
     * @param \App\Models\Mailbox $mailbox
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Mailbox $mailbox)
    {
        $mailbox->deleteMailbox();
        return $this->api(new MailboxResource($mailbox->toArray()),__METHOD__);
    }
}
