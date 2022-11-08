<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'resume_id' => 'nullable',
            'profile_id' => 'nullable',
            'category_id' => 'nullable',
            'price_from' => 'nullable',
            'price_to' => 'nullable',
            'when_date' => 'nullable',
            'limit' => 'nullable',
            'status' => 'nullable',
            'type' => 'nullable'
        ];
    }
}
