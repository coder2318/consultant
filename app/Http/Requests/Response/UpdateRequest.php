<?php

namespace App\Http\Requests\Response;

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
            'application_id' => 'nullable|exists:applications,id',
            'resume_id' => 'nullable|exists:resumes,id',
            'amount' => 'nullable',
            'text' => 'nullable',
            'status' => 'nullable|numeric|in:1,2,3'
        ];
    }
}
