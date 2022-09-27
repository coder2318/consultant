<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @OA\Post(
     * path="/login",
     * summary="Sign in",
     * description="Login by login, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"login","password"},
     *       @OA\Property(property="login", type="string", format="login", example="admin"),
     *       @OA\Property(property="password", type="string", format="password", example="33333333")
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */
    public function login()
    {

        $credentials = [
            'login' => Str::lower(request('login')),
            'password' => request('password')
        ];

        $user = User::where('login', $credentials['login'])->first();

        if(!$user || !Hash::check($credentials['password'], $user->password))
            return response()->errorJson('Login yoki parolinggiz xato!|301', 401);

//        $user = auth()->user();
//        if (!($user->profile->is_active)) {
//            return response()->errorJson('Iltimos, akkauntingizni faollashtiring!|302', 422);
//        }
        $token = $user->createToken('Token for user')->accessToken;

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post (
     * path="/auth/get-info",
     * summary="Get info user",
     * security={{ "bearerAuth": {} }},
     * description="Get info user",
     * tags={"auth"},
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
    public function me()
    {
        $user = auth()->user();

//        $userData = new UserResource($user);

        return response()->successJson($user);
    }

    /**
     * @OA\Post (
     * path="/logout",
     * summary="Logout",
     * security={{ "bearerAuth": {} }},
     * description="Logout",
     * tags={"auth"},
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
    public function logout()
    {
        Auth::logout();

        return response()->successJson(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->successJson([
            'access_token' => $token,
            'token_type' => 'bearer',
//            'expires_in' => auth('api')->factory()->getTTL(),
        ]);
    }
}
