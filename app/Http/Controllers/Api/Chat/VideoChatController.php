<?php

namespace App\Http\Controllers\Api\Chat;

use App\Events\NotificationEvent;
use App\Events\StartVideoChat;
use App\Http\Controllers\Controller;
use App\Models\Chat\Zoom;
use Illuminate\Http\Request;

class VideoChatController extends Controller
{
    public function callUser(Request $request)
    {
        $data['userToCall'] = $request->user_to_call;
        $data['signalData'] = $request->signal_data;
        $data['from'] = auth()->user()->id;
        $data['type'] = 'incomingCall';
        $data['chat_id'] = $request->chat_id;
//        Zoom::create([
//            'profile_ids' => [$data['from'], $data['userToCall']],
//            'application_id' => $request->application_id ?? 1
//        ]);
        broadcast(new StartVideoChat($data));
        broadcast(new NotificationEvent($data['userToCall']));
    }

    public function acceptCall(Request $request)
    {
        $data['signal'] = $request->signal;
        $data['to'] = $request->to;
        $data['type'] = 'callAccepted';
        $data['chat_id'] = $request->chat_id;

        broadcast(new StartVideoChat($data));
    }

    public function disconnectCall(Request $request)
    {
        $data['signal'] = null;
        $data['to'] = $request->to;
        $data['type'] = 'disconnect';
        $data['chat_id'] = $request->chat_id;

        broadcast(new StartVideoChat($data));
    }

    public function declineCall(Request $request)
    {
        $data['signal'] = null;
        $data['to'] = $request->to;
        $data['type'] = 'decline';
        $data['chat_id'] = $request->chat_id;

        broadcast(new StartVideoChat($data));
    }
}
