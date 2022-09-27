<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\IndexRequest;
use App\Http\Requests\Payment\StoreRequest;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $service)
    {
    }

    /**
     * @OA\Get(
     *      path="/payment",
     *      operationId="PaymentIndex",
     *      tags={"Payment"},
     *      security={{ "bearerAuth": {} }},
     *      summary="Payment list",
     *      description="index",
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
     * path="/payment",
     * summary="Create new payment",
     * security={{ "bearerAuth": {} }},
     * description="Create by comoany or recruter",
     * tags={"Payment"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Create new payment",
     *    @OA\JsonContent(
     *       required={ "application_id", "amount"},
     *       @OA\Property(property="application_id", type="number", example="1"),
     *       @OA\Property(property="amount", type="string", example="250000"),
     *       @OA\Property(property="payment_type", type="string", example="payme")
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
}
