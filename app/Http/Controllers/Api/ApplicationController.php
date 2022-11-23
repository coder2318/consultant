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
     *         name="title",
     *         in="query",
     *         description="title to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="text",
     *         ),
     *         style="form"
     *     ),
     *     @OA\Parameter(
     *         name="price_from",
     *         in="query",
     *         description="price_from to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         ),
     *         style="form"
     *     ),
     *     @OA\Parameter(
     *         name="price_to",
     *         in="query",
     *         description="price_to to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         ),
     *         style="form"
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="status to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         ),
     *         style="form"
     *     ),
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
     *       required={ "category_id", "description", "title", "price_from", "price_to"},
     *       @OA\Property(property="resume_id", type="number", example="1"),
     *       @OA\Property(property="title", type="string", example="Mushugimda muammo "),
     *       @OA\Property(property="category_id", type="number", example="1"),
     *       @OA\Property(property="description", type="string", example="Mushugimda muammo description"),
     *       @OA\Property(property="when_date", type="string", example="srochno or nesrochno"),
     *       @OA\Property(property="price_from", type="number", example="100000"),
     *       @OA\Property(property="price_to", type="number", example="150000"),
     *       @OA\Property(property="files", type="string", example="file"),
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

    public function show($id)
    {
        $model = $this->service->show($id);
        return response()->successJson($model);
    }

    /**
     * @OA\Get (
     * path="/admin/application/{application}",
     * summary="Show application by admin",
     * security={{ "bearerAuth": {} }},
     * description="Show by application admin",
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

    public function showAdmin($id)
    {
        $model = $this->service->showAdmin($id);
        return response()->successJson($model);
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
     *       @OA\Property(property="title", type="string", example="Mushugimda muammo update"),
     *            @OA\Property(property="description", type="string", example="Mushugimda muammo description"),
     *       @OA\Property(property="when_date", type="string", example="srochno or nesrochno"),
     *       @OA\Property(property="price_from", type="number", example="100000"),
     *       @OA\Property(property="price_to", type="number", example="150000"),
     *       @OA\Property(property="files", type="string", example="file"),
     *       @OA\Property(property="status", type="string", example=" type number: 1->e'lon qilingan, 2-> consultant bn kelishilgan, 3->chernovik qilib qoyilgan, 4->tugatilgan, 5->deactivatsiya qilingan, 6->otmen qilingan"),
     *       @OA\Property(property="reason_inactive", type="string", example="reason inactive"),
     *       @OA\Property(property="is_visible", type="bool", example="false"),
     *       @OA\Property(property="file_delete", type="string", example={"file_1", "file_2"}),
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
    public function update(UpdateRequest $request, $id)
    {
        $model = $this->service->edit($request->all(), $id);
        return response()->successJson($model);
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
        $model = $this->service->delete((int) $application->id);
        if($model)
            return response()->successJson('Successfully deleted');

        return response()->errorJson('Не удалено|306', 404);
    }

    /**
     * @OA\Get(
     *      path="/self-application",
     *      operationId="SelfApplciationIndex",
     *      tags={"Application"},
     *      security={{ "bearerAuth": {} }},
     *      summary="Application belongs to consultant list",
     *      description="consultant uchun o'zini categorylariga mos keladigan zayavkalar royxati",
     *      @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="category_id to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         ),
     *         style="form"
     *     ),
     * @OA\Parameter(
     *         name="price_from",
     *         in="query",
     *         description="price_from to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         ),
     *         style="form"
     *     ),
     * @OA\Parameter(
     *         name="price_to",
     *         in="query",
     *         description="price_to to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         ),
     *         style="form"
     *     ),
     * @OA\Parameter(
     *         name="when_date",
     *         in="query",
     *         description="when_date to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         style="form"
     *     ),
     * @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="type to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         style="form"
     *     ),
     *     @OA\Parameter(
     *         name="response_status",
     *         in="query",
     *         description="response_status to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         style="form"
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="search to filter by",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
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

    public function selfIndex(IndexRequest $request) // consultant uchun o'zini categorylariga mos keladigan zayavkalar royxati
    {
        return response()->successJson($this->service->selfIndex($request->all()));
    }

    /**
     * @OA\Get(
     *      path="/my-application",
     *      operationId="MyApplciationIndex",
     *      tags={"Application"},
     *      security={{ "bearerAuth": {} }},
     *      summary="Applications list for client self",
     *      description="index",
     *      @OA\Parameter(
     *         description="status",
     *         in="query",
     *         name="status",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         description="limit",
     *         in="query",
     *         name="limit",
     *         required=false,
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

    public function myIndex(IndexRequest $request) // mening zayavkalarim
    {
        $data = $request->all();
        $data['profile_id'] = auth()->user()->profile->id;
        return response()->successJson($this->service->list($data));
    }

    /**
     * @OA\Get(
     *      path="/my-order-application",
     *      operationId="MyOrderApplciationIndex",
     *      tags={"Application"},
     *      security={{ "bearerAuth": {} }},
     *      summary="mening zakazlarim consultant uchun",
     *      description="Consultant uchun mening zakazlarim apisi",
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

    public function myOrderIndex(IndexRequest $request) // mening zakazlarim consultant uchun
    {
        return response()->successJson($this->service->myOrderIndex($request->all()));
    }

    /**
     * @OA\Get(
     *      path="/my-response",
     *      operationId="myresponseIndex",
     *      tags={"Response"},
     *     security={{ "bearerAuth": {} }},
     *      summary="My response list",
     *      description="index",
     *     @OA\Parameter(
     *         name="response_status",
     *         in="query",
     *         description="response_status to filter by",
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

    public function myResponses(IndexRequest $request) // mening otkliklarim
    {
        return response()->successJson($this->service->myResponseApplication($request->all()));
    }

}
