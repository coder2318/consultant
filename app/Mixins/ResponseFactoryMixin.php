<?php


namespace App\Mixins;


use Illuminate\Http\JsonResponse;

class ResponseFactoryMixin
{
    const CODE_VALIDATION_ERROR = 422;
    const CODE_SUCCESS_UPDATED = 202;
    const CODE_SUCCESS = 200;
    const CODE_SUCCESS_CREATED = 201;
    const CODE_SUCCESS_DELETED = 202;
    const CODE_SUCCESS_FALSE = 555;
    const CODE_ACCESS_DENIED = 403;

    public function successJson()
    {
        return function($data = [], $code = ResponseFactoryMixin::CODE_SUCCESS){
            $result =  [
                'success'=> true,
                'data' => $data
            ];
            return new JsonResponse($result, $code);
        };
    }

    public function errorJson()
    {
        return function($message, $status, $errors = null, $data = null){
            $data = [
                'success' => false,
                'errors' => $message
            ];
            return new JsonResponse($data, $status);
        };
    }

}
