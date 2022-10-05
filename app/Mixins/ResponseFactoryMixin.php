<?php


namespace App\Mixins;


use Illuminate\Http\JsonResponse;

class ResponseFactoryMixin
{
    public function successJson()
    {
        return function($data){
            return [
                'success'=> true,
                'data' => $data
            ];
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
