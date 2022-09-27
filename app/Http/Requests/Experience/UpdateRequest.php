<?php

namespace App\Http\Requests\Experience;

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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|required_if:is_current_job,false|date|after_or_equal:start_date',
            'is_current_job' => 'nullable|bool',
            'company_name' => 'nullable',
            'profession' => 'nullable'
        ];
    }
}
