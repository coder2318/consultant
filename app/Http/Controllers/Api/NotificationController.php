<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\StoreRequest;
use App\Http\Requests\Notification\UpdateRequest;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/notification",
     *      operationId="NotificationIndex",
     *      tags={"Notification"},
     *      security={{ "bearerAuth": {} }},
     *      summary="Notification list",
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

    public function index(Request $request)
    {
        return response()->successJson($this->service->list($request->all()));
    }

    /**
     * @OA\Get(
     *      path="/my-notification",
     *      operationId="MyNotificationIndex",
     *      tags={"Notification"},
     *      security={{ "bearerAuth": {} }},
     *      summary="Notifications list for client self",
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

    public function myIndex()
    {
        $data['profile_id'] = auth()->user()->profile->id;
        return response()->successJson($this->service->list($data));
    }

    /**
     * @OA\Post(
     * path="/notification",
     * summary="Create new notification",
     * security={{ "bearerAuth": {} }},
     * description="Create by comoany or recruter",
     * tags={"Notification"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new notification",
     *    @OA\JsonContent(
     *       required={ "profile_id", "text", "description"},
     *       @OA\Property(property="profile_id", type="number", example="1"),
     *       @OA\Property(property="text", type="string", example="Sizning arizangiz korib chiqiladi "),
     *       @OA\Property(property="description", type="string", example="Ariza bir necha ish kuni mobaynida korib chiqiladi degan umiddamiz ))")
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
        $notification = $this->service->create($request->all());
        return response()->successJson($notification);
    }

    /**
     * @OA\Get (
     * path="/notification/{notification}",
     * summary="Show notification",
     * security={{ "bearerAuth": {} }},
     * description="Show by notification",
     * tags={"Notification"},
     *     @OA\Parameter(
     *         description="notification ID",
     *         in="path",
     *         name="notification",
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

    public function show(Notification $notification)
    {
        $model = $this->service->showNew($notification->id);
        return response()->successJson($model);
    }

    /**
     * @OA\Get (
     * path="/admin/notification/{notification}",
     * summary="Show notification by admin",
     * security={{ "bearerAuth": {} }},
     * description="Show by notification admin",
     * tags={"Notification"},
     *     @OA\Parameter(
     *         description="notification ID",
     *         in="path",
     *         name="notification",
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

    public function showAdmin(Notification $notification)
    {
        $model = $this->service->show($notification->id);
        return response()->successJson($model);
    }

    /**
     * @OA\Put (
     * path="/notification/{notification}",
     * summary="Update notification",
     * security={{ "bearerAuth": {} }},
     * description="Update by notification",
     * tags={"Notification"},
     *     @OA\Parameter(
     *         description="notification ID",
     *         in="path",
     *         name="notification",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\RequestBody(
     *    description="Update notification",
     *    @OA\JsonContent(
     *       @OA\Property(property="profile_id", type="number", example="1"),
     *       @OA\Property(property="text", type="string", example="Sizning arizangiz korib chiqiladi "),
     *       @OA\Property(property="description", type="string", example="Ariza bir necha ish kuni mobaynida korib chiqiladi degan umiddamiz ))")
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
    public function update(UpdateRequest $request, Notification $notification)
    {
        $model = $this->service->edit($request->all(), $notification->id);
        return response()->successJson($model);
    }

    /**
     * @OA\Delete(
     *      path="/notification/{notification}",
     *      summary="delete notification",
     * security={{ "bearerAuth": {} }},
     * description="delete by notification",
     * tags={"Notification"},
     *     @OA\Parameter(
     *         description="notification ID",
     *         in="path",
     *         name="notification",
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

    public function destroy(Notification $notification)
    {
        $this->service->delete((int) $notification->id);
        return response()->successJson('Successfully deleted');
    }

    /**
     * @OA\Get (
     * path="/make-all-showed-notification",
     * summary="Show notification",
     * security={{ "bearerAuth": {} }},
     * description="Show by notification",
     * tags={"Notification"},
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
    public function allShowed()
    {
        return response()->successJson($this->service->makeShowedAll());
    }

}
