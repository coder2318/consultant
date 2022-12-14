<?php

namespace App\Http\Requests\Resume;

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
            'category_id' => 'nullable',
            'language' => 'nullable',
            'status' => 'nullable',
            'limit' => 'nullable|numeric',
            'search' => 'nullable',
            'sort_name' => 'nullable|in:applications_count,rating',
        ];
    }
}
