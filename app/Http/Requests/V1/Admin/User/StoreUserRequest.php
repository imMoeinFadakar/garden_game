<?php

namespace App\Http\Requests\V1\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use function response;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username', // Assuming a `users` table
            'telegram_id' => 'required|string|regex:/^[0-9]+$/',
            'market_status' => 'required|string|in:active,deactive',
            'warehouse_status' => 'required|string|in:available,deactive',
            'user_status' => 'required|string|in:active,deactive',
            'remember_token' => 'nullable|string|max:200',
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "success" => false,
            "code" => 400,
            "message" => "validation failed",
            "detail" => $validator->errors()
        ]));
    }
}
