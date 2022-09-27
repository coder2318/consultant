<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\IndexRequest;
use App\Http\Requests\Profile\StoreRequest;
use App\Http\Requests\Profile\UpdateRequest;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/profile",
     *      operationId="ProfileIndex",
     *      tags={"Profile"},
     *     security={{ "bearerAuth": {} }},
     *      summary="Profile list",
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
     *
     */

    public function index(IndexRequest $request)
    {
        return response()->successJson($this->service->get($request->all()));
    }

    /**
     * @OA\Post(
     * path="/profile",
     * summary="Create new profile",
     * security={{ "bearerAuth": {} }},
     * description="Create by profile",
     * tags={"Profile"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new profile",
     *    @OA\JsonContent(
     *       required={"user_id", "role"},
     *       @OA\Property(property="user_id", type="number", example="6"),
     *       @OA\Property(property="role", type="string", example="user")
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
        $candidate = $this->service->create($request->all());
        return response()->successJson($candidate);
    }

    /**
     * @OA\Get (
     * path="/profile/{profile}",
     * summary="ProfileShow",
     * operationId="ProfileShow",
     * security={{ "bearerAuth": {} }},
     * description="Show by Profile",
     * tags={"Profile"},
     *     @OA\Parameter(
     *         description="Profile ID",
     *         in="path",
     *         name="profile",
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

    public function show(Profile $profile)
    {
        $model = $this->service->show($profile->id);
        if($model)
            return response()->successJson($model);
        return response()->errorJson('Информация не найдена|404', 404);
    }

    /**
     * @OA\Put (
     * path="/profile/{profile}",
     * summary="Update profile",
     * security={{ "bearerAuth": {} }},
     * description="Update ",
     * tags={"Profile"},
     *     @OA\Parameter(
     *         description="Profile ID",
     *         in="path",
     *         name="profile",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\RequestBody(
     *    description="Update profile",
     *    @OA\JsonContent(
     *       @OA\Property(property="role", type="string", example="consultant")
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
    public function update(UpdateRequest $request, Profile $profile)
    {
        $this->authorize('update', $profile);
        $params = $request->validated();
        $model = $this->service->edit($params, $profile->id);
        if ($model)
            return response()->successJson($model);
        return response()->errorJson('Не обновлено|305', 422);

    }

    /**
     * @OA\Delete(
     * path="/profile/{profile}",
     * summary="delete profile",
     * security={{ "bearerAuth": {} }},
     * description="delete by profile",
     * tags={"Profile"},
     *     @OA\Parameter(
     *         description="Profile ID",
     *         in="path",
     *         name="profile",
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

    public function destroy(Profile $profile)
    {
        $this->authorize('delete', $profile);
        $model = $this->service->delete((int) $profile->id);
        if($model)
            return response()->successJson('Successfully deleted');
        return response()->errorJson('Не удалено|306', 404);
    }
}
