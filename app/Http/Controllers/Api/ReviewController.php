<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreRequest;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/review-list/{resume_id}",
     *      operationId="reviewIndex",
     *      tags={"Review"},
     *     security={{ "bearerAuth": {} }},
     *      summary="review list",
     *      description="index",
     * @OA\Parameter(
     *         description="resume ID",
     *         in="path",
     *         name="resume_id",
     *         required=true,
     *         @OA\Schema(type="integer")
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

    public function index(Request $request, $resume_id)
    {
        $params = $request->all();
        $params['resume_id'] = $resume_id;
        return response()->successJson($this->service->list($params));
    }
    /**
     * @OA\Post(
     * path="/review",
     * summary="Create new review",
     * security={{ "bearerAuth": {} }},
     * description="Create review",
     * tags={"Review"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new review",
     *    @OA\JsonContent(
     *       required={"resume_id", "text"},
     *       @OA\Property(property="resume_id", type="number", example="3"),
     *       @OA\Property(property="text", type="text", example="backend dasturchi"),
     *       @OA\Property(property="rating", type="number", example="5"),
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
     * path="/review/{review}",
     * summary="delete review",
     * security={{ "bearerAuth": {} }},
     * description="delete ",
     * tags={"Review"},
     *     @OA\Parameter(
     *         description="review ID",
     *         in="path",
     *         name="review",
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

    public function destroy(int $review)
    {
        $this->service->delete($review);
        return response()->successJson('Successfully deleted');
    }
}
