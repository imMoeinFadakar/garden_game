<?php

namespace App\Http\Requests\V1\Admin\Farm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\File;
use function response;

class UpdateFarmRequest extends FormRequest
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
            "name" => "required|string|unique:farms,id",
            "price" => "required|integer|min:1",
            "require_token" => ["required","integer"],
            "require_gem" => ["required","integer"],
            "require_referral" => ["required","integer"],
            "image_url" => ["required","image",File::types(["jpg","png","svg","jpeg"])],
            "description" => ["required","string"],
            "power" => ["required","integer"],
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
