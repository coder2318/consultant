<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Websocket;
use App\Http\Requests\Application\{IndexRequest, StoreRequest, UpdateRequest};
use App\Models\Application;
use App\Services\ApplicationService;

class ApplicationController extends Controller
{
    public function __construct(protected ApplicationService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/application",
     *      operationId="ApplciationIndex",
     *      tags={"Application"},
     *      security={{ "bearerAuth": {} }},
     *      summary="Application list",
     *      description="index",
     *     @OA\Parameter(
     *         name="resume_id",
     *         in="query",
     *         description="resume_id to filter by",
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
     * path="/application",
     * summary="Create new application",
     * security={{ "bearerAuth": {} }},
     * description="Create by comoany or recruter",
     * tags={"Application"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new application",
     *    @OA\JsonContent(
     *       required={ "category_id", "text"},
     *       @OA\Property(property="resume_id", type="number", example="1"),
     *       @OA\Property(property="category_id", type="number", example="1"),
     *       @OA\Property(property="text", type="string", example="Mushugimda muammo "),
     *       @OA\Property(property="date", type="string", example="2022-10-12")
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
        $application = $this->service->create($request->all());
        return response()->successJson($application);
    }

    /**
     * @OA\Get (
     * path="/application/{application}",
     * summary="Show application",
     * security={{ "bearerAuth": {} }},
     * description="Show by application",
     * tags={"Application"},
     *     @OA\Parameter(
     *         description="application ID",
     *         in="path",
     *         name="application",
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

    public function show(Application $application)
    {
        $model = $this->service->show($application->id);
        if($model)
            return response()->successJson($model);
        return response()->errorJson('Информация не найдена|404', 404);
    }

    /**
     * @OA\Put (
     * path="/application/{application}",
     * summary="Update application",
     * security={{ "bearerAuth": {} }},
     * description="Update by application",
     * tags={"Application"},
     *     @OA\Parameter(
     *         description="application ID",
     *         in="path",
     *         name="application",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\RequestBody(
     *    description="Update application",
     *    @OA\JsonContent(
     *       @OA\Property(property="resume_id", type="number", example="1"),
     *       @OA\Property(property="profile_id", type="number", example="1"),
     *       @OA\Property(property="category_id", type="number", example="1"),
     *       @OA\Property(property="text", type="string", example="Mushugimda muammo update"),
     *       @OA\Property(property="date", type="string", example="2022-10-12")
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
    public function update(UpdateRequest $request, Application $application)
    {
        $this->authorize('update', $application);
        $model = $this->service->edit($request->all(), $application->id);
        if ($model)
            return response()->successJson($model);
        return response()->errorJson('Не обновлено|305', 422);

    }

    /**
     * @OA\Delete(
     *      path="/application/{application}",
     *      summary="delete application",
     * security={{ "bearerAuth": {} }},
     * description="delete by application",
     * tags={"Application"},
     *     @OA\Parameter(
     *         description="application ID",
     *         in="path",
     *         name="application",
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

    public function destroy(Application $application)
    {
        $this->authorize('delete', $application);
        $model = $this->service->delete((int) $application->id);
        if($model)
            return response()->successJson('Successfully deleted');

        return response()->errorJson('Не удалено|306', 404);
    }

}
