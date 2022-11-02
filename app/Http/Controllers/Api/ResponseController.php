<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * @OA\Get(
     *      path="/my-response",
     *      operationId="myresponseIndex",
     *      tags={"Response"},
     *     security={{ "bearerAuth": {} }},
     *      summary="My response list",
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

    public function myResponses(IndexRequest $request)
    {
        return response()->successJson($this->service->list($request->all()));
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
     *       @OA\Property(property="status", type="number", example="1")
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
}
