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

    protected function prepareForValidation()
    {
        $this->merge([
            'resume_id' => json_decode($this->resume_id),
            'category_id' => json_decode($this->category_id),
            'price_from' => json_decode($this->price_from),
            'price_to' => json_decode($this->price_to),
            'when_date' => json_decode($this->when_date),
            'title' => json_decode($this->title),
            'description' => json_decode($this->description),
            'status' => json_decode($this->status),
            'reason_inactive' => json_decode($this->reason_inactive),
        ]);
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
            'when_date' => 'nullable|date',
            'status' => 'nullable|numeric|in:1,2,3,4,5,6',
            'reason_inactive' => 'nullable'
        ];
    }
}
