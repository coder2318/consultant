<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resume\IndexRequest;
use App\Http\Requests\Resume\StoreRequest;
use App\Http\Requests\Resume\UpdateRequest;
use App\Models\Resume;
use App\Services\ResumeService;

class ResumeController extends Controller
{
    public function __construct(protected ResumeService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/resume",
     *      operationId="ResumeIndex",
     *      tags={"Resume"},
     *      summary="Resume list",
     *     security={{ "bearerAuth": {} }},
     *      description="index",
     * @OA\Parameter(
     *         description="Category ID",
     *         in="query",
     *         name="category_id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         description="Search",
     *         in="query",
     *         name="search",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     * @OA\Parameter(
     *         description="Status CREATED = 1, CONFIRMED = 2, BLOCKED = 3",
     *         in="query",
     *         name="status",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="Sort Name for sort by rating, applications_count",
     *         in="query",
     *         name="sort_name",
     *         required=false,
     *         @OA\Schema(type="string")
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
     * path="/resume",
     * summary="Create new resume",
     * security={{ "bearerAuth": {} }},
     * description="Create ",
     * tags={"Resume"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new Resume",
     *    @OA\JsonContent(
     *       required={"category_id"},
     *       @OA\Property(property="category_id", type="number", example="12"),
     *       @OA\Property(property="language", type="string", example={"english", "russian"}),
     *       @OA\Property(property="about", type="string", example="about"),
     *       @OA\Property(property="files", type="string", example={"files"}),
     *       @OA\Property(property="skill_ids", type="string", example={1, 2}),
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
     * path="/resume/{resume}",
     * operationId="ResumeShow",
     * security={{ "bearerAuth": {} }},
     * description="Show by Resume",
     * tags={"Resume"},
     *     @OA\Parameter(
     *         description="Resume ID",
     *         in="path",
     *         name="resume",
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
        $model = $this->service->show((int) $id);
        $model->reviews;
        return response()->successJson($model);
    }

    /**
     * @OA\Put (
     * path="/resume/{resume}",
     * summary="Update Resume",
     * security={{ "bearerAuth": {} }},
     * description="Update ",
     * tags={"Resume"},
     *     @OA\Parameter(
     *         description="Resume ID",
     *         in="path",
     *         name="resume",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\RequestBody(
     *    description="Update Category",
     *    @OA\JsonContent(
     *       @OA\Property(property="category_id", type="number", example="12"),
     *       @OA\Property(property="language", type="string", example={"english", "russian"}),
     *       @OA\Property(property="about", type="string", example="about"),
     *       @OA\Property(property="files", type="string", example={"files"}),
     *       @OA\Property(property="skill_ids", type="string", example={1, 2}),
     *       @OA\Property(property="status", type="string", example="type number: 1-> created, 2-> confirmed, 3-> blocked"),
     *       @OA\Property(property="visible", type="string", example="true or false"),
     *     @OA\Property(property="file_delete", type="string", example={"file_1", "file_2"}),
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
        $model = $this->service->edit($request->all(), (int) $id);
        return response()->successJson($model);
    }

    /**
     * @OA\Delete(
     * path="/resume/{resume}",
     * summary="delete Resume",
     * security={{ "bearerAuth": {} }},
     * description="delete ",
     * tags={"Resume"},
     *     @OA\Parameter(
     *         description="Resume ID",
     *         in="path",
     *         name="resume",
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

    public function destroy($id)
    {
        $this->service->delete((int) $id);
        return response()->successJson('Successfully deleted');
    }

    /**
     * @OA\Get(
     *      path="/my-resume",
     *      operationId="MyResumeIndex",
     *      tags={"Resume"},
     *      summary="My Resume list",
     *     security={{ "bearerAuth": {} }},
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
        return response()->successJson($this->service->myIndex());
    }

    /**
     * @OA\Get(
     *      path="/check-has-resume",
     *      operationId="check-has-resume",
     *      tags={"Resume"},
     *      summary="check-has-resume",
     *     security={{ "bearerAuth": {} }},
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

    public function checkHasResume()
    {
        return response()->successJson($this->service->checkHasResume());
    }

    /**
     * @OA\Get(
     *      path="/top-consultant",
     *      operationId="top-consultant",
     *      tags={"Resume"},
     *      summary="top-consultant",
     *     security={{ "bearerAuth": {} }},
     *      description="index",
     *     @OA\Parameter(
     *         description="Limit",
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

    public function topConsultant(IndexRequest $request)
    {
        return response()->successJson(array_values($this->service->topConsultant($request->all())));
    }

    /**
     * @OA\Get(
     *      path="/resume-shortlist",
     *      operationId="ResumeShortIndex",
     *      tags={"Resume"},
     *      summary="Resume short list",
     *     security={{ "bearerAuth": {} }},
     *      description="index",
     * @OA\Parameter(
     *         description="Category ID",
     *         in="query",
     *         name="category_id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\Parameter(
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

    public function indexShortList(IndexRequest $request)
    {
        return response()->successJson($this->service->list($request->all()));
    }
}
