<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reaction\StoreRequest;
use App\Models\Reaction;
use App\Services\ReactionService;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function __construct(protected ReactionService $service)
    {
    }

    /**
     * @OA\Post(
     * path="/reaction",
     * summary="Create new reaction",
     * security={{ "bearerAuth": {} }},
     * description="Create reaction",
     * tags={"Reaction"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new reaction",
     *    @OA\JsonContent(
     *       required={"comment_id", "profile_id", "reaction"},
     *       @OA\Property(property="comment_id", type="number", example="3"),
     *       @OA\Property(property="profile_id", type="number", example="3"),
     *       @OA\Property(property="reaction", type="bool", example="true"),
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
     * path="/reaction/{reaction}",
     * summary="delete reaction",
     * security={{ "bearerAuth": {} }},
     * description="delete ",
     * tags={"Reaction"},
     *     @OA\Parameter(
     *         description="reaction ID",
     *         in="path",
     *         name="reaction",
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

    public function destroy(Reaction $reaction)
    {
        $model = $this->service->delete((int) $reaction->id);
        if($model)
            return response()->successJson('Successfully deleted');
        return response()->errorJson('Не удалено|306', 404);
    }
}
