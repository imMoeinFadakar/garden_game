<?php

namespace App\Http\Requests\V1\Admin\Farm;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\File;
use function response;

class StoreFarmRequest extends FormRequest
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
            "name" => "required|string|unique:farms,name",
            "require_token" => ["required","integer"],
            "require_gem" => ["required","integer"],
            "require_referral" => ["required","integer"],
            "farm_image_url" => ["required","image",File::types(["jpg","png","svg","jpeg"])
                                                                        ->min(1)
                                                                        ->max(1024)       ],
            "description" => ["required","string"],
            "flage_image_url" => ["required","image",File::types(["jpg","png","svg","jpeg"])
            ->min(1)
            ->max(1024)       ],
            "power" => ["required","integer"],
            
            "min_token_value" => "required|integer|min:1",
            "max_token_value" => "required|integer|gt:min_token_value",
            "prodcut_image_url" => ["required","image",File::types(["jpg","png","jpeg","svg"])->min(1)->max(1024) ],
            "header_bold_color" =>["required"],
            "header_light_color" => ["required"],
            "background_bold_color" => ["required"],
            "background_light_color" => ["required"]
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
