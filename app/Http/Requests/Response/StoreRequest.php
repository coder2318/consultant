<?php

namespace App\Http\Requests\Response;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'application_id' => 'required|exists:applications,id',
            'resume_id' => 'required|exists:resumes,id',
            'amount' => 'required',
            'text' => 'nullable'
        ];
    }
}
