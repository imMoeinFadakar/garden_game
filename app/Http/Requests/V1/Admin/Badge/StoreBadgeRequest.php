<?php

namespace App\Http\Requests\V1\Admin\Badge;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\File;
use function response;

class StoreBadgeRequest extends FormRequest
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
           
            "description" => "required|string",
            "reward" => "required|integer",
            "image_url" => ["required","image",File::types(["jpeg","png","svg","jpg"])
            ->min(1)
            ->max(2048)],

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
