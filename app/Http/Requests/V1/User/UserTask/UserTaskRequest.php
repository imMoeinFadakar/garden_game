<?php

namespace App\Http\Requests\V1\User\UserTask;

use Illuminate\Foundation\Http\FormRequest;

class UserTaskRequest extends FormRequest
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
            "task_id" => "required|unique:user_tasks,task_id",
        ];
    }
}
