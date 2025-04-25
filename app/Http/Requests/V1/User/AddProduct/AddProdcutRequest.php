<?php

namespace App\Http\Requests\V1\User\AddProduct;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddProdcutRequest extends FormRequest
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
            "farm_id" => "required|integer|exists:farms,id",
            "reward_id" =>"required|integer|exists:temporary_rewards,id",
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([

            "success" => false,
            "code" => 400,
            "message" => "validation error",
            "detail" => $validator->errors()

        ]));
    }

}
