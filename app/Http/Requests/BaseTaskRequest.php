<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'title.required' => 'The task title is required.',
            'title.string' => 'The task title must be a valid string.',
            'title.max' => 'The task title must not exceed 50 characters.',
            'priority.required' => 'Please select a priority level.',
            'priority.in' => 'The priority must be one of: high, medium, or low.',
        ];
    }
}
