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

    protected function prepareForValidation()
    {
        $this->merge([
            'language' => json_decode($this->language),
            'category_id' => json_decode($this->category_id),
            'about' => json_decode($this->about),
            'skill_ids' => json_decode($this->skill_ids),
            'status' => json_decode($this->status),
            'visible' => json_decode($this->visible),
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
            'category_id' => 'nullable|exists:categories,id',
            'language' => 'nullable|array',
            'about' => 'nullable',
            'files.*' => 'nullable|file|max:10240',
            'status' => 'nullable|numeric',
            'visible' => 'nullable|boolean'
        ];
    }
}
