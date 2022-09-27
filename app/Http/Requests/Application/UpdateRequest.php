<?php

namespace App\Http\Requests\Application;

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
            'resume_id' => 'nullable|exists:resumes,id',
            'profile_id' => 'nullable|exists:profiles,id',
            'category_id' => 'nullable|exists:categories,id',
            'date' => 'nullable',
            'text' => 'nullable',
            'files.*' => 'nullable|file|max:15240'
        ];
    }
}
