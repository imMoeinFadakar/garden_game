<?php

namespace App\Http\Requests\V1\Admin\Farm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
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

            'name' => ['required'],
            "require_token" => ["nullable","integer"],
            "require_gem" => ["nullable","integer"],
            "require_referral" => ["nullable","integer"],
            "farm_image_url" => ["nullable","image",File::types(["jpg","png","svg","jpeg"])
                                                                        ->min(1)
                                                                        ->max(2048)       ],
            "description" => ["nullable","string"],
            "flage_image_url" => ["nullable","image",
            File::types(["jpg","png","svg","jpeg"])
            ->min(1)
            ->max(2048)],
            "power" => ["nullable","integer"],
           
            "min_token_value" => "nullable|integer|min:1",
            "max_token_value" => "nullable|integer|gt:min_token_value",
            "prodcut_image_url" => ["nullable","image",File::types(["jpg","png","jpeg","svg"])
            ->min(1)
            ->max(2048) ]
            ,      "header_bold_color" =>["nullable"],
            "header_light_color" => ["nullable"],
            "background_bold_color" => ["nullable"],
            "background_light_color" => ["nullable"],
            'farm_reward' => ['nullable','integer']

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
