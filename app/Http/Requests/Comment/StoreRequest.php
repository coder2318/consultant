<?php

namespace App\Http\Requests\Comment;

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
            'profile_id' => 'required|exists:profiles,id',
            'parent_id' => 'nullable|exists:comments,id',
            'text' => 'required'
        ];
    }
}
