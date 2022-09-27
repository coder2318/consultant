<?php

namespace App\Http\Requests\Experience;

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
            'resume_id' => 'required|exists:resumes,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'is_current_job' => 'nullable|bool',
            'company_name' => 'required',
            'profession' => 'required'
        ];
    }
}
