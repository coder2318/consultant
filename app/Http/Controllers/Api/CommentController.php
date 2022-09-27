<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\IndexRequest;
use App\Http\Requests\Comment\StoreRequest;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(protected CommentService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/comment",
     *      operationId="commentIndex",
     *      tags={"Comment"},
     *     security={{ "bearerAuth": {} }},
     *      summary="comment list",
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
     * path="/comment",
     * summary="Create new comment",
     * security={{ "bearerAuth": {} }},
     * description="Create comment",
     * tags={"Comment"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new comment",
     *    @OA\JsonContent(
     *       required={"resume_id", "text"},
     *       @OA\Property(property="resume_id", type="number", example="3"),
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
     * @OA\Delete(
     * path="/comment/{comment}",
     * summary="delete comment",
     * security={{ "bearerAuth": {} }},
     * description="delete ",
     * tags={"Comment"},
     *     @OA\Parameter(
     *         description="comment ID",
     *         in="path",
     *         name="comment",
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

    public function destroy(Comment $comment)
    {
        $model = $this->service->delete((int) $comment->id);
        if($model)
            return response()->successJson('Successfully deleted');
        return response()->errorJson('Не удалено|306', 404);
    }
}
