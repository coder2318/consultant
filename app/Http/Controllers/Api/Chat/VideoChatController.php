<?php

namespace App\Http\Controllers\Api\Chat;

use App\Events\NotificationWebsocketEvent;
use App\Events\StartVideoChat;
use App\Http\Controllers\Controller;
use App\Models\Chat\Zoom;
use App\Models\Profile;
use App\Services\Chat\ZoomService;
use Illuminate\Http\Request;

class VideoChatController extends Controller
{
    public function __construct(protected ZoomService $service)
    {
    }

    public function callUser(Request $request)
    {
        $data['userToCall'] = $request->user_to_call;
        $data['signalData'] = $request->signal_data;
        $data['from'] = auth()->user()->id;
        $data['type'] = 'incomingCall';
        $data['chat_id'] = $request->chat_id;
        broadcast(new StartVideoChat($data));
        $profile = Profile::where('user_id', $data['userToCall'])->first();
        if($profile)
            broadcast(new NotificationWebsocketEvent($profile->id));
        $this->service->create($data);
    }

    public function acceptCall(Request $request)
    {
        $data['signal'] = $request->signal;
        $data['to'] = $request->to;
        $data['type'] = 'callAccepted';
        $data['chat_id'] = $request->chat_id;

        broadcast(new StartVideoChat($data));
        $this->service->changeStatus($data, Zoom::INCOMING);
    }

    public function disconnectCall(Request $request)
    {
        $data['signal'] = null;
        $data['to'] = $request->to;
        $data['type'] = 'disconnect';
        $data['chat_id'] = $request->chat_id;

        broadcast(new StartVideoChat($data));

        $this->service->update($data);
    }

    public function declineCall(Request $request)
    {
        $data['signal'] = null;
        $data['to'] = $request->to;
        $data['type'] = 'decline';
        $data['chat_id'] = $request->chat_id;

        broadcast(new StartVideoChat($data));
//        $this->service->update($data);

//        $this->service->changeStatus($data, Zoom::DECLINED);
    }
}
