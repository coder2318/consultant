<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Experience\StoreRequest;
use App\Http\Requests\Experience\UpdateRequest;
use App\Models\Experience;
use App\Services\ExperienceService;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function __construct(protected ExperienceService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/experience",
     *      operationId="experienceIndex",
     *      tags={"Experience"},
     *      security={{ "bearerAuth": {} }},
     *      summary="experience list",
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

    public function index(Request $request)
    {
        return response()->successJson($this->service->list($request->all()));
    }

    /**
     * @OA\Post(
     * path="/experience",
     * summary="Create new experience",
     * security={{ "bearerAuth": {} }},
     * description="Create experience",
     * tags={"Experience"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new experience",
     *    @OA\JsonContent(
     *       required={"resume_id", "start_date", "company_name", "profession"},
     *       @OA\Property(property="start_date", type="string", example="2022-01-01"),
     *       @OA\Property(property="resume_id", type="number", example="1"),
     *       @OA\Property(property="is_current_job", type="boolean", example="true"),
     *       @OA\Property(property="company_name", type="text", example="Agrobank OOO"),
     *       @OA\Property(property="profession", type="text", example="backend dasturchi"),
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
        $experience = $this->service->create($request->all());
        return response()->successJson($experience);
    }

    /**
     * @OA\Get (
     * path="/experience/{experience}",
     * summary="experienceShow",
     * operationId="experienceShow",
     * security={{ "bearerAuth": {} }},
     * description="Show by experience",
     * tags={"Experience"},
     *     @OA\Parameter(
     *         description="experience ID",
     *         in="path",
     *         name="experience",
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

    public function show(Experience $experience)
    {
        $model = $this->service->show($experience->id);
        if($model)
            return response()->successJson($model);
        return response()->errorJson('Информация не найдена|404', 404);
    }

    /**
     * @OA\Put (
     * path="/experience/{experience}",
     * summary="Update experience",
     * security={{ "bearerAuth": {} }},
     * description="Update experience",
     * tags={"Experience"},
     *     @OA\Parameter(
     *         description="experience ID",
     *         in="path",
     *         name="experience",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\RequestBody(
     *    description="Update Category",
     *    @OA\JsonContent(
     *       @OA\Property(property="start_date", type="string", example="2022-01-01"),
     *       @OA\Property(property="resume_id", type="number", example="1"),
     *       @OA\Property(property="is_current_job", type="boolean", example="true"),
     *       @OA\Property(property="company_name", type="text", example="Agrobank OOO"),
     *       @OA\Property(property="profession", type="text", example="backend dasturchi")
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
    public function update(UpdateRequest $request, Experience $experience)
    {
        $model = $this->service->edit($request->all(), $experience->id);
        if ($model)
            return response()->successJson($model);
        return response()->errorJson('Не обновлено|305', 422);

    }

    /**
     * @OA\Delete(
     * path="/experience/{experience}",
     * summary="delete experience",
     * security={{ "bearerAuth": {} }},
     * description="delete ",
     * tags={"Experience"},
     *     @OA\Parameter(
     *         description="experience ID",
     *         in="path",
     *         name="experience",
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

    public function destroy(Experience $experience)
    {
        $model = $this->service->delete((int) $experience->id);
        if($model)
            return response()->successJson('Successfully deleted');
        return response()->errorJson('Не удалено|306', 404);
    }
}
