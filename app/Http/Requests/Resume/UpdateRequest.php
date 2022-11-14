<?php

namespace App\Http\Requests\Resume;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'category_id' => 'nullable|exists:categories,id',
            'language' => 'nullable|array',
            'about' => 'nullable',
            'files.*' => 'nullable|file|max:10240',
            'status' => 'nullable|numeric',
            'visible' => 'nullable|boolean'
        ];
    }
}
