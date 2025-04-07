<?php

namespace App\Http\Requests\V1\Admin\USerReffralReward;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use function response;

class UpdateUserReffralRewardRequest extends FormRequest
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
            "reward_for_generation_one" => "required|integer|min:1",
            "reward_for_generation_two" => "required|integer|min:1",
            "reward_for_generation_three" => "required|integer|min:1",
            "reward_for_generation_four" => "required|integer|min:1",
            "farm_id" => "required|integer|exists:farms,id",
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
