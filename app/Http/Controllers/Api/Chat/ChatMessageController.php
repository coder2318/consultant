<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatMessage\IndexRequest;
use App\Http\Requests\ChatMessage\SendRequest;
use App\Http\Requests\ChatMessage\UpdateRequest;
use App\Services\Chat\ChatMessageService;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function __construct(protected ChatMessageService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/chat-messages",
     *      operationId="ChatMessageIndex",
     *      tags={"ChatMessage"},
     *     security={{ "bearerAuth": {} }},
     *      summary="ChatMessage list",
     *      description="index",
     *     @OA\Parameter(
     *         name="chat_id",
     *         in="query",
     *         description="chat_id to filter by",
     *         required=true,
     *         @OA\Schema(
     *             type="number",
     *         ),
     *         style="form"
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     )
     */
    public function index(IndexRequest $request)
    {
        if($this->service->validForChat($request->all())){

            $chatMessages = $this->service->list($request->all());
            return response()->successJson($chatMessages);
        }
        abort(403,'Ushbu chat xabarlarini olish uchun sizda huquq yetarli emas!|202');

    }

    /**
     * @OA\Post(
     * path="/chat-messages/send",
     * summary="Create new chat-message",
     * security={{ "bearerAuth": {} }},
     * description="Create ",
     * tags={"ChatMessage"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new Chat message",
     *    @OA\JsonContent(
     *       required={"chat_id", "message"},
     *       @OA\Property(property="chat_id", type="number", example="1"),
     *       @OA\Property(property="msg", type="string", example={
                        {
                        "message" : "hello",
                        "is_price" : false
                        },
                        {
                        "message" : 70000,
                        "is_price" : true
                        }
            }),
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

    public function send(SendRequest $request)
    {
//        dd($request->all());
        $chatMessage = $this->service->create($request->all());

        return response()->successJson($chatMessage);
    }
    /**
     * @OA\Put(
     * path="/chat-messages/update-showed",
     * summary="update-showed new chat-message",
     * security={{ "bearerAuth": {} }},
     * description="update-showed ",
     * tags={"ChatMessage"},
     * @OA\RequestBody(
     *    required=true,
     *    description="update-showed Chat message",
     *    @OA\JsonContent(
     *       required={"message_ids"},
     *       @OA\Property(property="message_ids", type="string", example={1}),
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

    public function updateShowed(UpdateRequest $request)
    {
        //TODO: policy yozish kerak o'ziga tegishli chatlarni update qilishi uchun
        $chatMessages = $this->service->updateShowed($request->all());
        return response()->successJson($chatMessages);
    }
}
