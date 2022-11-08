<?php

namespace App\Http\Requests\Resume;

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

    protected function prepareForValidation()
    {
        $this->merge([
            'category_id' => json_decode($this->category_id),
            'about' => json_decode($this->about)
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
            'category_id' => 'required|exists:categories,id',
            'language' => 'nullable|array',
            'about' => 'nullable',
            'files.*' => 'nullable|file|max:10240',
            'skill_ids' => 'nullable|array'
        ];
    }
}
