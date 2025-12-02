<?php

namespace App\Http\Requests;

class UpdateTaskRequest extends BaseTaskRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:50',
            'description' => 'sometimes|nullable|string',
            'priority' => 'sometimes|required|integer|min:1|max:5',

        ];
    }
}
