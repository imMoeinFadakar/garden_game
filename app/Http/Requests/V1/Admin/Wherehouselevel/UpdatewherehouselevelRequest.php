<?php

namespace App\Http\Requests\V1\Admin\Wherehouselevel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use function response;

class UpdatewherehouselevelRequest extends FormRequest
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
            "level_number" => "nullable|integer",
            "overcapacity" => "nullable|integer",
            "cost_for_buy" => "nullable|integer",
            "farm_id" => ["nullable","integer","exists:farms,id"],
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
