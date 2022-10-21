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
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'nullable',
            'description' => 'nullable',
            'files.*' => 'nullable|file|max:15240',
            'is_visible' => 'nullable|boolean',
            'expired_date' => 'nullable|date',
            'price_from' => 'nullable|numeric',
            'price_to' => 'nullable|numeric',
            'when' => 'nullable|in:today,tomorrow,in_week,whenever',
            'when_date' => 'nullable|date',
            'status' => 'nullable|numeric|in:1,2,3,4'
        ];
    }
}
