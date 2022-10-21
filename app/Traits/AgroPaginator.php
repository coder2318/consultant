<?php


namespace App\Traits;


trait AgroPaginator
{
    public static function paginate($pagination){
        return [
            'current_page' => $pagination->currentPage(),
            'last_page' => $pagination->lastPage(),
            'per_page' => $pagination->perPage(),
            'to' => $pagination->lastItem(),
            'total' => $pagination->total(),
            'data' => $pagination->items(),
        ];
    }
}