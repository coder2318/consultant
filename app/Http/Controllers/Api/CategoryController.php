<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\IndexRequest;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/category",
     *      operationId="CategoryIndex",
     *      tags={"Category"},
     *     security={{ "bearerAuth": {} }},
     *      summary="Category list",
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
        return response()->successJson($this->service->list($request->all()));
    }

    /**
     * @OA\Post(
     * path="/category",
     * summary="Create new category",
     * security={{ "bearerAuth": {} }},
     * description="Create ",
     * tags={"Category"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new Category",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", example="AgroConsult"),
     *       @OA\Property(property="parent_id", type="number", example="1")
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
     * path="/category/{category}",
     * summary="CategoryShow",
     * operationId="CategoryShow",
     * security={{ "bearerAuth": {} }},
     * description="Show by Category",
     * tags={"Category"},
     *     @OA\Parameter(
     *         description="Category ID",
     *         in="path",
     *         name="category",
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

    public function show(Category $category)
    {
        $model = $this->service->show($category->id);
        if($model)
            return response()->successJson($model);
        return response()->errorJson('Информация не найдена|404', 404);
    }

    /**
     * @OA\Put (
     * path="/category/{category}",
     * summary="Update Category",
     * security={{ "bearerAuth": {} }},
     * description="Update ",
     * tags={"Category"},
     *     @OA\Parameter(
     *         description="Category ID",
     *         in="path",
     *         name="category",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\RequestBody(
     *    description="Update Category",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="Product"),
     *       @OA\Property(property="parent_id", type="number", example="2")
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
    public function update(UpdateRequest $request, Category $category)
    {
        $model = $this->service->edit($request->all(), $category->id);
        if ($model)
            return response()->successJson($model);
        return response()->errorJson('Не обновлено|305', 422);

    }

    /**
     * @OA\Delete(
     * path="/category/{category}",
     * summary="delete Category",
     * security={{ "bearerAuth": {} }},
     * description="delete ",
     * tags={"Category"},
     *     @OA\Parameter(
     *         description="Category ID",
     *         in="path",
     *         name="category",
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

    public function destroy(Category $category)
    {
        $model = $this->service->delete((int) $category->id);
        if($model)
            return response()->successJson('Successfully deleted');
        return response()->errorJson('Не удалено|306', 404);
    }
}
