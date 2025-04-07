<?php

namespace App\Http\Requests\V1\Admin\Giftcart;

use Illuminate\Foundation\Http\FormRequest;

class StoreGiftcart extends FormRequest
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
            "value" => ["required","integer","min:1"],
            "count" => ["required","integer","min:1","max:20"],
        ];
    }
}
