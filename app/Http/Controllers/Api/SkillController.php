<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Skill\IndexRequest;
use App\Http\Requests\Skill\StoreRequest;
use App\Http\Requests\Skill\UpdateRequest;
use App\Models\Skill;
use App\Services\SkillService;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function __construct(protected SkillService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/skill",
     *      operationId="SkillIndex",
     *      tags={"Skill"},
     *      security={{ "bearerAuth": {} }},
     *      summary="Skill list",
     *      description="index",
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="category_id to filter by",
     *         required=false,
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
        return response()->successJson($this->service->get($request->all()));
    }

    /**
     * @OA\Post(
     * path="/skill",
     * summary="Create new skill",
     * security={{ "bearerAuth": {} }},
     * description="Create by comoany or recruter",
     * tags={"Skill"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new skill",
     *    @OA\JsonContent(
     *       required={ "category_id", "name"},
     *       @OA\Property(property="category_id", type="number", example="4"),
     *       @OA\Property(property="name", type="string", example="agrotexnik")
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
        $skill = $this->service->create($request->all());
        return response()->successJson($skill);
    }

    /**
     * @OA\Get (
     * path="/skill/{skill}",
     * summary="Show skill",
     * security={{ "bearerAuth": {} }},
     * description="Show by skill",
     * tags={"Skill"},
     *     @OA\Parameter(
     *         description="skill ID",
     *         in="path",
     *         name="skill",
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

    public function show(Skill $skill)
    {
        $model = $this->service->show($skill->id);
        return response()->successJson($model);
    }

    /**
     * @OA\Put (
     * path="/skill/{skill}",
     * summary="Update skill",
     * security={{ "bearerAuth": {} }},
     * description="Update by skill",
     * tags={"Skill"},
     *     @OA\Parameter(
     *         description="skill ID",
     *         in="path",
     *         name="skill",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\RequestBody(
     *    description="Update skill",
     *    @OA\JsonContent(
     *       @OA\Property(property="category_id", type="number", example="4"),
     *       @OA\Property(property="name", type="string", example="agrotexnik")
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
    public function update(UpdateRequest $request, Skill $skill)
    {
        $model = $this->service->edit($request->all(), $skill->id);
        return response()->successJson($model);
    }

    /**
     * @OA\Delete(
     *      path="/skill/{skill}",
     *      summary="delete skill",
     * security={{ "bearerAuth": {} }},
     * description="delete by skill",
     * tags={"Skill"},
     *     @OA\Parameter(
     *         description="skill ID",
     *         in="path",
     *         name="skill",
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
     *      security={{ "bearerAuth": {} }},
     * )
     */

    public function destroy(Skill $skill)
    {
        $model = $this->service->delete((int) $skill->id);
        if($model)
            return response()->successJson('Successfully deleted');

        return response()->errorJson('Не удалено|306', 404);
    }
}
