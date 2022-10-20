<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ResourceService;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function __construct(protected ResourceService $service)
    {
    }
    /**
     * @OA\Get(
     *      path="/resource/translate/{lang}",
     *      operationId="Translate",
     *      tags={"Resource"},
     *     security={{ "bearerAuth": {} }},
     *      summary="Translate  list",
     *      description="index",
     *     @OA\Parameter(
     *         description="lang",
     *         in="path",
     *         name="lang",
     *         required=true,
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
    public function translate($key)
    {
        return $this->service->getTranslate($key);
    }

    /**
     * @OA\Get(
     *      path="/resource/language",
     *      operationId="Language",
     *      tags={"Resource"},
     *     security={{ "bearerAuth": {} }},
     *      summary="Language  list",
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
    public function language()
    {
        return $this->service->getLanguage();
    }
}
