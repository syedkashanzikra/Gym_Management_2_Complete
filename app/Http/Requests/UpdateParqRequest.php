<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParqRequest extends FormRequest
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
            'name' => 'required',
            'emergency_contact_name' => 'required',
            'emergency_contact_phone_number' => 'required',
            // 'dob' => 'required',
            'accept' => 'required|boolean',
            'email' => 'required|email',
            'questions' => 'required|array',
            'questions.*.list' => 'required|array',
            'questions.*.list.*.value' => 'required_if:questions.*.list.*.required,true|boolean',
        ];
    }
}
