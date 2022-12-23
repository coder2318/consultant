<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelResponseRequest extends FormRequest
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
            'profile_id' => 'required|exists:profiles,id',
            'chat_id' => 'required|exists:chats,id'
        ];
    }
}
