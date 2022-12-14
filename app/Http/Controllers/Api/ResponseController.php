<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CancelResponseRequest;
use App\Http\Requests\Chat\CreateChatRequest;
use App\Http\Requests\Response\{IndexRequest, StoreRequest, UpdateRequest};
use App\Models\Response;
use App\Services\ResponseService;

class ResponseController extends Controller
{
    public function __construct(protected ResponseService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/response",
     *      operationId="responseIndex",
     *      tags={"Response"},
     *     security={{ "bearerAuth": {} }},
     *      summary="response list",
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
        return response()->successJson($this->service->get($request->all()));
    }

    /**
     * @OA\Post(
     * path="/response",
     * summary="Create new response",
     * security={{ "bearerAuth": {} }},
     * description="Create response",
     * tags={"Response"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new response",
     *    @OA\JsonContent(
     *       required={"application_id", "resume_id", "amount", "text"},
     *       @OA\Property(property="application_id", type="number", example="1"),
     *       @OA\Property(property="resume_id", type="number", example="3"),
     *       @OA\Property(property="amount", type="text", example="250000"),
     *       @OA\Property(property="text", type="text", example="backend dasturchi"),
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
        $response = $this->service->create($request->all());
        return response()->successJson($response);
    }

    /**
     * @OA\Get (
     * path="/response/{response}",
     * summary="responseShow",
     * operationId="responseShow",
     * security={{ "bearerAuth": {} }},
     * description="Show by response",
     * tags={"Response"},
     *     @OA\Parameter(
     *         description="response ID",
     *         in="path",
     *         name="response",
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

    public function show(Response $response)
    {
        $model = $this->service->show($response->id);
        return response()->successJson($model);
    }

    /**
     * @OA\Put (
     * path="/response/{response}",
     * summary="Update response",
     * security={{ "bearerAuth": {} }},
     * description="Update response",
     * tags={"Response"},
     *     @OA\Parameter(
     *         description="response ID",
     *         in="path",
     *         name="response",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\RequestBody(
     *    description="Update Category",
     *    @OA\JsonContent(
     *       @OA\Property(property="amount", type="text", example="250000"),
     *       @OA\Property(property="text", type="text", example="backend dasturchi"),
    *       @OA\Property(property="status", type="string", example="type number: 1-> yuborilgan, 2->qabul qilingan, 3->bekor qilingan")
     *    ),
     * ),
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
    public function update(UpdateRequest $request, Response $response)
    {
        $model = $this->service->edit($request->all(), $response->id);
        return response()->successJson($model);
    }

    /**
     * @OA\Delete(
     * path="/response/{response}",
     * summary="delete response",
     * security={{ "bearerAuth": {} }},
     * description="delete ",
     * tags={"Response"},
     *     @OA\Parameter(
     *         description="response ID",
     *         in="path",
     *         name="response",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     * )
     */

    public function destroy(Response $response)
    {
        $this->service->delete((int) $response->id);
        return response()->successJson('Successfully deleted');
    }

    /**
     * @OA\Get (
     * path="/response-chat/{response}",
     * summary="responseChatShow",
     * operationId="responseChatShow",
     * security={{ "bearerAuth": {} }},
     * description="Show by response",
     * tags={"Response"},
     *     @OA\Parameter(
     *         description="response ID",
     *         in="path",
     *         name="response",
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

    public function responseChat(Response $response)
    {
        return response()->successJson($this->service->redirectToChat($response));
    }

    /**
     * @OA\Get (
     * path="/response-check/{application_id}",
     * summary="responseCheckApplication",
     * operationId="responseCheckApplication",
     * security={{ "bearerAuth": {} }},
     * description="Applicationda otklik yuborgan yoki yubormaganligini tekshiradigan api",
     * tags={"Response"},
     *     @OA\Parameter(
     *         description="application ID",
     *         in="path",
     *         name="application_id",
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

    public function responseCheck($application_id)
    {
        return response()->successJson($this->service->checkResponse($application_id));
    }

    /**
     * @OA\Post(
     * path="/create-chat",
     * summary="Create new chat",
     * security={{ "bearerAuth": {} }},
     * description="Create chat ",
     * tags={"Response"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new chat",
     *    @OA\JsonContent(
     *       required={"application_id", "msg"},
     *       @OA\Property(property="application_id", type="number", example="1"),
     *        @OA\Property(property="msg", type="string", example={
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

    public function storeChat(CreateChatRequest $request)
    {
        $response = $this->service->createChat($request->all());
        return response()->successJson($response);
    }

    /**
     * @OA\Post(
     * path="/cancel-response",
     * summary="Cancel response and change status to deny",
     * security={{ "bearerAuth": {} }},
     * description="Cancel response",
     * tags={"Response"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Cancel response",
     *    @OA\JsonContent(
     *       required={"application_id", "profile_id", "chat_id"},
     *       @OA\Property(property="application_id", type="number", example="1"),
     *       @OA\Property(property="profile_id", type="number", example="1"),
     *       @OA\Property(property="chat_id", type="number", example="1")
     *      ),
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
    public function cancelResponse(CancelResponseRequest $request)
    {
        return response()->successJson($this->service->cancelResponse($request->all()));
    }
}
