<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
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
     * Prepare the data for validation.
     *
     * @return void
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'resume_id' => 'nullable|exists:resumes,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required',
            'description' => 'nullable',
            'files.*' => 'nullable|file|max:15240',
            'price_from' => 'required|numeric',
            'price_to' => 'required|numeric',
            'when_date' => 'nullable|date',
        ];
    }
}
