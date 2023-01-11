<?php

namespace App\Http\Controllers\Api\Chat;

use App\Events\NotificationWebsocketEvent;
use App\Events\StartVideoChat;
use App\Http\Controllers\Controller;
use App\Http\Requests\Zoom\ActionInChatRequest;
use App\Models\Chat\Chat;
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

    /**
     * @OA\Post(
     * path="/video/invite-to-chat/{chat_id}",
     * summary="api for invite to chat",
     * security={{ "bearerAuth": {} }},
     * description="api for invite to chat",
     * tags={"Zoom"},
     *     @OA\Parameter(
     *         name="chat_id",
     *         in="path",
     *         description="Chat_id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Unprocessable Content",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, filled input. Please try again")
     *        )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * )
     */

    public function inviteChat($chat_id)
    {
        return response()->successJson($this->service->inviteToChat($chat_id));
    }

    /**
     * @OA\Post(
     * path="/video/action-in-chat",
     * summary="Action when intived in chat",
     * security={{ "bearerAuth": {} }},
     * description="Action when intived in chat",
     * tags={"Zoom"},
     * @OA\RequestBody(
     *    required=true,
     *    description="d",
     *    @OA\JsonContent(
     *       required={ "chat_id", "type"},
     *       @OA\Property(property="chat_id", type="number", example="1"),
     *       @OA\Property(property="type", type="string", example="accept or now_now")
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Unprocessable Content",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, filled input. Please try again")
     *        )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * )
     */

    public function actionInChat(ActionInChatRequest $request)
    {
        return response()->successJson($this->service->inviteToChat($request->all()));
    }
}
