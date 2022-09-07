<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ArrayStringCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if(isset($attributes[$key]) && $attributes[$key] !== null && $attributes[$key] !== '{}') {
            $array = explode(',', str_replace(['{','}'], '', $attributes[$key]));
            return array_map('intval',$array);
        }
        return [];
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        $ids_string = implode(',', $value);
        return [
            $key => "{" . $ids_string . "}",
        ];
    }
}
