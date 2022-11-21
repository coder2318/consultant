<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\IndexRequest;
use App\Http\Requests\Chat\StoreRequest;
use App\Services\Chat\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(protected ChatService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/chats",
     *      operationId="ChatIndex",
     *      tags={"Chat"},
     *     security={{ "bearerAuth": {} }},
     *      summary="Chat list",
     *      description="index",
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
        $chats = $this->service->index($request->all());

        return response()->successJson($chats);
    }

    /**
     * @OA\Get(
     *      path="/consultant-chats",
     *      operationId="ConsultantChatIndex",
     *      tags={"Chat"},
     *     security={{ "bearerAuth": {} }},
     *      summary="Consultant Chat list",
     *      description="index",
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
    public function indexConsultant(IndexRequest $request)
    {
        $chats = $this->service->index($request->all(), true);

        return response()->successJson($chats);
    }


    /**
     * @OA\Post(
     * path="/chats",
     * summary="Create new chat",
     * security={{ "bearerAuth": {} }},
     * description="Create ",
     * tags={"Chat"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new Chat",
     *    @OA\JsonContent(
     *       required={"to_profile_id", "application_id"},
     *       @OA\Property(property="to_profile_id", type="number", example="1"),
     *       @OA\Property(property="application_id", type="number", example="1"),
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

    public function store(StoreRequest $request)
    {
        $model = $this->service->create($request->all());
        return response()->successJson($model);
    }

    /**
     * @OA\Get (
     * path="/chats/{chat_id}",
     * summary="Chat Show",
     * operationId="chats Show",
     * security={{ "bearerAuth": {} }},
     * description="Show Chat",
     * tags={"Chat"},
     *     @OA\Parameter(
     *         description="chat ID",
     *         in="path",
     *         name="chat_id",
     *         required=true,
     *         @OA\Schema(type="integer")
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

    public function show(int $chat_id)
    {
        $model = $this->service->show($chat_id);
        $model->application->status;
        return response()->successJson($model);
    }
}
