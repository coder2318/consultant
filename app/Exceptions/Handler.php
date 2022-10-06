<?php

namespace App\Exceptions;

use App\Mixins\ResponseFactoryMixin;
use GuzzleHttp\Client;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        return $this->handleException($request, $exception);
    }

    public function handleException($request, Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->errorJson(['method' => ['Указанный метод для запроса недействителен']], 405);
        }

        if ($exception instanceof RouteNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->errorJson(['not_found' => ['Указанная ссылка не найдена']], 404);
        }
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->errorJson(['model_not_found' => ['Указанная модел не найдена']], 404);
        }


        if ($exception instanceof HttpException) {
            return response()->errorJson(['http_error'=>[$exception->getMessage()]], $exception->getStatusCode());
        }

        if ($exception instanceof ValidationException) {

            $items = $exception->validator->errors()->getMessages();
            $errors = [];
            foreach ($items as $field => $message){
                $messageStandard = [];
                foreach ($message as $key => $translate){
                    $messageStandard[] = [
                        'key' => $key,
                        'text' => $translate
                    ];
                }
                $errors[] = [
                    'field' => $field,
                    'message' => $messageStandard
                ];
            }
            return response()->errorJson($errors, ResponseFactoryMixin::CODE_VALIDATION_ERROR);
        }

        if ($exception instanceof AuthenticationException) {
            info('auth_token', [$request->header('authorization')]);
            return response()->errorJson(['auth'=>[$exception->getMessage()]], 401);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->errorJson(['permission'=>[$exception->getMessage()]], 403);
        }

//        if (\App::environment(['production'])) {
//            $this->sendError($exception);
//        }

        return response()->errorJson(['server_error' => [$exception->getMessage().' in '.$exception->getFile().":".$exception->getLine()]], 500);

    }


}
