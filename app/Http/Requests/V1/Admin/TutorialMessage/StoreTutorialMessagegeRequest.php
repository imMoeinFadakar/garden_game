<?php

namespace App\Http\Requests\V1\Admin\TutorialMessage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreTutorialMessagegeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "message" => 'required|string|max:255',
            'page' => ['required','string',Rule::in(['home','farm','team','store','market'])]
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        return new HttpResponseException(response()->json([
            "succes" => false,
            "code" => 400,
            'message' => 'validation failed',
            'detail' => $validator->errors()
        ]));
    }


}
